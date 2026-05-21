<?php

namespace App\Models;

use CodeIgniter\Model;

class MatchModel extends Model
{
    protected $table = 'matches';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'status',
        'score',
        'lost_item_id',
        'lost_item_title',
        'lost_item_description',
        'lost_item_category',
        'lost_item_location',
        'lost_item_latitude',
        'lost_item_longitude',
        'lost_item_date_reported',
        'lost_item_date_lost_found',
        'lost_item_reporter_name',
        'lost_item_reporter_email',
        'lost_item_reporter_department',
        'lost_item_images',
        'lost_item_detected_objects',
        'lost_item_detected_categories',
        'found_item_id',
        'found_item_title',
        'found_item_description',
        'found_item_category',
        'found_item_location',
        'found_item_latitude',
        'found_item_longitude',
        'found_item_date_reported',
        'found_item_date_lost_found',
        'found_item_reporter_name',
        'found_item_reporter_email',
        'found_item_reporter_department',
        'found_item_images',
        'found_item_detected_objects',
        'found_item_detected_categories'
    ];

    protected $validationRules = [
        'status' => 'required|in_list[pending,accepted,rejected,resolved]',
        'score' => 'required|decimal',
        'lost_item_id' => 'required',
        'found_item_id' => 'required'
    ];

    protected $validationMessages = [
        'status' => [
            'required' => 'Status is required.',
            'in_list' => 'Status must be one of: pending, accepted, rejected, resolved.'
        ],
        'score' => [
            'required' => 'Match score is required.',
            'decimal' => 'Match score must be a valid decimal number.'
        ],
        'lost_item_id' => [
            'required' => 'Lost item ID is required.'
        ],
        'found_item_id' => [
            'required' => 'Found item ID is required.'
        ]
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Enable callbacks
    protected $allowCallbacks = true;
    protected $afterInsert = ['notifyAdminsAfterInsert'];

    /**
     * Callback after insert - Notify admins and staff about new match
     * 
     * @param array $data
     * @return array
     */
    protected function notifyAdminsAfterInsert(array $data)
    {
        // Only proceed if insert was successful
        if (!isset($data['id']) || !isset($data['data'])) {
            return $data;
        }

        try {
            // Load notification service
            $notificationService = new \App\Libraries\EmailNotificationService();
            
            // Prepare match data for notification
            $matchData = array_merge(['id' => $data['id']], $data['data']);
            
            // Send notifications to system_admin, admin, and staff
            $notificationService->createMatchCreatedNotification($matchData);
            
            log_message('info', "Match notification triggered for match ID: {$data['id']}");
        } catch (\Exception $e) {
            // Log error but don't fail the insert operation
            log_message('error', "Failed to send match notification: " . $e->getMessage());
        }

        return $data;
    }

    /**
     * Get matches with pagination and filtering
     */
    public function getMatches($limit = 20, $offset = 0, $status = null)
    {
        $builder = $this->orderBy('created_at', 'DESC');
        
        if ($status) {
            $builder->where('status', $status);
        }
        
        return $builder->findAll($limit, $offset);
    }

    /**
     * Get match statistics
     */
    public function getMatchStats()
    {
        return [
            'total' => $this->countAll(),
            'pending' => $this->where('status', 'pending')->countAllResults(false),
            'accepted' => $this->where('status', 'accepted')->countAllResults(false),
            'rejected' => $this->where('status', 'rejected')->countAllResults(false),
            'resolved' => $this->where('status', 'resolved')->countAllResults(false)
        ];
    }

    /**
     * Update match status
     */
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Get matches by status
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Search matches
     */
    public function searchMatches($query)
    {
        return $this->groupStart()
                   ->like('lost_item_title', $query)
                   ->orLike('found_item_title', $query)
                   ->orLike('lost_item_description', $query)
                   ->orLike('found_item_description', $query)
                   ->orLike('lost_item_category', $query)
                   ->orLike('found_item_category', $query)
                   ->groupEnd()
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }
}