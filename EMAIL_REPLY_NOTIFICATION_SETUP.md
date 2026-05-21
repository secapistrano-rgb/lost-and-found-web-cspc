# Email Reply Notification System

## Overview
This system automatically notifies admins when users reply to system-generated emails (like "Claim Resolved" notifications). When admins click the notification, they're taken directly to Gmail to view and respond to the user.

## Architecture

### 1. Email Tracking System
- **email_threads table**: Tracks all sent emails with unique Message-IDs
- **EmailThreadModel**: Manages email thread data
- **EmailTracker helper**: Generates unique Message-IDs and tracks sent emails

### 2. Reply Detection
- **EmailWebhookController**: Webhook endpoint to receive email reply notifications
- Integrates with email services (SendGrid, Mailgun, etc.) or IMAP polling
- Matches replies to original emails using Message-ID headers

### 3. Admin Notifications
- Creates high-priority notifications when replies detected
- Links to Gmail search URL to find the conversation
- Shows preview of user's reply

## Installation

### Step 1: Create Database Tables
```bash
mysql -u your_username -p lost_and_found < setup_email_reply_notifications.sql
```

Or run in MySQL:
```sql
source setup_email_reply_notifications.sql;
```

### Step 2: Add Route for Webhook
Add to `app/Config/Routes.php`:
```php
// Email reply webhook
$routes->post('webhook/email-reply', 'EmailWebhookController::receiveReply');
$routes->get('webhook/test-reply', 'EmailWebhookController::testReply'); // For testing only
```

### Step 3: Configure Email Service Webhook

#### Option A: Using SendGrid
1. Go to SendGrid Settings → Mail Settings → Inbound Parse
2. Add your domain and subdomain (e.g., `reply.yourdomain.com`)
3. Set destination URL: `https://yourdomain.com/webhook/email-reply`
4. Add webhook secret to `.env`: `EMAIL_WEBHOOK_SECRET=your-secret-key`

#### Option B: Using Mailgun
1. Go to Mailgun Routes
2. Create route: Forward to URL
3. URL: `https://yourdomain.com/webhook/email-reply`
4. Add webhook secret to `.env`

#### Option C: Custom IMAP Polling (No webhook service needed)
Create a cron job that checks email via IMAP:
```php
// app/Commands/CheckEmailReplies.php
<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;

class CheckEmailReplies extends BaseCommand
{
    protected $group = 'Email';
    protected $name = 'email:check-replies';
    protected $description = 'Check for email replies via IMAP';

    public function run(array $params)
    {
        // Connect to Gmail via IMAP
        $imap = imap_open(
            '{imap.gmail.com:993/imap/ssl}INBOX',
            'your-email@gmail.com',
            'your-app-password'
        );

        // Search for unread emails
        $emails = imap_search($imap, 'UNSEEN');

        if ($emails) {
            foreach ($emails as $emailId) {
                // Process each email
                $header = imap_headerinfo($imap, $emailId);
                $body = imap_body($imap, $emailId);
                
                // Trigger webhook processing
                // ... (similar to webhook logic)
            }
        }

        imap_close($imap);
    }
}
```

Set up cron job:
```bash
# Check every 5 minutes
*/5 * * * * cd /path/to/project && php spark email:check-replies
```

## Usage in Your Email Code

### When Sending Emails
Update your email sending code to track emails:

```php
use App\Helpers\EmailTracker;

// Example: Sending claim resolved email
$email = \Config\Services::email();
$email->setTo('user@example.com');
$email->setSubject('Claim Resolved - Lost Accessories');
$email->setMessage('Your claim has been resolved...');

// Add tracking headers
$messageId = EmailTracker::addTrackingHeaders($email, 'claim', $claimId);

// Send email
if ($email->send()) {
    // Track the sent email
    EmailTracker::trackSentEmail(
        $messageId,
        'user@example.com',
        'Claim Resolved - Lost Accessories',
        'claim',
        $claimId
    );
}
```

### Example: Update Existing Email Notification Code

**Before:**
```php
$email->setTo($user['email']);
$email->setSubject('Claim Resolved');
$email->setMessage($message);
$email->send();
```

**After:**
```php
use App\Helpers\EmailTracker;

$email->setTo($user['email']);
$email->setSubject('Claim Resolved');
$email->setMessage($message);

// Add tracking
$messageId = EmailTracker::addTrackingHeaders($email, 'claim', $claimId);

if ($email->send()) {
    EmailTracker::trackSentEmail(
        $messageId,
        $user['email'],
        'Claim Resolved',
        'claim',
        $claimId
    );
}
```

