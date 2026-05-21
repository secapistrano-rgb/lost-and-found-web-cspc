<?php

namespace App\Controllers;

use App\Models\EmailThreadModel;
use App\Models\NotificationModel;
use App\Models\AdminRoleModel;
use CodeIgniter\Controller;

/**
 * Email Poller Controller
 * 
 * Checks Gmail via IMAP for new email replies and creates admin notifications.
 * Run this via cron job every 1-5 minutes or call manually.
 */
class EmailPollerController extends Controller
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
     * Check Gmail for new replies via IMAP
     * Call this endpoint or run via cron
     * 
     * GET /email-poller/check
     */
    public function check()
    {
        // Gmail IMAP settings - UPDATE THESE!
        $imapHost = getenv('GMAIL_IMAP_HOST') ?: 'imap.gmail.com';
        $imapPort = getenv('GMAIL_IMAP_PORT') ?: 993;
        $imapUser = getenv('GMAIL_EMAIL') ?: 'your-system-email@gmail.com';
        $imapPass = getenv('GMAIL_APP_PASSWORD') ?: 'your-app-password';

        // Check if IMAP extension is available
        if (!function_exists('imap_open')) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'IMAP extension not installed. Run: sudo apt-get install php-imap'
            ]);
        }

        try {
            // Connect to Gmail IMAP
            $mailbox = '{' . $imapHost . ':' . $imapPort . '/imap/ssl}INBOX';
            $connection = @imap_open($mailbox, $imapUser, $imapPass);

            if (!$connection) {
                $error = imap_last_error();
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Failed to connect to Gmail: ' . $error,
                    'hint' => 'Make sure you are using an App Password, not your regular Gmail password'
                ]);
            }

            // Search for ALL emails from last 7 days (not just unread)
            // This catches replies even if the user opened Gmail
            $since = date('d-M-Y', strtotime('-7 days'));
            $emails = imap_search($connection, 'SINCE "' . $since . '"');

            $processed = 0;
            $notifications = 0;

            if ($emails) {
                // Process only the last 50 emails to avoid timeout
                $emails = array_slice($emails, -50);
                foreach ($emails as $emailNumber) {
                    $result = $this->processEmail($connection, $emailNumber);
                    $processed++;
                    if ($result) {
                        $notifications++;
                    }
                }
            }

            imap_close($connection);

            return $this->response->setJSON([
                'success' => true,
                'processed' => $processed,
                'notifications_created' => $notifications,
                'message' => "Checked {$processed} emails, created {$notifications} notifications"
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Process a single email to check if it's a reply to a system email
     */
    protected function processEmail($connection, $emailNumber)
    {
        // Get email headers
        $header = imap_headerinfo($connection, $emailNumber);
        $structure = imap_fetchstructure($connection, $emailNumber);

        // Get the email body
        $body = $this->getEmailBody($connection, $emailNumber, $structure);

        // Get sender info
        $fromEmail = $header->from[0]->mailbox . '@' . $header->from[0]->host;
        $fromName = isset($header->from[0]->personal) ? $header->from[0]->personal : $fromEmail;
        $subject = isset($header->subject) ? imap_utf8($header->subject) : 'No Subject';
        $emailDate = isset($header->date) ? strtotime($header->date) : time();
        
        // Create a unique ID based on sender, date, and subject hash
        $uniqueId = md5($fromEmail . $emailDate . $subject);
        
        // Skip if we've already processed this email
        $alreadyProcessed = $this->emailThreadModel
            ->where('message_id', 'reply_' . $uniqueId)
            ->first();
        if ($alreadyProcessed) {
            return false;
        }

        // Check if this is a reply to a system email by subject pattern
        $isSystemReply = false;
        $entityType = null;
        
        // Match system email subjects (adjust these patterns to match your email templates)
        $systemPatterns = [
            '/^Re:\s*Claim Approved/i' => 'claim_approved',
            '/^Re:\s*Claim Rejected/i' => 'claim_rejected', 
            '/^Re:\s*Claim Resolved/i' => 'claim_resolved',
            '/^Re:\s*Item Returned/i' => 'item_returned',
            '/^Re:\s*Your Reported Item/i' => 'item_claimed',
            '/^Re:\s*Lost Item/i' => 'lost_item',
            '/^Re:\s*Found Item/i' => 'found_item',
        ];
        
        foreach ($systemPatterns as $pattern => $type) {
            if (preg_match($pattern, $subject)) {
                $isSystemReply = true;
                $entityType = $type;
                break;
            }
        }

        // If this looks like a reply to a system email, create notification
        if ($isSystemReply) {
            // Create a virtual thread for notification
            $thread = [
                'id' => 0,
                'subject' => preg_replace('/^Re:\s*/i', '', $subject),
                'entity_type' => $entityType,
                'entity_id' => null
            ];

            // Create admin notifications
            $this->notifyAdminsOfReply($thread, $fromName, $fromEmail, $subject, $body);
            
            // Track that we processed this reply (prevent duplicate notifications)
            try {
                $this->emailThreadModel->insert([
                    'message_id' => 'reply_' . $uniqueId,
                    'to_email' => getenv('GMAIL_EMAIL') ?: 'lostfoundcspc@gmail.com',
                    'from_email' => $fromEmail,
                    'subject' => $subject,
                    'entity_type' => 'processed_reply',
                    'has_reply' => 1
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Failed to track processed reply: ' . $e->getMessage());
            }

            log_message('info', "Email reply detected from {$fromEmail}: {$subject}");
            return true;
        }

        return false;
    }

    /**
     * Extract a specific header from raw headers
     */
    protected function extractHeader($rawHeaders, $headerName)
    {
        if (preg_match('/' . $headerName . ':\s*([^\r\n]+)/i', $rawHeaders, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    /**
     * Get email body from IMAP structure
     */
    protected function getEmailBody($connection, $emailNumber, $structure)
    {
        $body = '';

        if (isset($structure->parts) && count($structure->parts)) {
            // Multipart email
            foreach ($structure->parts as $index => $part) {
                if ($part->subtype == 'PLAIN') {
                    $body = imap_fetchbody($connection, $emailNumber, $index + 1);
                    break;
                }
            }
        } else {
            // Simple email
            $body = imap_body($connection, $emailNumber);
        }

        // Decode if needed
        if (isset($structure->encoding)) {
            switch ($structure->encoding) {
                case 3: // BASE64
                    $body = base64_decode($body);
                    break;
                case 4: // QUOTED-PRINTABLE
                    $body = quoted_printable_decode($body);
                    break;
            }
        }

        // Clean up the body - remove quoted original message
        $body = $this->removeQuotedContent($body);

        return trim($body);
    }

    /**
     * Remove quoted original message from reply
     */
    protected function removeQuotedContent($body)
    {
        // Remove Gmail quote markers
        $patterns = [
            '/On .+wrote:.*$/s',           // "On [date] [person] wrote:"
            '/^>.*$/m',                      // Lines starting with >
            '/\r\n\r\n--+\r\n.*/s',         // Signatures after --
            '/_{10,}.*/s',                   // Lines of underscores (separators)
        ];

        foreach ($patterns as $pattern) {
            $body = preg_replace($pattern, '', $body);
        }

        return trim($body);
    }

    /**
     * Notify all admins of the email reply
     */
    protected function notifyAdminsOfReply($thread, $fromName, $fromEmail, $subject, $body)
    {
        // Get all admin emails from admin_roles table
        $admins = $this->adminRoleModel->findAll();
        
        if (empty($admins)) {
            log_message('warning', 'No admins found to notify about email reply');
            return;
        }

        // Truncate body for notification
        $bodyPreview = strlen($body) > 100 ? substr($body, 0, 100) . '...' : $body;

        // Create Gmail search URL to find this conversation
        $searchQuery = 'from:' . $fromEmail . ' OR to:' . $fromEmail;
        $gmailUrl = 'https://mail.google.com/mail/u/0/#search/' . urlencode($searchQuery);

        // Create notification for each admin
        foreach ($admins as $admin) {
            $adminEmail = $admin['email'] ?? null;
            $adminName = $admin['name'] ?? $admin['username'] ?? 'Admin';
            
            if (empty($adminEmail)) {
                continue;
            }

            $notificationData = [
                'user_email' => $adminEmail,
                'user_name' => $adminName,
                'title' => '📧 Email Reply: ' . $subject,
                'message' => "Reply from {$fromName} ({$fromEmail}): " . ($bodyPreview ?: 'No message content'),
                'type' => 'system',
                'entity_type' => $thread['entity_type'] ?? 'email_reply',
                'entity_id' => $thread['entity_id'] ?? null,
                'priority' => 'high',
                'action_url' => $gmailUrl,
                'is_read' => 0,
                'metadata' => json_encode([
                    'thread_id' => $thread['id'],
                    'from_email' => $fromEmail,
                    'from_name' => $fromName,
                    'subject' => $subject,
                    'gmail_url' => $gmailUrl
                ])
            ];

            try {
                $this->notificationModel->insert($notificationData);
                log_message('info', "Created email reply notification for admin: {$adminEmail}");
            } catch (\Exception $e) {
                log_message('error', "Failed to create notification for {$adminEmail}: " . $e->getMessage());
            }
        }

        log_message('info', "Created email reply notifications for " . count($admins) . " admins");
    }

    /**
     * Manual test endpoint - creates a test notification without IMAP
     * 
     * GET /email-poller/test
     */
    public function test()
    {
        // Create a fake thread for testing
        $testThread = [
            'id' => 0,
            'subject' => 'Test Email Thread',
            'entity_type' => 'claim',
            'entity_id' => 1
        ];

        $this->notifyAdminsOfReply(
            $testThread,
            'Test User',
            'testuser@gmail.com',
            'Re: Test Subject',
            'This is a test reply message from the user.'
        );

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Test notification created! Check admin notifications.'
        ]);
    }

    /**
     * Check IMAP configuration status
     * 
     * GET /email-poller/status
     */
    public function status()
    {
        $imapAvailable = function_exists('imap_open');
        $emailConfigured = getenv('GMAIL_EMAIL') && getenv('GMAIL_APP_PASSWORD');

        return $this->response->setJSON([
            'imap_extension' => $imapAvailable ? 'installed' : 'NOT INSTALLED - run: sudo apt-get install php-imap',
            'gmail_email' => getenv('GMAIL_EMAIL') ?: 'NOT SET - add GMAIL_EMAIL to .env',
            'gmail_password' => getenv('GMAIL_APP_PASSWORD') ? 'SET (hidden)' : 'NOT SET - add GMAIL_APP_PASSWORD to .env',
            'ready' => $imapAvailable && $emailConfigured,
            'instructions' => [
                '1. Enable IMAP in Gmail: Settings > Forwarding and POP/IMAP > Enable IMAP',
                '2. Create App Password: Google Account > Security > 2-Step Verification > App Passwords',
                '3. Add to .env file: GMAIL_EMAIL=your@gmail.com and GMAIL_APP_PASSWORD=xxxx-xxxx-xxxx-xxxx',
                '4. Set up cron job to run /email-poller/check every 2-5 minutes'
            ]
        ]);
    }

    /**
     * Debug endpoint - list recent emails in inbox
     * 
     * GET /email-poller/debug
     */
    public function debug()
    {
        $imapHost = getenv('GMAIL_IMAP_HOST') ?: 'imap.gmail.com';
        $imapPort = getenv('GMAIL_IMAP_PORT') ?: 993;
        $imapUser = getenv('GMAIL_EMAIL') ?: 'your-system-email@gmail.com';
        $imapPass = getenv('GMAIL_APP_PASSWORD') ?: 'your-app-password';

        if (!function_exists('imap_open')) {
            return $this->response->setJSON(['error' => 'IMAP not installed']);
        }

        try {
            $mailbox = '{' . $imapHost . ':' . $imapPort . '/imap/ssl}INBOX';
            $connection = @imap_open($mailbox, $imapUser, $imapPass);

            if (!$connection) {
                return $this->response->setJSON([
                    'error' => 'Failed to connect: ' . imap_last_error(),
                    'user' => $imapUser
                ]);
            }

            $since = date('d-M-Y', strtotime('-7 days'));
            $emails = imap_search($connection, 'SINCE "' . $since . '"');

            $emailList = [];
            if ($emails) {
                $emails = array_slice($emails, -20); // Last 20 emails
                foreach ($emails as $emailNumber) {
                    $header = imap_headerinfo($connection, $emailNumber);
                    $fromEmail = $header->from[0]->mailbox . '@' . $header->from[0]->host;
                    $subject = isset($header->subject) ? imap_utf8($header->subject) : 'No Subject';
                    
                    // Check for In-Reply-To header
                    $rawHeaders = imap_fetchheader($connection, $emailNumber);
                    $inReplyTo = '';
                    if (preg_match('/In-Reply-To:\s*(.+)/i', $rawHeaders, $match)) {
                        $inReplyTo = trim($match[1]);
                    }
                    
                    $emailList[] = [
                        'num' => $emailNumber,
                        'from' => $fromEmail,
                        'subject' => $subject,
                        'date' => $header->date ?? '',
                        'in_reply_to' => $inReplyTo,
                        'is_reply' => !empty($inReplyTo) || strpos($subject, 'Re:') === 0
                    ];
                }
            }

            imap_close($connection);

            // Also get tracked emails
            $trackedEmails = $this->emailThreadModel->orderBy('id', 'DESC')->findAll(10);

            return $this->response->setJSON([
                'success' => true,
                'inbox_emails' => $emailList,
                'tracked_emails' => $trackedEmails,
                'note' => 'For replies to be detected, the original email must be tracked (in tracked_emails)'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }
}
