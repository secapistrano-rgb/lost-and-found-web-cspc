<?php namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\ClaimModel;
use App\Models\NotificationModel;
use App\Models\AdminRoleModel;
use App\Libraries\EmailNotificationService;
use App\Libraries\MatchingService;
use App\Models\UserModel;
use App\Helpers\ActivityLogger;

class ItemController extends BaseController
{
    protected $itemModel;
    protected $claimModel;
    protected $uploadPath;
    protected $categories;
    protected $emailService;
    protected $notificationService;
    protected $userModel;
    protected $activityLogger;
    protected $notificationModel;
    protected $adminRoleModel;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        $this->itemModel = new ItemModel();
        $this->claimModel = new ClaimModel();
        $this->uploadPath = FCPATH . 'uploads/';
        $this->emailService = new EmailNotificationService();
        $this->notificationService = new EmailNotificationService();
        $this->userModel = new UserModel();
        $this->activityLogger = ActivityLogger::getInstance();
        $this->notificationModel = new NotificationModel();
        $this->adminRoleModel = new AdminRoleModel();
        // Remove the categories array since it's no longer needed
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $statusFilter = $this->request->getGet('status');
        $typeFilter = $this->request->getGet('type');
        
        $builder = new ItemModel();

        if ($search) {
            $builder->groupStart()
                    ->like('title', $search)
                    ->orLike('description', $search)
                    ->groupEnd();
        }

        if ($category) {
            $builder->where('category', $category);
        }

        if ($statusFilter) {
            $builder->where('status', $statusFilter);
        }

        if ($typeFilter) {
            $builder->where('type', $typeFilter);
        }

        // Use configurable per-page limit (default: 50, but allows up to 100)
        $perPage = (int) $this->request->getGet('per_page') ?: 50;
        $perPage = min($perPage, 100); // Cap at 100 for performance

        $allItems = $builder->orderBy('created_at', 'desc')->paginate($perPage, 'items');
        
        $pager = $builder->pager;
        
        // Add claim information to each item
        foreach ($allItems as &$item) {
            // Get claim count
            $item['claim_count'] = $this->claimModel->where('item_id', $item['id'])->countAllResults();
            
            // Get verified claim if exists
            $verifiedClaim = $this->claimModel
                ->where('item_id', $item['id'])
                ->where('status', 'verified')
                ->first();
            
            if ($verifiedClaim) {
                $item['verified_claim'] = $verifiedClaim;
            }
        }

        // Log items list view activity
        $this->activityLogger->log(
            'Item Management',
            'list_view',
            'Viewed items list',
            [
                'severity' => 'low',
                'search' => $search,
                'category' => $category,
                'status_filter' => $statusFilter,
                'type_filter' => $typeFilter,
                'total_items' => count($allItems)
            ]
        );

