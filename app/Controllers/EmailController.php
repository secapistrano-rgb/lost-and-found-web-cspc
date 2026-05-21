<?php

namespace App\Controllers;

use App\Libraries\HardcodedEmailTemplates;
use App\Models\EmailLogModel;
use App\Models\UserModel;
use CodeIgniter\Email\Email;
use Exception;

class EmailController extends BaseController
{
    protected $templateManager;
    protected $emailLogModel;
    protected $userModel;

    public function __construct()
    {
        $this->templateManager = new HardcodedEmailTemplates();
        $this->emailLogModel = new EmailLogModel();
        $this->userModel = new UserModel();
    }

    // Enhanced main email management dashboard
    public function index()
    {
        // Get enhanced statistics
        $total = $this->emailLogModel->countAllResults();
        $sent = $this->emailLogModel->where('status', 'sent')->countAllResults();
        $failed = $this->emailLogModel->where('status', 'failed')->countAllResults();
        $pending = $this->emailLogModel->where('status', 'pending')->countAllResults();

        // Get today's statistics
        $today = date('Y-m-d');
        $todayTotal = $this->emailLogModel->where('DATE(created_at)', $today)->countAllResults();
        $todaySent = $this->emailLogModel->where('DATE(created_at)', $today)->where('status', 'sent')->countAllResults();

        $stats = [
            'total' => $total,
            'sent' => $sent,
            'failed' => $failed,
            'pending' => $pending,
            'today_total' => $todayTotal,
            'today_sent' => $todaySent,
            'success_rate' => $total > 0 ? round(($sent / $total) * 100, 1) : 0,
            'failure_rate' => $total > 0 ? round(($failed / $total) * 100, 1) : 0
        ];

        // Get recent emails with pagination - Fixed approach
        $recentEmails = $this->emailLogModel
            ->orderBy('created_at', 'DESC')
            ->paginate(10, 'emails');
        
        $pager = $this->emailLogModel->pager;

        // Add user names manually to avoid collation issues
        foreach ($recentEmails as &$email) {
            $user = $this->userModel->where('full_name', $email['sent_by'])->first();
            $email['sent_by_name'] = $user ? $user['full_name'] : $email['sent_by'];
        }

        $templates = $this->templateManager->getAllTemplates();
        
        $data = [
            'title' => 'Email Management',
            'stats' => $stats,
            'recent_emails' => $recentEmails,
            'templates' => array_values($templates),
            'active_templates_count' => count($templates),
            'total_templates_count' => count($templates),
            'pager' => $pager
        ];

        return view('emails/index', $data);
    }






    // Email logs
    public function logs()
    {
        // Get search and filter parameters
        $search = $this->request->getGet('search');
        $statusFilter = $this->request->getGet('status');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
    
        // Get statistics (unfiltered)
        $total = $this->emailLogModel->countAllResults();
        $sent = $this->emailLogModel->where('status', 'sent')->countAllResults();
        $failed = $this->emailLogModel->where('status', 'failed')->countAllResults();
        $pending = $this->emailLogModel->where('status', 'pending')->countAllResults();
    
        $stats = [
            'total' => $total,
            'sent' => $sent,
            'failed' => $failed,
            'pending' => $pending
        ];
    
        // Build query with filters
        $builder = $this->emailLogModel->builder();
        
        // Apply search filter
        if ($search) {
            $builder->groupStart()
                ->like('recipient_email', $search)
                ->orLike('recipient_name', $search)
                ->orLike('subject', $search)
                ->orLike('sent_by', $search)
                ->groupEnd();
        }
        
        // Apply status filter
        if ($statusFilter && $statusFilter !== 'all') {
            $builder->where('status', $statusFilter);
        }
        
        // Apply date range filter
        if ($dateFrom) {
            $builder->where('DATE(created_at) >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('DATE(created_at) <=', $dateTo);
        }
        
        // Get filtered logs
        $logs = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
        
        // Add user names to each log entry
        foreach ($logs as &$log) {
            if ($log['sent_by']) {
                $user = $this->userModel->where('full_name', $log['sent_by'])->first();
                $log['sent_by_name'] = $user ? $user['full_name'] : $log['sent_by'];
            } else {
                $log['sent_by_name'] = 'System';
            }
        }
    
        $data = [
            'title' => 'Email Logs',
            'logs' => $logs,
            'stats' => $stats,
            'filters' => [
                'search' => $search,
                'status' => $statusFilter,
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ]
        ];
    
        return view('emails/logs', $data);
    }

    // Enhanced get log details with more information
    public function getLogDetails($id)
    {
        try {
            // Get log details without problematic JOINs
            $log = $this->emailLogModel->find($id);

            if (!$log) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email log not found'
                ]);
            }

            // Get user name separately to avoid collation issues
            $user = $this->userModel->where('full_name', $log['sent_by'])->first();
            $sentByName = $user ? $user['full_name'] : $log['sent_by'];

            // Get template name from template_type
            $templateName = null;
            if (!empty($log['template_type'])) {
                $template = $this->templateManager->getTemplate($log['template_type']);
                $templateName = $template ? $template['name'] : null;
            }

            return $this->response->setJSON([
                'success' => true,
                'log' => [
                    'id' => $log['id'],
                    'recipient_email' => $log['recipient_email'],
                    'recipient_name' => $log['recipient_name'],
                    'subject' => $log['subject'],
                    'message_content' => $log['body'],
                    'status' => $log['status'],
                    'sent_by_name' => $sentByName,
                    'template_name' => $templateName,
                    'created_at' => $log['created_at'],
                    'sent_at' => $log['sent_at'],
                    'error_message' => $log['error_message'],
                    'attempts' => $log['attempts'] ?? 1
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading email details: ' . $e->getMessage()
            ]);
        }
    }

    // View email details
    public function view($id = null)
    {
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Email ID is required');
        }

        $email = $this->emailLogModel->find($id);
        
        if (!$email) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Email not found');
        }

        // Get user name separately to avoid collation issues
        $user = $this->userModel->where('full_name', $email['sent_by'])->first();
        $email['sent_by_name'] = $user ? $user['full_name'] : $email['sent_by'];

        // Get template name if template was used
        if (!empty($email['template_type'])) {
            $template = $this->templateManager->getTemplate($email['template_type']);
            $email['template_name'] = $template ? $template['name'] : null;
        } else {
            $email['template_name'] = null;
        }

        $data = [
            'title' => 'Email Details',
            'email' => $email
        ];

        return view('emails/view', $data);
    }

    // Delete email
    public function delete($id = null)
    {
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email ID is required'
            ]);
        }

        try {
            // Check if email exists
            $email = $this->emailLogModel->find($id);
            
            if (!$email) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email not found'
                ]);
            }

            // Delete the email
            $deleted = $this->emailLogModel->delete($id);
            
            if ($deleted) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Email deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete email'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting email: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the email'
            ]);
        }
    }
}