## Webhook Payload Format

When email service sends reply notification to webhook:

```json
{
    "from": "user@example.com",
    "to": "lostfoundcspc@gmail.com",
    "subject": "Re: Claim Resolved - Lost Accessories",
    "in_reply_to": "<claim-123-abc@yourdomain.com>",
    "body": "That's great, thank you very much."
}
```

## Admin Notification Example

When user replies, admins receive:

**Title:** 📧 User Replied to Email

**Message:**
```
User secapistrano@my.cspc.edu.ph replied to: "Claim Resolved - Lost Accessories"

Preview: That's great, thank you very much.

Click to view and reply in Gmail.
```

**Action URL:** `https://mail.google.com/mail/u/0/#search/from:secapistrano@my.cspc.edu.ph`

**Priority:** High

## Testing

### Manual Test (Development)
```bash
# Visit in browser or curl
curl http://localhost:8080/webhook/test-reply
```

### Test with Real Email
1. Send an email from your system to a test user
2. Have test user reply to that email
3. Check if admin receives notification

### Verify Tracking
```sql
-- Check tracked emails
SELECT * FROM email_threads ORDER BY created_at DESC LIMIT 10;

-- Check for replies
SELECT * FROM email_threads WHERE has_reply = 1;

-- Check admin notifications
SELECT * FROM notifications_web 
WHERE title LIKE '%User Replied%' 
ORDER BY created_at DESC;
```

## Security Considerations

1. **Webhook Authentication**
   - Always verify webhook secret
   - Use HTTPS for webhook endpoint
   - Rate limit webhook endpoint

2. **Email Validation**
   - Verify sender email matches expected users
   - Sanitize email content before displaying
   - Check for spam/malicious content

3. **Environment Variables**
   ```env
   EMAIL_WEBHOOK_SECRET=your-random-secret-key-here
   ```

## Troubleshooting

### Notifications Not Appearing?
1. Check webhook is receiving requests: `tail -f writable/logs/log-*.log`
2. Verify webhook secret matches
3. Check email_threads table for tracked emails
4. Verify admin_roles table has entries

### Gmail Link Not Working?
- Ensure Gmail URL format is correct
- User must be logged into Gmail
- Consider using direct email client protocol: `mailto:user@example.com`

### Replies Not Detected?
1. Verify Message-ID header is being sent
2. Check email service is forwarding to webhook
3. Verify In-Reply-To header matches Message-ID
4. Check webhook logs for errors

## Files Created

1. **Database Migration**: `app/Database/Migrations/2026-01-15-000001_CreateEmailThreadsTable.php`
2. **Model**: `app/Models/EmailThreadModel.php`
3. **Controller**: `app/Controllers/EmailWebhookController.php`
4. **Helper**: `app/Helpers/EmailTracker.php`
5. **SQL Setup**: `setup_email_reply_notifications.sql`

## Integration Example

### Update ClaimController to Track Emails

```php
// In ClaimController::approveAndResolve()
use App\Helpers\EmailTracker;

// Existing email code...
$email->setTo($userEmail);
$email->setSubject('Claim Resolved - ' . $itemTitle);
$email->setMessage($emailMessage);

// Add tracking
$messageId = EmailTracker::addTrackingHeaders($email, 'claim', $claimId);

if ($email->send()) {
    // Track this email
    EmailTracker::trackSentEmail(
        $messageId,
        $userEmail,
        'Claim Resolved - ' . $itemTitle,
        'claim',
        $claimId
    );
    
    log_message('info', "Claim resolution email sent and tracked: {$messageId}");
}
```

## Future Enhancements

1. **Direct Email Integration**: Reply directly from notifications panel
2. **Email Threading**: Show full conversation history
3. **Auto-responses**: Template-based quick replies
4. **Email Analytics**: Track open rates, response times
5. **Multi-language Support**: Detect and translate replies

## Conclusion

The system is now ready to:
✅ Track all outgoing emails with unique Message-IDs
✅ Detect when users reply via webhook
✅ Notify all admins with high-priority alerts
✅ Provide direct Gmail links to view and respond
✅ Maintain conversation context with entity linking

Admins will be immediately notified whenever users reply to any system email!
