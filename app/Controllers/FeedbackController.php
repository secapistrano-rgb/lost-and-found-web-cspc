<?php

namespace App\Controllers;

use App\Models\FeedbackModel;
use App\Models\FeedbackResponseModel;
use App\Helpers\ActivityLogger;
use App\Libraries\EmailNotificationService;

class FeedbackController extends BaseController
{
    protected $activityLogger;
    protected $notificationService;

    public function __construct()
    {
        $this->activityLogger = ActivityLogger::getInstance();
        $this->notificationService = new EmailNotificationService();
    }
    public function index()
    {
        $model = new FeedbackModel();
        
        $q = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $category = $this->request->getGet('category');
        
        $data['q'] = $q;
        $data['status'] = $status;
        $data['category'] = $category;
        
        // Apply filters
        if ($q) {
            $model->groupStart()
                  ->like('user_name', $q)
                  ->orLike('user_email', $q)
                  ->orLike('text', $q)
                  ->groupEnd();
        }
        
        if ($status) {
            $model->where('status', $status);
        }
        
        if ($category) {
            $model->where('type', $category);
        }
        
        // Use configurable per-page limit (default: 50, but allows up to 100)
        $perPage = (int) $this->request->getGet('per_page') ?: 50;
        $perPage = min($perPage, 100); // Cap at 100 for performance
        
        $feedback = $model->paginate($perPage, 'feedback');
        $pager = $model->pager;
        
        // Add responses to each feedback item
        $responseModel = new FeedbackResponseModel();
        foreach ($feedback as &$item) {
            $item['responses'] = $responseModel->getResponsesByFeedbackId($item['id']);
            $item['has_responses'] = !empty($item['responses']);
        }
        
        $data['feedback'] = $feedback;
        $data['pager'] = $pager;
        $data['per_page'] = $perPage;
        $data['stats'] = $model->getStats();
        
        // Log feedback management access
        $this->activityLogger->log(
            'Feedback Management',
            'view_feedback_list',
            'Viewed feedback management page' . ($status ? ' (filtered by: ' . $status . ')' : ''),
            session('user_id'),
            null,
            null,
            'low'
        );
        
        return view('feedback/index', $data);
    }
    
    public function create()
    {
        return view('feedback/create');
    }
    
    public function store()
    {
        $model = new FeedbackModel();
        
        $data = [
            'user_name'      => $this->request->getPost('user_name'),
            'user_email'     => $this->request->getPost('user_email'),
            'text'           => $this->request->getPost('text'),
            'rating'         => $this->request->getPost('rating'),
            'type'           => $this->request->getPost('type'),
            'user_photo_url' => $this->request->getPost('user_photo_url'),
            'status'         => 'pending'
        ];
        
        if ($model->createFeedback($data)) {
            // Get the created feedback ID
            $feedbackId = $model->getInsertID();
            
            // Log feedback creation activity
            activity_logger()->logFeedbackCreated(
                $feedbackId, 
                $data['user_email'], 
                $data['type'], 
                session()->get('user_id') ?: 'system'
            );
            

            
            return redirect()->to('/feedback')->with('success', 'Feedback submitted successfully!');
        } else {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }
    }
    
    public function view($id)
    {
        $model = new FeedbackModel();
        $data['feedback'] = $model->getFeedbackWithResponses($id);
        
        if (!$data['feedback']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Feedback not found');
        }
        
        return view('feedback/view', $data);
    }
    
    public function respond($id)
    {
        $model = new FeedbackModel();
        $data['feedback'] = $model->getFeedbackWithResponses($id);
        
        if (!$data['feedback']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Feedback not found');
        }
        
        return view('feedback/respond', $data);
    }
    
