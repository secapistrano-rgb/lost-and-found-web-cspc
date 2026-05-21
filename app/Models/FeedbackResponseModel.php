<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedbackResponseModel extends Model
{
    protected $table = 'feedback_responses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false; // ID is manually generated
    protected $returnType = 'array';
    protected $allowedFields = [
        'id',
        'feedback_id',
        'admin_email',
        'admin_name',
        'response_text',
        'created_at'
    ];
    protected $useTimestamps = false; // Using manual timestamp
    
    protected $validationRules = [
        'feedback_id'   => 'required|max_length[255]',
        'admin_email'   => 'required|valid_email|max_length[255]',
        'admin_name'    => 'permit_empty|max_length[255]',
        'response_text' => 'required|min_length[1]'
    ];
    
    protected $validationMessages = [
        'feedback_id' => [
            'required'   => 'Feedback ID is required',
            'max_length' => 'Feedback ID cannot exceed 255 characters'
        ],
        'admin_email' => [
            'required'    => 'Admin email is required',
            'valid_email' => 'Please provide a valid admin email address',
            'max_length'  => 'Admin email cannot exceed 255 characters'
        ],
        'admin_name' => [
            'max_length' => 'Admin name cannot exceed 255 characters'
        ],
        'response_text' => [
            'required'   => 'Response text is required',
            'min_length' => 'Response text is required'
        ]
    ];
    
    /**
     * Get responses for a specific feedback
     */
    public function getResponsesByFeedbackId($feedbackId)
    {
        return $this->where('feedback_id', $feedbackId)
                   ->orderBy('created_at', 'ASC')
                   ->findAll();
    }
    
    /**
     * Get the latest response for a feedback
     */
    public function getLatestResponseByFeedbackId($feedbackId)
    {
        return $this->where('feedback_id', $feedbackId)
                   ->orderBy('created_at', 'DESC')
                   ->first();
    }
    
    /**
     * Check if feedback has any responses
     */
    public function hasResponses($feedbackId)
    {
        return $this->where('feedback_id', $feedbackId)->countAllResults() > 0;
    }
    
    /**
     * Create a new response with auto-generated ID
     */
    public function createResponse($data)
    {
        // Set timezone to Philippine Time
        date_default_timezone_set('Asia/Manila');
        
        // Generate unique ID for the response
        $data['id'] = 'response_' . time() . '_' . uniqid();
        $data['created_at'] = date('Y-m-d H:i:s');
        
        log_message('debug', 'FeedbackResponseModel::createResponse - Data to save: ' . json_encode($data));
        
        // Validate data before saving
        if (!$this->validate($data)) {
            $validationErrors = $this->getValidationMessages();
            log_message('error', 'FeedbackResponseModel validation failed: ' . json_encode($validationErrors));
            return false;
        }
        
        // Log database connection info
        $db = $this->db();
        log_message('debug', 'Database connection active: ' . ($db->connID ? 'true' : 'false'));
        log_message('debug', 'Database name: ' . $db->getDatabase());
        
        // Start explicit transaction
        $db->transStart();
        log_message('debug', 'Transaction started');
        
        $result = $this->save($data);
        log_message('debug', 'FeedbackResponseModel::save result: ' . ($result ? 'true' : 'false'));
        
        if (!$result) {
            log_message('error', 'FeedbackResponseModel::save failed. Errors: ' . json_encode($this->errors()));
            $db->transRollback();
            log_message('debug', 'Transaction rolled back due to save failure');
        } else {
            // Complete the transaction
            $db->transComplete();
            log_message('debug', 'Transaction completed');
            
            // Check if transaction was successful
            if ($db->transStatus() === false) {
                log_message('error', 'CRITICAL: Transaction failed!');
                return false;
            }
            
            // Verify the record was actually inserted
            $insertedId = $data['id'];
            $verifyQuery = $db->query("SELECT COUNT(*) as count FROM feedback_responses WHERE id = ?", [$insertedId]);
            $count = $verifyQuery->getRow()->count;
            log_message('debug', 'Verification: Record with ID ' . $insertedId . ' exists in DB: ' . ($count > 0 ? 'true' : 'false'));
            
            if ($count == 0) {
                log_message('error', 'CRITICAL: Save returned true but record not found in database!');
                // Check if there are any database errors
                $error = $db->error();
                log_message('error', 'Database error info: ' . json_encode($error));
                return false;
            }
        }
        
        return $result;
    }
}