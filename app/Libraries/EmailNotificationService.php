<?php

namespace App\Libraries;

use App\Models\EmailLogModel;
use App\Models\EmailThreadModel;
use CodeIgniter\Email\Email;
use App\Libraries\HardcodedEmailTemplates;
use Exception;

class EmailNotificationService
{
    protected $emailLogModel;
    protected $emailThreadModel;
    protected $email;
    protected $templateManager;

    public function __construct()
    {
        $this->emailLogModel = new EmailLogModel();
        $this->emailThreadModel = new EmailThreadModel();
        // Initialize email service with proper configuration
        $config = new \Config\Email();
        $this->email = \Config\Services::email($config);
        $this->templateManager = new HardcodedEmailTemplates();
    }

    /**
     * Send automated email using template
     * 
     * @param string $templateType Template type (lost_item_confirmation, found_item_confirmation, etc.)
     * @param string $recipientEmail Recipient email address
     * @param string $recipientName Recipient name
     * @param array $placeholders Array of placeholder values
     * @param string $sentBy Who is sending the email
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendTemplatedEmail($templateType, $recipientEmail, $recipientName, $placeholders = [], $sentBy = 'System', $dryRun = false)
    {
        try {
            // Get template by type
            $template = $this->templateManager->getTemplate($templateType);
            
            if (!$template) {
                log_message('error', "Email template not found: {$templateType}");
                return false;
            }
            
            // Render template with data
            $rendered = $this->templateManager->renderTemplate($templateType, $placeholders);
            
            if (!$rendered) {
                log_message('error', "Failed to render email template: {$templateType}");
                return false;
            }
            
            $subject = $rendered['subject'];
            $body = $rendered['body'];
            
            // Log email attempt
            $logData = [
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'subject' => $subject,
                'body' => $body,
                'template_type' => $templateType,
                'status' => 'pending',
                'sent_by' => $sentBy
            ];
            
            $logId = $this->emailLogModel->insert($logData);
            
            if ($dryRun) {
                // For testing, just return true without sending
                log_message('info', 'DRY RUN: Would send email to ' . $recipientEmail . ' with subject: ' . $subject);
                $this->emailLogModel->update($logId, [
                    'status' => 'sent',
                    'sent_at' => date('Y-m-d H:i:s')
                ]);
                return true;
            }
            
            // Generate unique Message-ID for reply tracking
            $messageId = uniqid('lf_') . '.' . time() . '@lostfound.systems';
            
            // Send email with tracking headers
            $this->email->setTo($recipientEmail, $recipientName);
            $this->email->setSubject($subject);
            $this->email->setMessage($body);
            $this->email->setHeader('Message-ID', '<' . $messageId . '>');
            $this->email->setReplyTo(getenv('GMAIL_EMAIL') ?: 'lostfoundcspc@gmail.com');
            
            if ($this->email->send()) {
                // Update log as sent
                $this->emailLogModel->update($logId, [
                    'status' => 'sent',
                    'sent_at' => date('Y-m-d H:i:s')
                ]);
                
                // Track email for reply detection
                try {
                    $this->emailThreadModel->trackEmail([
                        'message_id' => $messageId,
                        'to_email' => $recipientEmail,
                        'from_email' => getenv('GMAIL_EMAIL') ?: 'lostfoundcspc@gmail.com',
                        'subject' => $subject,
                        'entity_type' => $templateType,
                        'entity_id' => $placeholders['item_id'] ?? $placeholders['claim_id'] ?? null
                    ]);
                    log_message('info', "Email tracked for reply detection: {$messageId}");
                } catch (\Exception $e) {
                    log_message('error', "Failed to track email: " . $e->getMessage());
                }
                
                log_message('info', "Email sent successfully to {$recipientEmail} using template {$templateType}");
                return true;
            } else {
                // Update log as failed
                $this->emailLogModel->update($logId, [
                    'status' => 'failed',
                    'error_message' => $this->email->printDebugger(['headers'])
                ]);
                
                log_message('error', "Failed to send email to {$recipientEmail}: " . $this->email->printDebugger(['headers']));
                return false;
            }
            
        } catch (Exception $e) {
            log_message('error', "Email service error: " . $e->getMessage());
            
            // Update log if logId exists
            if (isset($logId)) {
                $this->emailLogModel->update($logId, [
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
            }
            
            return false;
        }
    }

    /**
     * Replace placeholders in text
     * 
     * @param string $text Text with placeholders
     * @param array $itemData Item data (for backward compatibility)
     * @param array $userData User data (for backward compatibility)
     * @return string Text with replaced placeholders
     */
    public function replacePlaceholders($text, $itemData = [], $userData = [])
    {
        // Merge user and item data for template manager
        $data = array_merge($userData, $itemData);
        
        return $this->templateManager->replacePlaceholders($text, $data);
    }

