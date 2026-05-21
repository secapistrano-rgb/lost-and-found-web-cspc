<?php

namespace App\Controllers;

use App\Libraries\EmailNotificationService;
use CodeIgniter\Controller;

class EmailTest extends Controller
{
    private $emailService;

    public function __construct()
    {
        $this->emailService = new EmailNotificationService();
    }

    public function index()
    {
        return view('email_test');
    }

    public function testEmail()
    {
        try {
            // Test basic email configuration
            $config = new \Config\Email();
            $email = \Config\Services::email($config);
            
            $testEmail = 'test@example.com';
            $subject = 'CSPC Lost and Found - Email Test';
            $message = '<h2>Email Configuration Test</h2><p>This is a test email to verify SMTP settings are working correctly.</p><p>Timestamp: ' . date('Y-m-d H:i:s') . '</p>';
            
            $email->setTo($testEmail);
            $email->setSubject($subject);
            $email->setMessage($message);
            
            $result = $email->send();
            
            if ($result) {
                $response = [
                    'status' => 'success',
                    'message' => 'Test email sent successfully!',
                    'config' => [
                        'host' => $config->SMTPHost,
                        'port' => $config->SMTPPort,
                        'user' => $config->SMTPUser,
                        'protocol' => $config->protocol
                    ]
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to send test email',
                    'debug' => $email->printDebugger(['headers']),
                    'config' => [
                        'host' => $config->SMTPHost,
                        'port' => $config->SMTPPort,
                        'user' => $config->SMTPUser,
                        'protocol' => $config->protocol
                    ]
                ];
            }
            
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Exception occurred: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
        
        return $this->response->setJSON($response);
    }

    public function testNotificationService()
    {
        try {
            // Test the EmailNotificationService with sample data
            $claimData = [
                'id' => 999,
                'item_name' => 'Test Item',
                'status' => 'approved',
                'claim_date' => date('Y-m-d H:i:s')
            ];
            
            $userData = [
                'id' => 999,
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com'
            ];
            
            // Test claim approval notification
            $result = $this->emailService->sendClaimApprovalNotification($claimData, $userData, true); // dry run
            
            $response = [
                'status' => 'success',
                'message' => 'EmailNotificationService test completed',
                'result' => $result,
                'test_data' => [
                    'claim' => $claimData,
                    'user' => $userData
                ]
            ];
            
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => 'Exception in EmailNotificationService: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
        
        return $this->response->setJSON($response);
    }
}