        return view('dashboard/items', [
            'allItems' => $allItems,
            'categories' => $this->categories,
            'search' => $search,
            'selectedCategory' => $category,
            'selectedStatus' => $statusFilter,
            'selectedType' => $typeFilter,
            'pager' => $pager,
            'per_page' => $perPage,
        ]);
    }

    public function create()
    {
        return view('items/create'); // Remove categories parameter
    }

    public function edit($id = null)
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Item not found');
        }
        
        return view('items/edit', [
            'item' => $item
            // Remove categories parameter
        ]);
    }

    public function store()
    {
        $validationRules = [
            'title' => 'required|min_length[3]',
            'description' => 'required|min_length[5]',
            'category' => 'required|min_length[2]',
            'status' => 'required|in_list[active,inactive,claimed,returned,Pending Turning-in,Fake Report]',
            'type' => 'required|in_list[found,lost]',
            'date_lost_found' => 'required|valid_date[Y-m-d]',
            'reporter_email' => 'required|valid_email',
            'images' => 'uploaded[images]|is_image[images]|max_size[images,2048]'
        ];
    
        if (!$this->validate($validationRules)) {
            return view('items/create', [
                'validation' => $this->validator
            ]);
        }
    
        $imageFile = $this->request->getFile('images');
        $imageName = null;
        if ($imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move($this->uploadPath, $imageName);
        }
    
        // Generate a unique ID for the item
        $itemId = uniqid('item_', true);
    
        $itemData = [
            'id' => $itemId,
            'title' => $this->request->getPost('title'),    
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'status' => $this->request->getPost('status'),
            'type' => $this->request->getPost('type'),
            'images' => $imageName ? json_encode([$imageName]) : null,
            'reporter_email' => $this->request->getPost('reporter_email'),
            'date_lost_found' => $this->request->getPost('date_lost_found'),
            'reporter_name' => $this->request->getPost('reporter_name'),
            'reporter_department' => $this->request->getPost('reporter_department'),
            'location' => $this->request->getPost('location'),
            'reporter_id' => $this->request->getPost('reporter_email') // Using email as reporter_id for now
        ];
        
        $this->itemModel->save($itemData);
        
        // Log item creation activity
        $this->activityLogger->log(
            'Item Management',
            'item_create',
            'Created new ' . $itemData['type'] . ' item: ' . $itemData['title'],
            [
                'severity' => 'medium',
                'entity_type' => 'item',
                'entity_id' => $itemData['id'],
                'item_type' => $itemData['type'],
                'category' => $itemData['category'],
                'status' => $itemData['status'],
                'reporter_email' => $itemData['reporter_id']
            ]
        );
        

        
        // Find potential matches automatically
        $matchingService = new MatchingService();
        $matches = $matchingService->findMatches($itemData);
        
        if (!empty($matches)) {
            log_message('info', "Found " . count($matches) . " potential matches for item {$itemData['id']}");
        }
        
        // Send automated email notification
        $this->sendItemReportEmail($itemData);
    
        session()->setFlashdata('success', 'Item created successfully.');
        return redirect()->to('/items');
    }

    public function update($id = null)
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Item not found');
        }
    
        $validationRules = [
            'title' => 'required|min_length[3]',
            'description' => 'required|min_length[5]',
            'category' => 'required|min_length[2]',
            'status' => 'required|in_list[active,inactive,claimed,returned,Pending Turning-in,Fake Report]',
            'type' => 'required|in_list[found,lost]',
            'date_lost_found' => 'required|valid_date[Y-m-d]',
            'reporter_email' => 'required|valid_email',
            'reporter_name' => 'permit_empty|min_length[2]',
            'reporter_department' => 'permit_empty|min_length[2]',
            'location' => 'permit_empty|min_length[2]'
        ];
    
        if (!$this->validate($validationRules)) {
            return view('items/edit', [
                'item' => $item,
                'validation' => $this->validator
            ]);
        }
    
        $imageFile = $this->request->getFile('images');
        $imageName = $item['images']; // Keep existing image by default
        
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Delete old image if it exists
            if ($item['images']) {
                $oldImages = json_decode($item['images'], true);
                if ($oldImages && is_array($oldImages)) {
                    foreach ($oldImages as $oldImage) {
                        $oldImagePath = $this->uploadPath . $oldImage;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                }
            }
            
            $imageName = $imageFile->getRandomName();
            $imageFile->move($this->uploadPath, $imageName);
            $imageName = json_encode([$imageName]);
        }
    
        $newStatus = $this->request->getPost('status');
        $oldStatus = $item['status'];
    
        $updateData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'status' => $newStatus,
            'type' => $this->request->getPost('type'),
            'date_lost_found' => $this->request->getPost('date_lost_found'),
            'reporter_email' => $this->request->getPost('reporter_email'),
            'reporter_name' => $this->request->getPost('reporter_name'),
            'reporter_department' => $this->request->getPost('reporter_department'),
            'location' => $this->request->getPost('location'),
            'images' => $imageName
        ];
    
        $this->itemModel->update($id, $updateData);
        
        // Log item update activity
        $this->activityLogger->log(
            'Item Management',
            'item_update',
            'Updated item: ' . $item['title'],
            [
                'severity' => 'medium',
                'entity_type' => 'item',
                'entity_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'category' => $updateData['category'],
                'item_type' => $updateData['type']
            ]
        );
        
        // Check if status changed to Fake Report and notify admins IMMEDIATELY
        if ($oldStatus !== $newStatus && $newStatus === 'Fake Report') {
            log_message('info', "Status changed to Fake Report for item {$id}, notifying admins");
            // Get the updated item from database to ensure we have latest data
            $updatedItem = $this->itemModel->find($id);
            $this->notifyAdminsOfFakeReport($updatedItem, $updatedItem);
        }

        
        // If status changed, update related matches and claims
        if ($oldStatus !== $newStatus) {
            // Log status change activity
            $this->activityLogger->log(
                'Item Management',
                'status_change',
                'Changed status of item "' . $item['title'] . '" from ' . $oldStatus . ' to ' . $newStatus,
                [
                    'severity' => 'high',
                    'entity_type' => 'item',
                    'entity_id' => $id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'item_title' => $item['title']
                ]
            );
            
            $matchModel = new \App\Models\MatchModel();
            $claimModel = new \App\Models\ClaimModel();
            
            // Find related matches
            $matches = $matchModel
                ->where('lost_item_id', $id)
                ->orWhere('found_item_id', $id)
                ->findAll();
            
            foreach ($matches as $match) {
                $matchStatus = 'pending';
                $claimStatus = 'pending';
                
                switch ($newStatus) {
                    case 'claimed':
                        $matchStatus = 'accepted';
                        $claimStatus = 'approved';
                        break;
                    case 'returned':
                        $matchStatus = 'resolved';
                        $claimStatus = 'resolved';
                        break;
                    case 'active':
                        $matchStatus = 'pending';
                        $claimStatus = 'pending';
                        break;
                    case 'inactive':
                        $matchStatus = 'rejected';
                        $claimStatus = 'rejected';
                        break;
                }
                
                // Update match status
                $matchModel->update($match['id'], ['status' => $matchStatus]);
                
                // Update related claims
                $claims = $claimModel->where('match_id', $match['id'])->findAll();
                foreach ($claims as $claim) {
                    $claimModel->update($claim['id'], ['status' => $claimStatus]);
                }
            }
            
            // Log the status change
            log_message('info', "Item {$id} status changed from {$oldStatus} to {$newStatus}. Related matches and claims updated accordingly.");
        }
    
        session()->setFlashdata('success', 'Item updated successfully. Related matches and claims have been synchronized.');
        return redirect()->to('/items');
    }

    /**
     * Flag an item as fake report (accessible by users)
     * 
     * @param string|null $id Item ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function flagAsFake($id = null)
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Item not found');
        }

        // Update item status to Fake Report
        $this->itemModel->update($id, ['status' => 'Fake Report']);

        // Get updated item data with all fields
        $updatedItem = $this->itemModel->find($id);

        // Notify admins - pass the item data directly
        $this->notifyAdminsOfFakeReport($item, $item);

        // Log the action
        $this->activityLogger->log(
            'Item Management',
            'flag_as_fake',
            'User flagged item "' . $item['title'] . '" as fake report',
            [
                'severity' => 'high',
                'entity_type' => 'item',
                'entity_id' => $id,
                'item_title' => $item['title'],
                'reporter_email' => $item['reporter_email'],
                'flagged_by' => session()->get('user_id')
            ]
        );

        session()->setFlashdata('success', 'Item has been flagged as fake report. Admins have been notified.');
        return redirect()->to('/items');
    }

    public function delete($id = null)
    {
        $item = $this->itemModel->find($id);
        if ($item) {
            if ($item['images'] && file_exists($this->uploadPath . $item['images'])) {
                unlink($this->uploadPath . $item['images']);
            }
            $this->itemModel->delete($id);
            
            // Log item deletion activity
            $this->activityLogger->log(
                'Item Management',
                'item_delete',
                'Deleted item: ' . $item['title'],
                [
                    'severity' => 'high',
                    'entity_type' => 'item',
                    'entity_id' => $id,
                    'item_title' => $item['title'],
                    'item_type' => $item['type'],
                    'category' => $item['category'],
                    'status' => $item['status']
                ]
            );
            

            
            session()->setFlashdata('success', 'Item deleted successfully.');
        }
        return redirect()->to('/items');
    }

    public function view($id = null)
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Item not found');
        }
        
        // Get all claims for this item with claimant information
        $claims = $this->claimModel
            ->select('claims.id, claims.item_id, claims.match_id, claims.claimant_id, claims.claim_type, claims.status, claims.proof_of_ownership, claims.image, claims.google_user_id, claims.created_at, claims.updated_at, users.full_name as claimant_name, claims.claimant_id as claimant_email')
            ->join('users', 'users.email = claims.claimant_id', 'left')
            ->where('item_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        // Get related matches for this item
        $matchModel = new \App\Models\MatchModel();
        $matches = $matchModel
            ->where('lost_item_id', $id)
            ->orWhere('found_item_id', $id)
            ->orderBy('score', 'DESC')
            ->findAll();
        
        // Log item view activity
        $this->activityLogger->log(
            'Item Management',
            'item_view',
            'Viewed item details: ' . $item['title'],
            [
                'severity' => 'low',
                'entity_type' => 'item',
                'entity_id' => $id,
                'item_title' => $item['title'],
                'item_type' => $item['type'],
                'category' => $item['category'],
                'status' => $item['status'],
                'claims_count' => count($claims),
                'matches_count' => count($matches)
            ]
        );
        
        return view('items/view', [
            'item' => $item,
            'claims' => $claims,
            'matches' => $matches
        ]);
    }
    
    /**
     * Send automated email notification for item report
     * 
     * @param array $itemData Item data
     * @return void
     */
    private function sendItemReportEmail($itemData)
    {
        try {
            // Get or create user data
            $userData = $this->getUserData($itemData['reporter_email'], $itemData['reporter_name']);
            
            // Prepare item data for email
            $emailItemData = [
                'id' => $itemData['id'],
                'name' => $itemData['title'],
                'description' => $itemData['description'],
                'category' => $itemData['category'],
                'location' => $itemData['location'] ?? 'Not specified',
                'created_at' => date('Y-m-d H:i:s'),
                'date_lost' => $itemData['date_lost_found'],
                'date_found' => $itemData['date_lost_found']
            ];
            
            // Send item reported notification email
            $this->emailService->sendItemReportedNotification($itemData, $userData);
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to send item report email: ' . $e->getMessage());
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
     * Notify all admins when an item is flagged as fake report
     * 
     * @param array $item Original item data
     * @param array $updateData Updated item data
     * @return void
     */
    private function notifyAdminsOfFakeReport($item, $updateData)
    {
        try {
            log_message('info', 'Starting fake report notification process for item ID: ' . $item['id']);
            
            // Get all admins
            $admins = $this->adminRoleModel->findAll();
            
            if (empty($admins)) {
                log_message('warning', 'No admins found to notify about fake report');
                return;
            }
            
            log_message('info', 'Found ' . count($admins) . ' admin(s) to notify');
            
            // Get reporter information
            $reporterName = $updateData['reporter_name'] ?? $item['reporter_name'] ?? 'Unknown';
            $reporterEmail = $updateData['reporter_email'] ?? $item['reporter_email'] ?? 'Unknown';
            $itemTitle = $updateData['title'] ?? $item['title'] ?? 'Unknown Item';
            $itemType = $updateData['type'] ?? $item['type'] ?? 'unknown';
            $itemCategory = $updateData['category'] ?? $item['category'] ?? 'Unknown';
            
            log_message('info', "Fake report details - Item: {$itemTitle}, Reporter: {$reporterEmail}");
            
            // Increment fake report count for the reporter
            if ($reporterEmail !== 'Unknown' && filter_var($reporterEmail, FILTER_VALIDATE_EMAIL)) {
                $reporter = $this->userModel->where('email', $reporterEmail)->first();
                if ($reporter) {
                    $currentCount = $reporter['fake_report_count'] ?? 0;
                    $newCount = $currentCount + 1;
                    $updateResult = $this->userModel->update($reporter['id'], [
                        'fake_report_count' => $newCount
                    ]);
                    if ($updateResult) {
                        log_message('info', "Successfully incremented fake_report_count for user {$reporterEmail} to {$newCount}");
                    } else {
                        log_message('error', "Failed to increment fake_report_count for user {$reporterEmail}");
                    }
                } else {
                    log_message('warning', "Reporter user not found in database: {$reporterEmail}");
                }
            }
            
            // Create notification for each admin - using same pattern as match notifications
            $successCount = 0;
            foreach ($admins as $admin) {
                try {
                    // Create web notification
                    $notificationData = [
                        'user_email' => $admin['email'],
                        'user_name' => $admin['name'] ?? 'Admin',
                        'title' => 'Fake Report Flagged',
                        'message' => sprintf(
                            'Item "%s" (%s) has been flagged as a fake report. Reporter: %s (%s)',
                            $itemTitle,
                            $itemType,
                            $reporterName,
                            $reporterEmail
                        ),
                        'type' => 'item',
                        'entity_type' => 'item',
                        'entity_id' => $item['id'],
                        'priority' => 'high',
                        'action_url' => site_url('items/view/' . $item['id']),
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Insert notification
                    if ($this->notificationModel->insert($notificationData)) {
                        $successCount++;
                        log_message('info', "Fake report notification created for {$admin['email']}");
                    } else {
                        log_message('error', "Failed to insert notification for {$admin['email']}: " . json_encode($this->notificationModel->errors()));
                    }
                } catch (\Exception $e) {
                    log_message('error', "Exception creating notification for admin {$admin['email']}: " . $e->getMessage());
                }
            }
            
            log_message('info', "Fake report notifications sent to {$successCount} of " . count($admins) . " admin(s)");
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to notify admins of fake report: ' . $e->getMessage());
        }
    }
}
