<?php

namespace App\Controllers;

use App\Models\EmailThreadModel;
use App\Models\NotificationModel;
use App\Models\AdminRoleModel;

class EmailWebhookController extends BaseController
{
    protected $emailThreadModel;
    protected $notificationModel;
    protected $adminRoleModel;

    public function __construct()
    {
        $this->emailThreadModel = new EmailThreadModel();
        $this->notificationModel = new NotificationModel();
        $this->adminRoleModel = new AdminRoleModel();
    }

    /**
     * Webhook endpoint for receiving email reply notifications
     * This can be called by email services like SendGrid, Mailgun, etc.
     * Or by a custom IMAP checker script
     */
    public function receiveReply()
    {
        // Verify webhook authenticity
        $webhookSecret = getenv('EMAIL_WEBHOOK_SECRET') ?: 'your-secret-key';
        $providedSecret = $this->request->getHeaderLine('X-Webhook-Secret');
        
        if ($providedSecret !== $webhookSecret) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        try {
            $data = $this->request->getJSON(true);
            
            // Extract email details
            $fromEmail = $data['from'] ?? '';
            $toEmail = $data['to'] ?? '';
            $subject = $data['subject'] ?? '';
            $replyToMessageId = $data['in_reply_to'] ?? '';
            $emailBody = $data['body'] ?? '';
            
            log_message('info', "Email reply received from: {$fromEmail}");
            
            // Find the original thread
            $thread = $this->emailThreadModel->where('message_id', $replyToMessageId)->first();
            
            if (!$thread) {
                // Try to find by recipient email
                $thread = $this->emailThreadModel
                    ->where('to_email', $fromEmail)
                    ->orderBy('created_at', 'DESC')
                    ->first();
            }
            
            if ($thread) {
                // Mark thread as replied
                $this->emailThreadModel->markAsReplied($replyToMessageId ?: $thread['message_id']);
                
                // Notify all admins
                $this->notifyAdminsOfReply($thread, $fromEmail, $subject, $emailBody);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Reply processed and admins notified'
                ]);
            } else {
                log_message('warning', "No thread found for reply from: {$fromEmail}");
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Reply received but no matching thread found'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Email webhook error: ' . $e->getMessage());
            
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Internal server error'
            ]);
        }
    }

    /**
     * Notify all admins when a user replies to an email
     */
    private function notifyAdminsOfReply($thread, $fromEmail, $subject, $emailBody)
    {
        try {
            // Get all admins
            $admins = $this->adminRoleModel->findAll();
            
            if (empty($admins)) {
                log_message('warning', 'No admins found to notify about email reply');
                return;
            }
            
            // Create Gmail search URL to find the conversation
            $gmailSearchUrl = 'https://mail.google.com/mail/u/0/#search/' . urlencode('from:' . $fromEmail);
            
            // Get preview of email body
            $bodyPreview = substr(strip_tags($emailBody), 0, 150);
            if (strlen($emailBody) > 150) {
                $bodyPreview .= '...';
            }
            
            // Create notification for each admin
            $successCount = 0;
            foreach ($admins as $admin) {
                try {
                    $notificationData = [
                        'user_email' => $admin['email'],
                        'user_name' => $admin['name'] ?? 'Admin',
                        'title' => '📧 User Replied to Email',
                        'message' => sprintf(
                            "User %s replied to: \"%s\"\n\n" .
                            "Preview: %s\n\n" .
                            "Click to view and reply in Gmail.",
                            $fromEmail,
                            $thread['subject'],
                            $bodyPreview
                        ),
                        'type' => 'system',
                        'entity_type' => $thread['entity_type'] ?? 'email',
                        'entity_id' => $thread['entity_id'] ?? null,
                        'priority' => 'high',
                        'action_url' => $gmailSearchUrl,
                        'is_read' => 0,
                        'metadata' => json_encode([
                            'reply_from' => $fromEmail,
                            'original_subject' => $thread['subject'],
                            'thread_id' => $thread['thread_id'] ?? null,
                            'reply_subject' => $subject,
                            'entity_type' => $thread['entity_type'] ?? null,
                            'entity_id' => $thread['entity_id'] ?? null,
                        ]),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    if ($this->notificationModel->insert($notificationData)) {
                        $successCount++;
                        log_message('info', "Email reply notification created for {$admin['email']}");
                    }
                } catch (\Exception $e) {
                    log_message('error', "Failed to create notification for {$admin['email']}: " . $e->getMessage());
                }
            }
            
            log_message('info', "Email reply notifications sent to {$successCount} of " . count($admins) . " admin(s)");
            
        } catch (\Exception $e) {
            log_message('error', 'Failed to notify admins of email reply: ' . $e->getMessage());
        }
    }

    /**
     * Manual trigger endpoint for testing
     * Remove in production or add authentication
     */
    public function testReply()
    {
        $testData = [
            'from' => 'secapistrano@my.cspc.edu.ph',
            'to' => 'lostfoundcspc@gmail.com',
            'subject' => 'Re: Claim Resolved - Lost Accessories',
            'in_reply_to' => '',
            'body' => 'That\'s great, thank you very much.',
        ];
        
        // Set JSON request
        $_SERVER['CONTENT_TYPE'] = 'application/json';
        $_SERVER['HTTP_X_WEBHOOK_SECRET'] = getenv('EMAIL_WEBHOOK_SECRET') ?: 'your-secret-key';
        
        return $this->receiveReply();
    }
}