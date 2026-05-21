<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedbackModel extends Model
{
    protected $table = 'feedback';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_email', 
        'type', 
        'rating', 
        'text', 
        'status', 
        'user_name', 
        'user_photo_url',
        'created_at', 
        'updated_at'
    ];
    
    /**
     * Get feedback with responses
     */
    public function getFeedbackWithResponses($id = null)
    {
        $responseModel = new \App\Models\FeedbackResponseModel();
        
        if ($id) {
            $feedback = $this->find($id);
            if ($feedback) {
                $feedback['responses'] = $responseModel->getResponsesByFeedbackId($id);
                $feedback['has_responses'] = !empty($feedback['responses']);
            }
            return $feedback;
        }
        
        $feedbacks = $this->findAll();
        foreach ($feedbacks as &$feedback) {
            $feedback['responses'] = $responseModel->getResponsesByFeedbackId($feedback['id']);
            $feedback['has_responses'] = !empty($feedback['responses']);
        }
        
        return $feedbacks;
    }
    
    /**
     * Get statistics for feedback
     */
    public function getStats()
    {
        $total = $this->countAllResults(false);
        $pending = $this->where('status', 'pending')->countAllResults(false);
        $addressed = $this->where('status', 'addressed')->countAllResults(false);
        $resolved = $this->where('status', 'resolved')->countAllResults(false);
        
        return [
            'total' => $total,
            'pending' => $pending,
            'responded' => $addressed,
            'resolved' => $resolved
        ];
    }
    
    /**
     * Get average rating
     */
    public function getAverageRating()
    {
        $result = $this->selectAvg('rating')->where('rating IS NOT NULL')->first();
        return $result ? round($result['rating'], 2) : 0;
    }
    
    /**
     * Get feedback count by type
     */
    public function getFeedbackByCategory()
    {
        return $this->select('type, COUNT(*) as count')
                   ->groupBy('type')
                   ->findAll();
    }
    
    /**
     * Create feedback with auto-generated ID
     */
    public function createFeedback($data)
    {
        // Set timezone to Philippine Time
        date_default_timezone_set('Asia/Manila');
        
        // Generate unique ID for the feedback
        $data['id'] = 'feedback_' . time() . '_' . uniqid();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->save($data);
    }
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'user_email' => 'required|valid_email|max_length[255]',
        'type'       => 'required|in_list[general,bug_report,feature_request,complaint,suggestion]',
        'rating'     => 'permit_empty|integer|greater_than[0]|less_than[6]',
        'text'       => 'required|min_length[10]',
        'user_name'  => 'permit_empty|max_length[255]',
        'user_photo_url' => 'permit_empty|valid_url'
    ];
    
    protected $validationMessages = [
        'user_email' => [
            'required'    => 'Email is required',
            'valid_email' => 'Please provide a valid email address',
            'max_length'  => 'Email cannot exceed 255 characters'
        ],
        'type' => [
            'required' => 'Feedback type is required',
            'in_list'  => 'Please select a valid feedback type'
        ],
        'text' => [
            'required'   => 'Feedback text is required',
            'min_length' => 'Feedback must be at least 10 characters long'
        ],
        'rating' => [
            'integer'      => 'Rating must be a number',
            'greater_than' => 'Rating must be between 1 and 5',
            'less_than'    => 'Rating must be between 1 and 5'
        ],
        'user_name' => [
            'max_length' => 'Name cannot exceed 255 characters'
        ],
        'user_photo_url' => [
            'valid_url' => 'Please provide a valid photo URL'
        ]
    ];
    

}