    /**
     * Send lost item confirmation email
     * 
     * @param array $itemData Item data
     * @param array $userData User data
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendLostItemConfirmation($itemData, $userData, $dryRun = false)
    {
        $placeholders = [
            'user_name' => $userData['full_name'] ?? $userData['email'],
            'item_name' => $itemData['name'],
            'item_description' => $itemData['description'],
            'item_category' => $itemData['category'],
            'date_lost' => $itemData['date_lost'] ?? $itemData['created_at'],
            'location' => $itemData['location'] ?? 'Not specified',
            'item_id' => $itemData['id']
        ];
        
        return $this->sendTemplatedEmail(
            'lost_item_confirmation',
            $userData['email'],
            $userData['full_name'] ?? $userData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }

    /**
     * Send found item confirmation email
     * 
     * @param array $itemData Item data
     * @param array $userData User data
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendFoundItemConfirmation($itemData, $userData, $dryRun = false)
    {
        $placeholders = [
            'user_name' => $userData['full_name'] ?? $userData['email'],
            'item_name' => $itemData['name'],
            'item_description' => $itemData['description'],
            'item_category' => $itemData['category'],
            'date_found' => $itemData['date_found'] ?? $itemData['created_at'],
            'location' => $itemData['location'] ?? 'Not specified',
            'item_id' => $itemData['id']
        ];
        
        return $this->sendTemplatedEmail(
            'found_item_confirmation',
            $userData['email'],
            $userData['full_name'] ?? $userData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }

    /**
     * Send claim approved notification
     * 
     * @param array $claimData Claim data
     * @param array $itemData Item data
     * @param array $userData User data
     * @param array $finderData Finder data
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendClaimApproved($claimData, $itemData, $userData, $finderData, $dryRun = false)
    {
        $placeholders = [
            'user_name' => $userData['full_name'] ?? $userData['email'],
            'item_name' => $itemData['name'],
            'item_description' => $itemData['description'],
            'claim_id' => $claimData['id'],
            'approval_date' => date('Y-m-d H:i:s'),
            'finder_name' => $finderData['full_name'] ?? $finderData['email'],
            'finder_email' => $finderData['email']
        ];
        
        return $this->sendTemplatedEmail(
            'claim_approved',
            $userData['email'],
            $userData['full_name'] ?? $userData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }

    /**
     * Send claim rejected notification
     * 
     * @param array $claimData Claim data
     * @param array $itemData Item data
     * @param array $userData User data
     * @param string $rejectionReason Reason for rejection
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendClaimRejected($claimData, $itemData, $userData, $rejectionReason, $dryRun = false)
    {
        $placeholders = [
            'user_name' => $userData['full_name'] ?? $userData['email'],
            'item_name' => $itemData['name'],
            'item_description' => $itemData['description'],
            'claim_id' => $claimData['id'],
            'rejection_date' => date('Y-m-d H:i:s'),
            'rejection_reason' => $rejectionReason
        ];
        
        return $this->sendTemplatedEmail(
            'claim_rejected',
            $userData['email'],
            $userData['full_name'] ?? $userData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }
    
    /**
     * Send finder notification when claim is approved
     * 
     * @param array $itemData Item data
     * @param array $finderData Finder data
     * @param array $claimData Claim data
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendFinderNotificationApproved($itemData, $finderData, $claimData, $dryRun = false)
    {
        $placeholders = [
            'finder_name' => $finderData['full_name'] ?? $finderData['email'],
            'item_name' => $itemData['name'],
            'date_claimed' => $claimData['updated_at'] ?? date('Y-m-d H:i:s')
        ];
        
        return $this->sendTemplatedEmail(
            'finder_notification_approved',
            $finderData['email'],
            $finderData['full_name'] ?? $finderData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }
    

    
    /**
     * Send finder notification when item is returned/resolved
     * 
     * @param array $itemData Item data
     * @param array $finderData Finder data
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendFinderNotificationResolved($itemData, $finderData, $dryRun = false)
    {
        $placeholders = [
            'finder_name' => $finderData['full_name'] ?? $finderData['email'],
            'item_name' => $itemData['name'],
            'date_returned' => date('Y-m-d H:i:s')
        ];
        
        return $this->sendTemplatedEmail(
            'finder_notification_resolved',
            $finderData['email'],
            $finderData['full_name'] ?? $finderData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }
    
    /**
     * Send claim approval notification email
     * 
     * @param array $claimData Claim data
     * @param array $userData User data
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendClaimApprovalNotification($claimData, $userData, $dryRun = false)
    {
        $template = $this->templateManager->getTemplate('claim_approved');
        if (!$template) {
            log_message('error', 'Claim approval template not found');
            return false;
        }
        
        $placeholders = [
            'user_name' => $userData['full_name'],
            'claimant_name' => $claimData['claimant_name'],
            'item_name' => $claimData['item_name'],
            'item_category' => $claimData['item_category'],
            'item_location' => $claimData['item_location'],
            'claim_description' => $claimData['claim_description'],
            'date_approved' => $claimData['updated_at'],
            'match_id' => $claimData['id']
        ];
        
        return $this->sendTemplatedEmail(
            'claim_approved',
            $userData['email'],
            $userData['full_name'] ?? $userData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }
    
    /**
     * Send claim rejection notification email
     * 
     * @param array $claimData Claim data
     * @param array $userData User data
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendClaimRejectionNotification($claimData, $userData, $dryRun = false)
    {
        $template = $this->templateManager->getTemplate('claim_rejected');
        if (!$template) {
            log_message('error', 'Claim rejection template not found');
            return false;
        }
        
        $placeholders = [
            'user_name' => $userData['full_name'],
            'claimant_name' => $claimData['claimant_name'],
            'item_name' => $claimData['item_name'],
            'item_category' => $claimData['item_category'],
            'item_location' => $claimData['item_location'],
            'claim_description' => $claimData['claim_description'],
            'rejection_date' => $claimData['updated_at'],
            'claim_id' => $claimData['id']
        ];
        
        return $this->sendTemplatedEmail(
            'claim_rejected',
            $userData['email'],
            $userData['full_name'] ?? $userData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }
    
    /**
     * Send match found notification email
     * 
     * @param array $matchData Match data
     * @param array $userData User data
     * @param string $userType Type of user ('lost' or 'found')
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendMatchFoundNotification($matchData, $userData, $userType = 'lost', $dryRun = false)
    {
        $template = $this->templateManager->getTemplate('match_found');
        if (!$template) {
            log_message('error', 'Match found template not found');
            return false;
        }
        
        $placeholders = [
            'user_name' => $userData['full_name'],
            'match_score' => $matchData['score'],
            'lost_item_title' => $matchData['lost_item']['title'],
            'lost_item_description' => $matchData['lost_item']['description'],
            'lost_item_category' => $matchData['lost_item']['category'],
            'lost_item_location' => $matchData['lost_item']['location'],
            'lost_item_date' => $matchData['lost_item']['date_lost'],
            'lost_reporter_name' => $matchData['lost_item']['reporter_name'],
            'found_item_title' => $matchData['found_item']['title'],
            'found_item_description' => $matchData['found_item']['description'],
            'found_item_category' => $matchData['found_item']['category'],
            'found_item_location' => $matchData['found_item']['location'],
            'found_item_date' => $matchData['found_item']['date_found'],
            'found_reporter_name' => $matchData['found_item']['reporter_name'],
            'match_date' => $matchData['created_at'],
            'match_id' => $matchData['id'],
            'user_type' => $userType
        ];
        
        return $this->sendTemplatedEmail(
            'match_found',
            $userData['email'],
            $userData['full_name'] ?? $userData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }

    /**
     * Send match notification
     * 
     * @param array $matchData Match data
     * @param array $userItemData User's item data
     * @param array $matchItemData Matched item data
     * @param array $userData User data
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendMatchNotification($matchData, $userItemData, $matchItemData, $userData, $dryRun = false)
    {
        $placeholders = [
            'user_name' => $userData['full_name'] ?? $userData['email'],
            'item_type' => $userItemData['type'],
            'your_item_name' => $userItemData['name'],
            'your_item_description' => $userItemData['description'],
            'your_item_id' => $userItemData['id'],
            'match_item_name' => $matchItemData['name'],
            'match_item_description' => $matchItemData['description'],
            'match_score' => $matchData['match_score'] ?? '95',
            'match_action' => $matchItemData['type'] === 'lost' ? 'Lost' : 'Found',
            'match_date' => $matchItemData['created_at'],
            'claim_url' => site_url('claims/create?match_id=' . $matchData['id'])
        ];
        
        return $this->sendTemplatedEmail(
            'match_notification',
            $userData['email'],
            $userData['full_name'] ?? $userData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }
    
    /**
     * Send match status update notification email
     * 
     * @param array $matchData Match data with status information
     * @param array $userData User data
     * @param string $userType Type of user ('lost' or 'found')
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendMatchStatusNotification($matchData, $userData, $userType = 'lost', $dryRun = false)
    {
        // Determine template type based on status
        $templateType = 'match_found'; // Default template
        
        switch ($matchData['status']) {
            case 'accepted':
                $templateType = 'match_found';
                break;
            case 'rejected':
                $templateType = 'match_found'; // Use same template but with different content
                break;
            case 'resolved':
                $templateType = 'match_found';
                break;
        }
        
        $template = $this->templateManager->getTemplate($templateType);
        if (!$template) {
            log_message('error', 'Match status template not found: ' . $templateType);
            return false;
        }
        
        // Create status-specific subject and content
        $statusText = ucfirst($matchData['status']);
        $subject = "Match Status Update - {$statusText}: {$matchData['lost_item']['title']}";
        
        // Create custom body based on status
        $statusMessage = '';
        switch ($matchData['status']) {
            case 'accepted':
                $statusMessage = 'Great news! Your match has been accepted and approved.';
                break;
            case 'rejected':
                $statusMessage = 'Unfortunately, your match has been rejected.';
                break;
            case 'resolved':
                $statusMessage = 'Your match has been resolved and the item has been returned.';
                break;
        }
        
        $placeholders = [
            'user_name' => $userData['full_name'],
            'match_status' => $statusText,
            'status_message' => $statusMessage,
            'match_score' => $matchData['score'],
            'lost_item_title' => $matchData['lost_item']['title'],
            'lost_item_description' => $matchData['lost_item']['description'],
            'lost_item_category' => $matchData['lost_item']['category'],
            'lost_item_location' => $matchData['lost_item']['location'],
            'lost_item_date' => $matchData['lost_item']['date_lost'],
            'lost_reporter_name' => $matchData['lost_item']['reporter_name'],
            'found_item_title' => $matchData['found_item']['title'],
            'found_item_description' => $matchData['found_item']['description'],
            'found_item_category' => $matchData['found_item']['category'],
            'found_item_location' => $matchData['found_item']['location'],
            'found_item_date' => $matchData['found_item']['date_found'],
            'found_reporter_name' => $matchData['found_item']['reporter_name'],
            'match_date' => $matchData['updated_at'],
            'match_id' => $matchData['id'],
            'user_type' => $userType
        ];
        
        // Use custom subject and body for status updates
        $customBody = "<html><body>
        <h2>Match Status Update</h2>
        <p>Dear {{user_name}},</p>
        <p>{{status_message}}</p>
        
        <h3>Match Details:</h3>
        <ul>
            <li><strong>Match ID:</strong> {{match_id}}</li>
            <li><strong>Status:</strong> {{match_status}}</li>
            <li><strong>Match Score:</strong> {{match_score}}%</li>
            <li><strong>Updated:</strong> {{match_date}}</li>
        </ul>
        
        <h3>Lost Item:</h3>
        <ul>
            <li><strong>Title:</strong> {{lost_item_title}}</li>
            <li><strong>Description:</strong> {{lost_item_description}}</li>
            <li><strong>Category:</strong> {{lost_item_category}}</li>
            <li><strong>Location:</strong> {{lost_item_location}}</li>
            <li><strong>Date Lost:</strong> {{lost_item_date}}</li>
            <li><strong>Reporter:</strong> {{lost_reporter_name}}</li>
        </ul>
        
        <h3>Found Item:</h3>
        <ul>
            <li><strong>Title:</strong> {{found_item_title}}</li>
            <li><strong>Description:</strong> {{found_item_description}}</li>
            <li><strong>Category:</strong> {{found_item_category}}</li>
            <li><strong>Location:</strong> {{found_item_location}}</li>
            <li><strong>Date Found:</strong> {{found_item_date}}</li>
            <li><strong>Reporter:</strong> {{found_reporter_name}}</li>
        </ul>
        
        <p>If you have any questions, please contact the Lost and Found system administrators.</p>
        
        <p>Best regards,<br>CSPC Lost and Found System</p>
        </body></html>";
        
        // Replace placeholders in custom content
        $renderedSubject = $this->templateManager->replacePlaceholders($subject, $placeholders);
        $renderedBody = $this->templateManager->replacePlaceholders($customBody, $placeholders);
        
        // Log email attempt
        $logData = [
            'recipient_email' => $userData['email'],
            'recipient_name' => $userData['full_name'],
            'subject' => $renderedSubject,
            'body' => $renderedBody,
            'template_type' => 'match_status_notification',
            'status' => 'pending',
            'sent_by' => 'System'
        ];
        
        $logId = $this->emailLogModel->insert($logData);
        
        if ($dryRun) {
            // For testing, just return true without sending
            log_message('info', 'DRY RUN: Would send match status email to ' . $userData['email'] . ' with subject: ' . $renderedSubject);
            $this->emailLogModel->update($logId, [
                'status' => 'sent',
                'sent_at' => date('Y-m-d H:i:s')
            ]);
            return true;
        }
        
        // Send email
        $this->email->setTo($userData['email'], $userData['full_name']);
        $this->email->setSubject($renderedSubject);
        $this->email->setMessage($renderedBody);
        
        if ($this->email->send()) {
            // Update log as sent
            $this->emailLogModel->update($logId, [
                'status' => 'sent',
                'sent_at' => date('Y-m-d H:i:s')
            ]);
            
            log_message('info', "Match status email sent successfully to {$userData['email']} for match {$matchData['id']}");
            return true;
        } else {
            // Update log as failed
            $this->emailLogModel->update($logId, [
                'status' => 'failed',
                'error_message' => $this->email->printDebugger(['headers'])
            ]);
            
            log_message('error', "Failed to send match status email to {$userData['email']}: " . $this->email->printDebugger(['headers']));
            return false;
   }
    }

    /**
     * Send item reported notification email
     * 
     * @param array $itemData Item data
     * @param array $userData User data
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendItemReportedNotification($itemData, $userData, $dryRun = false)
    {
        $placeholders = [
            'user_name' => $userData['full_name'] ?? $userData['email'],
            'item_name' => $itemData['title'],
            'item_description' => $itemData['description'],
            'item_category' => $itemData['category'],
            'item_type' => $itemData['type'],
            'item_location' => $itemData['location'],
            'item_date' => $itemData['date_lost_found'],
            'item_id' => $itemData['id'],
            'report_date' => $itemData['date_reported'] ?? date('Y-m-d H:i:s')
        ];
        
        return $this->sendTemplatedEmail(
            'item_reported',
            $userData['email'],
            $userData['full_name'] ?? $userData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }
    
    /**
     * Send claim resolved notification email
     * 
     * @param array $claimData Claim data
     * @param array $userData User data
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function sendClaimResolvedNotification($claimData, $userData, $dryRun = false)
    {
        $template = $this->templateManager->getTemplate('claim_resolved');
        if (!$template) {
            log_message('error', 'Claim resolved template not found');
            return false;
        }
        
        $placeholders = [
            'user_name' => $userData['full_name'],
            'claimant_name' => $claimData['claimant_name'],
            'item_name' => $claimData['item_name'],
            'item_category' => $claimData['item_category'],
            'item_location' => $claimData['item_location'],
            'claim_description' => $claimData['claim_description'],
            'resolution_date' => $claimData['updated_at'],
            'claim_id' => $claimData['id']
        ];
        
        return $this->sendTemplatedEmail(
            'claim_resolved',
            $userData['email'],
            $userData['full_name'] ?? $userData['email'],
            $placeholders,
            'System',
            $dryRun
        );
    }
    
    /**
     * Send match created notification to admins and staff
     * Notifies system_admin, admin, and staff when a new match is found
     * 
     * @param array $matchData Match data including lost and found item information
     * @param bool $dryRun If true, don't actually send email (for testing)
     * @return bool Success status
     */
    public function createMatchCreatedNotification($matchData, $dryRun = false)
    {
        try {
            // Get all admin emails (system_admin, admin, and staff)
            $adminRoleModel = new \App\Models\AdminRoleModel();
            $admins = $adminRoleModel
                ->whereIn('role', ['super_admin', 'admin', 'staff'])
                ->findAll();
            
            if (empty($admins)) {
                log_message('warning', 'No admins found to notify about match creation');
                return false;
            }
            
            // Prepare notification data
            $notificationModel = new \App\Models\NotificationModel();
            $successCount = 0;
            
            foreach ($admins as $admin) {
                // Create web notification
                $notificationData = [
                    'user_email' => $admin['email'],
                    'title' => 'New Match Found',
                    'message' => sprintf(
                        'A new match has been found between "%s" (lost) and "%s" (found) with a score of %.2f%%',
                        $matchData['lost_item_title'] ?? 'Unknown',
                        $matchData['found_item_title'] ?? 'Unknown',
                        ($matchData['score'] ?? 0) * 100
                    ),
                    'type' => 'match',
                    'entity_type' => 'match',
                    'entity_id' => $matchData['id'] ?? null,
                    'is_read' => false,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                // Insert notification
                if ($notificationModel->insert($notificationData)) {
                    $successCount++;
                    log_message('info', "Match notification created for {$admin['email']}");
                } else {
                    log_message('error', "Failed to insert notification for {$admin['email']}: " . json_encode($notificationModel->errors()));
                }
            }
            
            log_message('info', "Match created notifications sent to {$successCount} admins/staff");
            return true;
            
        } catch (\Exception $e) {
            log_message('error', "Failed to create match notifications: " . $e->getMessage());
            return false;
        }
    }
}