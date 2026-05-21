<?php

namespace App\Controllers;

use App\Models\MatchModel;
use App\Models\ItemModel;
use App\Models\UserModel;
use App\Libraries\EmailNotificationService;
use App\Helpers\ActivityLogger;

class MatchController extends BaseController
{
    protected $matchModel;
    protected $itemModel;
    protected $userModel;
    protected $emailService;
    protected $activityLogger;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        $this->matchModel = new MatchModel();
        $this->itemModel = new ItemModel();
        $this->userModel = new UserModel();
        $this->emailService = new EmailNotificationService();
        $this->activityLogger = ActivityLogger::getInstance();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $date = $this->request->getGet('date');
    
        $builder = $this->matchModel->orderBy('created_at', 'DESC');
    
        if ($search) {
            $builder->groupStart()
                   ->like('lost_item_title', $search)
                   ->orLike('found_item_title', $search)
                   ->orLike('lost_item_description', $search)
                   ->orLike('found_item_description', $search)
                   ->orLike('lost_item_category', $search)
                   ->orLike('found_item_category', $search)
                   ->groupEnd();
        }
    
        if ($status) {
            $builder->where('status', $status);
        }
    
        // Apply date filter when provided (match by day)
        if ($date) {
            $builder->where('created_at >=', $date . ' 00:00:00');
            $builder->where('created_at <=', $date . ' 23:59:59');
        }
    
        // Use configurable per-page limit (default: 50, but allows up to 100)
        $perPage = (int) $this->request->getGet('per_page') ?: 50;
        $perPage = min($perPage, 100); // Cap at 100 for performance
    
        $matches = $builder->paginate($perPage, 'matches');
        $pager = $this->matchModel->pager;
        $stats = $this->matchModel->getMatchStats();
    
        // Log activity for viewing matches list
        $this->activityLogger->log(
            'Matches',
            'View Matches List',
            'User viewed the matches management page',
            [
                'severity' => 'low',
                'entity_type' => 'matches',
                'entity_id' => session()->get('user_id'),
                'new_values' => [
                    'total_matches' => count($matches),
                    'status_filter' => $status,
                    'search_query' => $search,
                    'date_filter' => $date,
                    'user_id' => session()->get('user_id'),
                    'user_email' => session()->get('email')
                ]
            ]
        );
    