    public function updateResponse($id)
    {
        $feedbackModel = new FeedbackModel();
        $responseModel = new FeedbackResponseModel();
        
        // Debug logging
        log_message('debug', 'FeedbackController::updateResponse called with ID: ' . $id);
        
        $feedback = $feedbackModel->find($id);
        
        if (!$feedback) {
            log_message('error', 'Feedback not found with ID: ' . $id);
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Feedback not found');
        }
        
        // Get admin info from session or use defaults
        $adminEmail = session()->get('email') ?: 'admin@system.com';
        $adminName = session()->get('name') ?: 'System Admin';
        $responseText = $this->request->getPost('admin_response');
        
        log_message('debug', 'Admin info - Email: ' . $adminEmail . ', Name: ' . $adminName);
        log_message('debug', 'Response text length: ' . strlen($responseText));
        
        if (empty($responseText)) {
            log_message('error', 'Response text is empty');
            return redirect()->back()->withInput()->with('error', 'Response text is required.');
        }
        
        // Create response data
        $responseData = [
            'feedback_id'   => $id,
            'admin_email'   => $adminEmail,
            'admin_name'    => $adminName,
            'response_text' => $responseText
        ];
        
        log_message('debug', 'Response data: ' . json_encode($responseData));
        
        // Set timezone to Philippine Time
        date_default_timezone_set('Asia/Manila');
        
        // Update feedback status
        $feedbackData = [
            'status' => $this->request->getPost('status') ?: 'reviewed',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Try to create response first
        $createResult = $responseModel->createResponse($responseData);
        log_message('debug', 'createResponse result: ' . ($createResult ? 'true' : 'false'));
        
        if (!$createResult) {
            $errors = $responseModel->errors();
            log_message('error', 'Failed to create response. Errors: ' . json_encode($errors));
            log_message('error', 'Validation errors: ' . json_encode($responseModel->getValidationMessages()));
            $errorMessage = !empty($errors) ? implode(', ', $errors) : 'Failed to create response.';
            return redirect()->back()->withInput()->with('error', 'Failed to add response: ' . $errorMessage);
        }
        
        log_message('debug', 'Response created successfully, updating feedback status');
        
        // Update feedback status
        if (!$feedbackModel->update($id, $feedbackData)) {
            log_message('error', 'Failed to update feedback status');
            return redirect()->back()->withInput()->with('error', 'Response added but failed to update feedback status.');
        }
        
        // Log feedback response activity
        activity_logger()->logFeedbackResponseAdded(
            $id, 
            $feedback['user_email'], 
            $adminName, 
            session()->get('user_id') ?: 'system'
        );
        

        
        log_message('debug', 'Feedback response process completed successfully');
        return redirect()->to('/feedback')->with('success', 'Response added successfully!');
    }
    
    public function delete($id)
    {
        log_message('debug', 'FeedbackController::delete called with ID: ' . $id);
        
        $model = new FeedbackModel();
        $feedback = $model->find($id);
        
        if (!$feedback) {
            log_message('error', 'Feedback not found for ID: ' . $id);
            return redirect()->to('/feedback')->with('error', 'Feedback not found.');
        }
        
        log_message('debug', 'Found feedback to delete: ' . json_encode($feedback));
        
        $deleteResult = $model->delete($id);
        log_message('debug', 'Delete result: ' . ($deleteResult ? 'true' : 'false'));
        
        if ($deleteResult) {
            // Log feedback deletion activity
            activity_logger()->logFeedbackDeleted(
                $id, 
                $feedback['user_email'], 
                session()->get('user_id') ?: 'system'
            );
            

            
            log_message('info', 'Feedback deleted successfully: ' . $id);
            return redirect()->to('/feedback')->with('success', 'Feedback deleted successfully.');
        } else {
            $errors = $model->errors();
            log_message('error', 'Failed to delete feedback. Errors: ' . json_encode($errors));
            return redirect()->to('/feedback')->with('error', 'Failed to delete feedback.');
        }
    }
    
    public function updateStatus($id)
    {
        log_message('debug', 'FeedbackController::updateStatus called with ID: ' . $id);
        
        $model = new FeedbackModel();
        $feedback = $model->find($id);
        
        if (!$feedback) {
            log_message('error', 'Feedback not found for ID: ' . $id);
            return redirect()->to('/feedback')->with('error', 'Feedback not found.');
        }
        
        $status = $this->request->getPost('status');
        log_message('debug', 'Received status: ' . $status);
        log_message('debug', 'Current feedback status: ' . ($feedback['status'] ?: 'empty'));
        
        // Set default status if current status is empty
        if (empty($feedback['status'])) {
            $currentStatus = 'pending';
        } else {
            $currentStatus = $feedback['status'];
        }
        
        if (empty($status)) {
            log_message('error', 'Status is empty');
            return redirect()->to('/feedback')->with('error', 'Status is required.');
        }
        
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        log_message('debug', 'Update data: ' . json_encode($data));
        
        if ($model->update($id, $data)) {
            // Log feedback status change activity
            activity_logger()->logFeedbackStatusChanged(
                $id, 
                $feedback['user_email'], 
                $currentStatus, 
                $status, 
                session()->get('user_id') ?: 'system'
            );
            

            
            log_message('info', 'Status updated successfully for feedback ID: ' . $id . ' to status: ' . $status);
            return redirect()->to('/feedback')->with('success', 'Status updated successfully.');
        } else {
            $errors = $model->errors();
            log_message('error', 'Failed to update status. Errors: ' . json_encode($errors));
            return redirect()->to('/feedback')->with('error', 'Failed to update status.');
        }
    }
    
    public function analytics()
    {
        $model = new FeedbackModel();
        
        $data['stats'] = $model->getStats();
        $data['average_rating'] = $model->getAverageRating();
        $data['category_stats'] = $model->getFeedbackByCategory();
        
        // Get monthly feedback count for the last 6 months
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $count = $model->where('DATE_FORMAT(created_at, "%Y-%m")', $month)->countAllResults(false);
            $monthlyStats[] = [
                'month' => date('M Y', strtotime("-$i months")),
                'count' => $count
            ];
        }
        $data['monthly_stats'] = $monthlyStats;
        
        // Get recent feedback with responses
        $data['recent_feedback'] = $model->getFeedbackWithResponses();
        
        return view('feedback/analytics', $data);
    }
}