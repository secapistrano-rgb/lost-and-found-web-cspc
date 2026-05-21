<?php

namespace App\Controllers;

use App\Models\ClaimModel;
use App\Models\MatchModel;
use App\Models\ItemModel;
use App\Models\UserModel;
use App\Models\NotificationModel;
use App\Models\AdminRoleModel;
use App\Libraries\EmailNotificationService;
use App\Helpers\ActivityLogger;
use CodeIgniter\Controller;
class ClaimController extends BaseController
{
    protected $claimModel;
    protected $matchModel;
    protected $itemModel;
    protected $userModel;
    protected $emailService;
    protected $notificationService;
    protected $activityLogger;
    protected $notificationModel;
    protected $adminRoleModel;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        $this->claimModel = new ClaimModel();
        $this->matchModel = new MatchModel();
        $this->itemModel = new ItemModel();
        $this->userModel = new UserModel();
        $this->emailService = new EmailNotificationService();
        $this->notificationService = new EmailNotificationService();
        $this->activityLogger = ActivityLogger::getInstance();
        $this->notificationModel = new NotificationModel();
        $this->adminRoleModel = new AdminRoleModel();
    }

    public function index()
    {
        // Use configurable per-page limit (default: 50, but allows up to 100)
        $perPage = (int) $this->request->getGet('per_page') ?: 50;
        $perPage = min($perPage, 100); // Cap at 100 for performance
        
        $claims = $this->claimModel
            ->select('claims.id, claims.item_id, claims.match_id, claims.claimant_id, claims.claim_type, claims.status, claims.proof_of_ownership, claims.image, claims.google_user_id, claims.created_at, claims.updated_at, matches.id as match_id, matches.score, matches.status as match_status, 
                     matches.lost_item_title, matches.lost_item_category, matches.lost_item_images, matches.lost_item_location,
                     matches.found_item_title, matches.found_item_category, matches.found_item_images, matches.found_item_location,
                     matches.lost_item_date_lost_found, matches.found_item_date_lost_found,
                     matches.lost_item_reporter_name, matches.lost_item_reporter_email,
                     matches.found_item_reporter_name, matches.found_item_reporter_email,
                     users.full_name as claimant_name, claims.claimant_id as claimant_email')
            ->join('matches', 'matches.id = claims.match_id', 'left')
            ->join('users', 'users.email = claims.claimant_id', 'left')
            ->orderBy('claims.created_at', 'DESC')
            ->paginate($perPage, 'claims');
    
        $pager = $this->claimModel->pager;
        
        // Log activity for viewing claims list
        $this->activityLogger->log(
            'Claims',
            'View Claims List',
            'User viewed the claims management page',
            [
                'severity' => 'low',
                'entity_type' => 'claims',
                'entity_id' => session()->get('user_id'),
                'new_values' => [
                    'total_claims' => count($claims),
                    'user_id' => session()->get('user_id'),
                    'user_email' => session()->get('email')
                ]
            ]
        );
    
        return view('claims/index', ['claims' => $claims, 'pager' => $pager, 'per_page' => $perPage]);
    }

    public function create()
    {
        $matches = $this->matchModel
            ->where('status', 'accepted') // Only accepted matches can be claimed
            ->orderBy('created_at', 'DESC')
            ->findAll();

        if (empty($matches)) {
            session()->setFlashdata('warning', 'No accepted matches are available for claiming right now.');
            return redirect()->to('/match');
        }

        return view('claims/create', ['matches' => $matches]);
    }

    public function store()
    {
        // This method is deprecated - claims are now created through match acceptance
        // Redirect to matches page
        session()->setFlashdata('info', 'Claims are now created by accepting matches.');
        return redirect()->to('/match');
    }

    public function edit($id = null)
    {
        $claim = $this->getClaimWithMatchDetails($id);
        return view('claims/edit', ['claim' => $claim]);
    }

    public function update($id = null)
    {
        $rules = [
            'status' => 'required|in_list[pending,approved,rejected,resolved]',
        ];
    
        if (!$this->validate($rules)) {
            return view('claims/edit', [
                'validation' => $this->validator,
                'claim' => $this->getClaimWithMatchDetails($id)
            ]);
        }
    
        $status = $this->request->getPost('status');
        $oldClaim = $this->getClaimWithMatchDetails($id);
    
        // Update the claim status
        $this->claimModel->update($id, [
            'status' => $status
        ]);
        
        // Log claim status change activity
        $this->activityLogger->log(
            'Claims',
            'Update Claim Status',
            "Claim status changed from '{$oldClaim['status']}' to '{$status}'",
            [
                'severity' => 'high',
                'entity_type' => 'claim',
                'entity_id' => $id,
                'new_values' => [
                    'old_status' => $oldClaim['status'],
                    'new_status' => $status,
                    'match_id' => $oldClaim['match_id'],
                    'claimant_email' => $oldClaim['claimant_id'],
                    'claimant_name' => $oldClaim['claimant_name'] ?? 'N/A',
                    'user_id' => session()->get('user_id')
                ]
            ]
        );
        

    
        // Get the updated claim with match details and claimant info
        $claim = $this->claimModel
            ->select('claims.id, claims.item_id, claims.match_id, claims.claimant_id, claims.claim_type, claims.status, claims.proof_of_ownership, claims.image, claims.google_user_id, claims.created_at, claims.updated_at, claims.claimant_id as claimant_email, matches.lost_item_id, matches.found_item_id, matches.id as match_id,
                     matches.lost_item_title, matches.lost_item_category, matches.lost_item_location,
                     matches.found_item_title, matches.found_item_category, matches.found_item_location,
                     matches.lost_item_reporter_name, matches.lost_item_reporter_email,
                     matches.found_item_reporter_name, matches.found_item_reporter_email,
                     users.full_name as claimant_name')
            ->join('matches', 'matches.id = claims.match_id', 'left')
            ->join('users', 'users.email = claims.claimant_id', 'left')
            ->where('claims.id', $id)
            ->first();
    
        if ($claim && $claim['match_id']) {
            // Update match status based on claim status
            $matchStatus = 'pending';
            $itemStatus = 'active';
            
            switch ($status) {
                case 'approved':
                    $matchStatus = 'accepted';
                    $itemStatus = 'active'; // Keep items active until claim is resolved
                    
                    // Automatically reject other pending claims for the same match
                    $this->rejectOtherClaims($claim['match_id'], $id);
                    break;
                case 'resolved':
                    $matchStatus = 'resolved';
                    // For resolved claims, lost items should be 'claimed' and found items should be 'returned'
                    // We'll handle this separately for each item type below
                    break;
                case 'rejected':
                    $matchStatus = 'rejected';
                    $itemStatus = 'active'; // Return to active if claim is rejected
                    break;
                case 'pending':
                    $matchStatus = 'pending';
                    $itemStatus = 'active';
                    break;
            }
            
            // Update the match status
            $this->matchModel->update($claim['match_id'], ['status' => $matchStatus]);
            
            // Update related items status
            if ($status === 'resolved') {
                // When resolved: lost items = 'claimed', found items = 'returned'
                if ($claim['lost_item_id']) {
                    $this->itemModel->update($claim['lost_item_id'], ['status' => 'claimed']);
                }
                if ($claim['found_item_id']) {
                    $this->itemModel->update($claim['found_item_id'], ['status' => 'returned']);
                }
            } else {
                // For other statuses, use the same status for both items
                if ($claim['lost_item_id']) {
                    $this->itemModel->update($claim['lost_item_id'], ['status' => $itemStatus]);
                }
                if ($claim['found_item_id']) {
                    $this->itemModel->update($claim['found_item_id'], ['status' => $itemStatus]);
                }
            }
            
            // Log the status change for audit purposes
            log_message('info', "Claim {$id} status changed from {$oldClaim['status']} to {$status}. Match {$claim['match_id']} and items updated accordingly.");
            
            // Send email notification for status change
            $this->sendClaimStatusEmail($claim, $status, $oldClaim['status']);
        }
    
        session()->setFlashdata('success', 'Claim updated successfully. Related match and items have been synchronized.');
        return redirect()->to('/claims');
    }

    public function delete($id = null)
    {
        try {
            // Log the delete attempt
            log_message('info', 'Attempting to delete claim with ID: ' . $id);
            
            // Check if claim exists (including soft deleted ones)
            $claim = $this->claimModel->withDeleted()->find($id);
            
            if ($claim) {
                log_message('info', 'Claim found, proceeding with deletion');
                
                // Force delete (hard delete) instead of soft delete
                $result = $this->claimModel->delete($id, true);
                
                if ($result) {
                    log_message('info', 'Claim deleted successfully');
                    
                    // Log claim deletion activity
                     $this->activityLogger->log(
                         'Claims',
                         'Delete Claim',
                         "Claim deleted for match ID {$claim['match_id']}",
                         [
                             'severity' => 'critical',
                             'entity_type' => 'claim',
                             'entity_id' => $id,
                             'new_values' => [
                                 'match_id' => $claim['match_id'],
                                 'claimant_email' => $claim['claimant_id'] ?? 'Unknown',
                                 'claimant_name' => $claim['claimant_name'] ?? 'Unknown',
                                 'status' => $claim['status'],
                                 'proof_of_ownership' => $claim['proof_of_ownership'] ?? 'N/A',
                                 'user_id' => session()->get('user_id')
                             ]
                         ]
                     );
                    

                    
                    session()->setFlashdata('success', 'Claim deleted successfully.');
                } else {
                    log_message('error', 'Failed to delete claim - database operation failed');
                    session()->setFlashdata('error', 'Failed to delete claim.');
                }
            } else {
                log_message('warning', 'Claim not found with ID: ' . $id);
                session()->setFlashdata('error', 'Claim not found.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception during claim deletion: ' . $e->getMessage());
            session()->setFlashdata('error', 'An error occurred while deleting the claim: ' . $e->getMessage());
        }
        
        return redirect()->to('/claims');
    }

    public function view($id = null)
    {
        $claim = $this->getClaimWithMatchDetails($id);
        return view('claims/view', ['claim' => $claim]);
    }

    private function getClaimWithMatchDetails($claimId)
    {
        $claim = $this->claimModel
            ->select('claims.id, claims.item_id, claims.match_id, claims.claimant_id, claims.claim_type, claims.status, claims.proof_of_ownership, claims.image, claims.google_user_id, claims.created_at, claims.updated_at, claims.claimant_id as claimant_email, matches.id as match_id, matches.score, matches.status as match_status,
                     matches.lost_item_title, matches.lost_item_description, matches.lost_item_category, 
                     matches.lost_item_images, matches.lost_item_location, matches.lost_item_date_lost_found,
                     matches.found_item_title, matches.found_item_description, matches.found_item_category,
                     matches.found_item_images, matches.found_item_location, matches.found_item_date_lost_found,
                     matches.lost_item_reporter_name, matches.lost_item_reporter_email,
                     matches.found_item_reporter_name, matches.found_item_reporter_email,
                     users.full_name as claimant_name')
            ->join('matches', 'matches.id = claims.match_id', 'left')
            ->join('users', 'users.email = claims.claimant_id', 'left')
            ->where('claims.id', $claimId)
            ->first();

        if (!$claim) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Claim not found');
        }
        return $claim;
    }
    
    /**
     * Send email notification for claim status change
     * 
     * @param array $claim Claim data with match details
     * @param string $newStatus New claim status
     * @param string $oldStatus Previous claim status
     * @return void
     */
    private function sendClaimStatusEmail($claim, $newStatus, $oldStatus)
    {
        try {
            // Only send emails for approved, rejected, or resolved status changes
            if (!in_array($newStatus, ['approved', 'rejected', 'resolved'])) {
                return;
            }
            
            // Get claimant user data
            $userData = $this->getUserData($claim['claimant_email'] ?? $claim['claimant_id'], $claim['claimant_name']);
            
            // Prepare claim data for email
            $claimData = [
                'id' => $claim['id'],
                'status' => $newStatus,
                'claimant_name' => $claim['claimant_name'],
                'item_name' => $claim['lost_item_title'] ?? $claim['found_item_title'],
                'item_category' => $claim['lost_item_category'] ?? $claim['found_item_category'],
                'item_location' => $claim['lost_item_location'] ?? $claim['found_item_location'],
                'claim_description' => $claim['proof_of_ownership'],
                'updated_at' => date('Y-m-d H:i:s'),
                'match_id' => $claim['match_id']
            ];
            
            // Prepare item data for finder notifications
            $itemData = [
                'name' => $claim['lost_item_title'] ?? $claim['found_item_title'],
                'category' => $claim['lost_item_category'] ?? $claim['found_item_category'],
                'location' => $claim['lost_item_location'] ?? $claim['found_item_location'],
                'date_claimed' => date('Y-m-d H:i:s'),
                'date_returned' => date('Y-m-d H:i:s')
            ];
            
            // Send appropriate email based on status
            if ($newStatus === 'approved') {
                // Send notification to claimant
                $this->emailService->sendClaimApprovalNotification($claimData, $userData);
                
                // Send notification to finder
                $this->sendFinderNotification($claim, 'approved', $itemData, $claimData);
                
            } elseif ($newStatus === 'rejected') {
                // Send rejection notification to claimant
                $this->emailService->sendClaimRejectionNotification($claimData, $userData);
                
            } elseif ($newStatus === 'resolved') {
                // Send resolution notification to claimant
                $this->emailService->sendClaimResolvedNotification($claimData, $userData);
                
                // Send notification to finder that item was returned
                $this->sendFinderNotification($claim, 'resolved', $itemData, $claimData);
                
                // Notify all admins that a turned-in item has been claimed
                $this->notifyAdminsOfClaimedItem($claim);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to send claim status email: ' . $e->getMessage());
        }
    }
    
    /**
     * Send finder notification for claim status changes
     * 
     * @param array $claim Claim data with match details
     * @param string $status Claim status (approved or resolved)
     * @param array $itemData Item data
     * @param array $claimData Claim data
     * @return void
     */
    private function sendFinderNotification($claim, $status, $itemData, $claimData)
    {
        try {
            // Get finder information from the match data
            $finderEmail = $claim['found_item_reporter_email'] ?? null;
            $finderName = $claim['found_item_reporter_name'] ?? 'Finder';
            
            if (!$finderEmail) {
                log_message('warning', 'No finder email found for claim notification');
                return;
            }
            
            $finderData = $this->getUserData($finderEmail, $finderName);
            
            if ($status === 'approved') {
                $this->emailService->sendFinderNotificationApproved($itemData, $finderData, $claimData);
            } elseif ($status === 'resolved') {
                $this->emailService->sendFinderNotificationResolved($itemData, $finderData);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to send finder notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Get user data for email notification
     * 
     * @param string $email User email
     * @param string $name User name
     * @return array User data
     */
    private function getUserData($email, $name)
    {
        // Try to get user from database
        $user = $this->userModel->where('email', $email)->first();
        
        if ($user) {
            return [
                'email' => $user['email'],
                'full_name' => $user['full_name'] ?? $name
            ];
        }
        
        // Return basic user data if not found in database
        return [
            'email' => $email,
            'full_name' => $name ?? $email
        ];
    }
    
    /**
     * Simplified getUserData method for inline use
     * 
     * @param string $email User email
     * @param string $name User name
     * @return array User data
     */
    private function getSimpleUserData($email, $name)
    {
        return ['full_name' => $name ?? 'User', 'email' => $email];
    }

    /**
     * Automatically reject other pending claims for the same match when one is approved
     */
    private function rejectOtherClaims($matchId, $approvedClaimId)
    {
        // Find all other pending claims for the same match with claimant details
        $otherClaims = $this->claimModel
            ->select('claims.id, claims.item_id, claims.match_id, claims.claimant_id, claims.claim_type, claims.status, claims.proof_of_ownership, claims.image, claims.google_user_id, claims.created_at, claims.updated_at, users.full_name as claimant_name, claims.claimant_id as claimant_email')
            ->join('users', 'users.email = claims.claimant_id', 'left')
            ->where('claims.match_id', $matchId)
            ->where('claims.id !=', $approvedClaimId)
            ->where('claims.status', 'pending')
            ->findAll();

        foreach ($otherClaims as $claim) {
            // Update claim status to rejected
            $this->claimModel->update($claim['id'], ['status' => 'rejected']);
            
            // Log the automatic rejection
            log_message('info', "Automatically rejected claim ID {$claim['id']} because claim ID {$approvedClaimId} was approved for match ID {$matchId}");
            
            // Send rejection notification email
            try {
                $userData = $this->getUserData($claim['claimant_email'] ?? $claim['claimant_id'], $claim['claimant_name'] ?? 'Unknown');
                $this->emailService->sendClaimRejectionNotification($claim, $userData);
                $recipientEmail = $claim['claimant_email'] ?? $claim['claimant_id'];
                log_message('info', "Rejection notification sent to {$recipientEmail} for automatically rejected claim ID {$claim['id']}");
            } catch (\Exception $e) {
                log_message('error', "Failed to send rejection notification for claim ID {$claim['id']}: " . $e->getMessage());
            }
        }
    }

    /**
     * Notify all admins when a turned-in item is claimed (resolved)
     * 
     * @param array $claim Claim data with match details
     * @return void
     */
    private function notifyAdminsOfClaimedItem($claim)
    {
        try {
            // Get all admins
            $admins = $this->adminRoleModel->findAll();
            
            if (empty($admins)) {
                log_message('warning', 'No admins found to notify about claimed item');
                return;
            }
            
            // Get item details
            $itemTitle = $claim['found_item_title'] ?? $claim['lost_item_title'] ?? 'Unknown Item';
            $claimantName = $claim['claimant_name'] ?? 'Unknown User';
            $claimantEmail = $claim['claimant_email'] ?? $claim['claimant_id'] ?? 'Unknown';
            
            // Create notification for each admin
            foreach ($admins as $admin) {
                $notificationData = [
                    'user_email' => $admin['email'],
                    'user_name' => $admin['name'] ?? 'Admin',
                    'title' => 'Item Claimed - Turned-In Item Returned',
                    'message' => "A turned-in item '{$itemTitle}' has been claimed and returned to {$claimantName} ({$claimantEmail}).",
                    'type' => 'claim',
                    'entity_type' => 'claim',
                    'entity_id' => $claim['id'],
                    'priority' => 'medium',
                    'action_url' => site_url('claims/view/' . $claim['id']),
                    'metadata' => [
                        'claim_id' => $claim['id'],
                        'item_title' => $itemTitle,
                        'claimant_name' => $claimantName,
                        'claimant_email' => $claimantEmail,
                        'status' => 'resolved'
                    ]
                ];
                
                $this->notificationModel->createNotification($notificationData);
            }
            
            log_message('info', "Notified " . count($admins) . " admin(s) about claimed item for claim ID {$claim['id']}");
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to notify admins of claimed item: ' . $e->getMessage());
        }
    }
}