        return view('matches/index', [
            'matches' => $matches,
            'pager' => $pager,
            'stats' => $stats,
            'currentStatus' => $status,
            'searchQuery' => $search,
            'currentDate' => $date,
            'per_page' => $perPage
        ]);
    }

    public function view($id = null)
    {
        $match = $this->getMatchDetails($id);
        
        // Log activity for viewing match details
        $this->activityLogger->log(
            'Matches',
            'View Match Details',
            "User viewed details for match ID {$id}",
            [
                'severity' => 'low',
                'entity_type' => 'match',
                'entity_id' => $id,
                'new_values' => [
                    'match_status' => $match['status'],
                    'match_score' => $match['score'],
                    'lost_item_title' => $match['lost_item_title'],
                    'found_item_title' => $match['found_item_title'],
                    'user_id' => session()->get('user_id')
                ]
            ]
        );
        
        return view('matches/view', ['match' => $match]);
    }

    public function edit($id = null)
    {
        $match = $this->getMatchDetails($id);
        return view('matches/edit', ['match' => $match]);
    }

    public function update($id = null)
    {
        $rules = [
            'status' => 'required|in_list[pending,accepted,rejected,resolved]',
        ];
    
        if (!$this->validate($rules)) {
            return view('matches/edit', [
                'validation' => $this->validator,
                'match' => $this->getMatchDetails($id)
            ]);
        }
    
        $status = $this->request->getPost('status');
        $notes = $this->request->getPost('notes');
        $oldMatch = $this->matchModel->find($id);
    
        $updateData = ['status' => $status];
        if ($notes) {
            $updateData['notes'] = $notes;
        }
    
        $this->matchModel->update($id, $updateData);
        
        // Log match status change activity
        $this->activityLogger->log(
            'Matches',
            'Update Match Status',
            "Match status changed from '{$oldMatch['status']}' to '{$status}'",
            [
                'severity' => 'medium',
                'entity_type' => 'match',
                'entity_id' => $id,
                'new_values' => [
                    'old_status' => $oldMatch['status'],
                    'new_status' => $status,
                    'notes' => $notes,
                    'lost_item_id' => $oldMatch['lost_item_id'],
                    'found_item_id' => $oldMatch['found_item_id'],
                    'match_score' => $oldMatch['score'],
                    'user_id' => session()->get('user_id')
                ]
            ]
        );
    
        // Get the match details
        $match = $this->matchModel->find($id);
        
        if ($match) {
            // Update related items status based on match status
            $itemStatus = 'active';
            $claimStatus = 'pending';
            
            switch ($status) {
                case 'accepted':
                    $itemStatus = 'active'; // Keep items active until match is resolved
                    $claimStatus = 'approved';
                    break;
                case 'resolved':
                    // For resolved status, we'll handle lost and found items separately
                    $claimStatus = 'resolved';
                    break;
                case 'rejected':
                    $itemStatus = 'active';
                    $claimStatus = 'rejected';
                    break;
                case 'pending':
                    $itemStatus = 'active';
                    $claimStatus = 'pending';
                    break;
            }
            
            // Update both lost and found items
            if ($status === 'resolved') {
                // When resolved: lost items = 'claimed', found items = 'returned'
                if ($match['lost_item_id']) {
                    $this->itemModel->update($match['lost_item_id'], ['status' => 'claimed']);
                }
                if ($match['found_item_id']) {
                    $this->itemModel->update($match['found_item_id'], ['status' => 'returned']);
                }
            } else {
                // For other statuses, use the same status for both items
                if ($match['lost_item_id']) {
                    $this->itemModel->update($match['lost_item_id'], ['status' => $itemStatus]);
                }
                if ($match['found_item_id']) {
                    $this->itemModel->update($match['found_item_id'], ['status' => $itemStatus]);
                }
            }
            
            // Update related claims
            $claimModel = new \App\Models\ClaimModel();
            $claims = $claimModel->where('match_id', $id)->findAll();
            foreach ($claims as $claim) {
                $claimModel->update($claim['id'], ['status' => $claimStatus]);
            }
            
            // Log the status change
            log_message('info', "Match {$id} status changed from {$oldMatch['status']} to {$status}. Related items and claims updated accordingly.");
            
            // Send email notification for status change
            $this->sendMatchStatusEmail($match, $status, $oldMatch['status']);
        }

        session()->setFlashdata('success', 'Match updated successfully. Related items and claims have been synchronized.');
        return redirect()->to('/match');
    }

    public function delete($id = null)
    {
        $match = $this->matchModel->find($id);
        if ($match) {
            $this->matchModel->delete($id);
            
            // Log match deletion activity
            $this->activityLogger->log(
                'Matches',
                'Delete Match',
                "Match deleted with ID {$id}",
                [
                    'severity' => 'critical',
                    'entity_type' => 'match',
                    'entity_id' => $id,
                    'new_values' => [
                        'match_status' => $match['status'],
                        'match_score' => $match['score'],
                        'lost_item_id' => $match['lost_item_id'],
                        'found_item_id' => $match['found_item_id'],
                        'lost_item_title' => $match['lost_item_title'],
                        'found_item_title' => $match['found_item_title'],
                        'user_id' => session()->get('user_id')
                    ]
                ]
            );
            
            session()->setFlashdata('success', 'Match deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Match not found.');
        }
        return redirect()->to('/match');
    }

    public function approve($id = null)
    {
        $match = $this->matchModel->find($id);
        if (!$match) {
            session()->setFlashdata('error', 'Match not found.');
            return redirect()->to('/match');
        }

        $this->matchModel->updateStatus($id, 'accepted');
        
        // Update related items - keep them active until match is resolved
        $this->itemModel->update($match['lost_item_id'], ['status' => 'active']);
        $this->itemModel->update($match['found_item_id'], ['status' => 'active']);

        // Log match approval activity
        $this->activityLogger->log(
            'Matches',
            'Approve Match',
            "Match approved with ID {$id}",
            [
                'severity' => 'medium',
                'entity_type' => 'match',
                'entity_id' => $id,
                'new_values' => [
                    'old_status' => $match['status'],
                    'new_status' => 'accepted',
                    'lost_item_id' => $match['lost_item_id'],
                    'found_item_id' => $match['found_item_id'],
                    'match_score' => $match['score'],
                    'user_id' => session()->get('user_id')
                ]
            ]
        );

        session()->setFlashdata('success', 'Match approved successfully.');
        return redirect()->to('/match');
    }

    public function reject($id = null)
    {
        $match = $this->matchModel->find($id);
        if (!$match) {
            session()->setFlashdata('error', 'Match not found.');
            return redirect()->to('/match');
        }

        $this->matchModel->updateStatus($id, 'rejected');
        
        // Log match rejection activity
        $this->activityLogger->log(
            'Matches',
            'Reject Match',
            "Match rejected with ID {$id}",
            [
                'severity' => 'medium',
                'entity_type' => 'match',
                'entity_id' => $id,
                'new_values' => [
                    'old_status' => $match['status'],
                    'new_status' => 'rejected',
                    'lost_item_id' => $match['lost_item_id'],
                    'found_item_id' => $match['found_item_id'],
                    'match_score' => $match['score'],
                    'user_id' => session()->get('user_id')
                ]
            ]
        );
        
        session()->setFlashdata('success', 'Match rejected successfully.');
        return redirect()->to('/match');
    }

    public function resolve($id = null)
    {
        $match = $this->matchModel->find($id);
        if (!$match) {
            session()->setFlashdata('error', 'Match not found.');
            return redirect()->to('/match');
        }

        $this->matchModel->updateStatus($id, 'resolved');
        
        // Update related items: lost items to 'claimed', found items to 'returned'
        $this->itemModel->update($match['lost_item_id'], ['status' => 'claimed']);
        $this->itemModel->update($match['found_item_id'], ['status' => 'returned']);

        // Log match resolution activity
        $this->activityLogger->log(
            'Matches',
            'Resolve Match',
            "Match resolved with ID {$id}",
            [
                'severity' => 'medium',
                'entity_type' => 'match',
                'entity_id' => $id,
                'new_values' => [
                    'old_status' => $match['status'],
                    'new_status' => 'resolved',
                    'lost_item_id' => $match['lost_item_id'],
                    'found_item_id' => $match['found_item_id'],
                    'match_score' => $match['score'],
                    'user_id' => session()->get('user_id')
                ]
            ]
        );

        session()->setFlashdata('success', 'Match resolved successfully.');
        return redirect()->to('/match');
    }

    public function bulkAction()
    {
        $action = $this->request->getPost('bulk_action');
        $selectedMatches = $this->request->getPost('selected_matches');

        if (empty($selectedMatches)) {
            session()->setFlashdata('warning', 'No matches selected.');
            return redirect()->to('/match');
        }

        $count = 0;
        foreach ($selectedMatches as $matchId) {
            switch ($action) {
                case 'approve':
                    $this->approve($matchId);
                    $count++;
                    break;
                case 'reject':
                    $this->reject($matchId);
                    $count++;
                    break;
                case 'delete':
                    $this->delete($matchId);
                    $count++;
                    break;
            }
        }

        session()->setFlashdata('success', "{$count} matches processed successfully.");
        return redirect()->to('/match');
    }

    private function getMatchDetails($matchId)
    {
        $match = $this->matchModel->find($matchId);

        if (!$match) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Match not found');
        }

        return $match;
    }

    public function export()
    {
        $matches = $this->matchModel->findAll();
        
        // Log match export activity
        $this->activityLogger->log(
            'Matches',
            'Export Matches',
            'User exported matches data to CSV',
            [
                'severity' => 'low',
                'entity_type' => 'matches',
                'entity_id' => session()->get('user_id'),
                'new_values' => [
                    'total_matches_exported' => count($matches),
                    'export_format' => 'CSV',
                    'export_date' => date('Y-m-d H:i:s'),
                    'user_id' => session()->get('user_id'),
                    'user_email' => session()->get('email')
                ]
            ]
        );
        
        // Set headers for CSV download
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="matches_export_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'ID', 'Status', 'Score', 'Lost Item Title', 'Found Item Title',
            'Lost Item Category', 'Found Item Category', 'Lost Item Location',
            'Found Item Location', 'Created At', 'Updated At'
        ]);
        
        // CSV data
        foreach ($matches as $match) {
            fputcsv($output, [
                $match['id'],
                $match['status'],
                $match['score'],
                $match['lost_item_title'],
                $match['found_item_title'],
                $match['lost_item_category'],
                $match['found_item_category'],
                $match['lost_item_location'],
                $match['found_item_location'],
                $match['created_at'],
                $match['updated_at']
            ]);
        }
        
        fclose($output);
        exit;
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
     * Send email notification for match status change
     * 
     * @param array $match Match data with details
     * @param string $newStatus New match status
     * @param string $oldStatus Previous match status
     * @return void
     */
    private function sendMatchStatusEmail($match, $newStatus, $oldStatus)
    {
        try {
            // Only send emails for significant status changes
            if (!in_array($newStatus, ['accepted', 'rejected', 'resolved'])) {
                return;
            }
            
            // Get match details with reporter information
            $matchDetails = $this->getMatchDetails($match['id']);
            if (!$matchDetails) {
                return;
            }
            
            // Get lost item reporter data
            $lostReporterData = $this->getUserData(
                $matchDetails['lost_item_reporter_email'], 
                $matchDetails['lost_item_reporter_name']
            );
            
            // Get found item reporter data
            $foundReporterData = $this->getUserData(
                $matchDetails['found_item_reporter_email'], 
                $matchDetails['found_item_reporter_name']
            );
            
            // Prepare match data for email
            $matchData = [
                'id' => $match['id'],
                'status' => $newStatus,
                'old_status' => $oldStatus,
                'score' => $matchDetails['score'],
                'lost_item' => [
                    'title' => $matchDetails['lost_item_title'],
                    'description' => $matchDetails['lost_item_description'],
                    'category' => $matchDetails['lost_item_category'],
                    'location' => $matchDetails['lost_item_location'],
                    'date_lost' => $matchDetails['lost_item_date_lost_found'],
                    'reporter_name' => $matchDetails['lost_item_reporter_name']
                ],
                'found_item' => [
                    'title' => $matchDetails['found_item_title'],
                    'description' => $matchDetails['found_item_description'],
                    'category' => $matchDetails['found_item_category'],
                    'location' => $matchDetails['found_item_location'],
                    'date_found' => $matchDetails['found_item_date_lost_found'],
                    'reporter_name' => $matchDetails['found_item_reporter_name']
                ],
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Send notification to lost item reporter
            $this->emailService->sendMatchStatusNotification($matchData, $lostReporterData, 'lost');
            
            // Send notification to found item reporter
            $this->emailService->sendMatchStatusNotification($matchData, $foundReporterData, 'found');
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to send match status notification: ' . $e->getMessage());
        }
    }
}