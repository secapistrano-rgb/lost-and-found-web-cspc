# Email Reply Notification - Quick Setup Guide

## 📧 What This Does

When users reply to system emails (like "Claim Resolved - Lost Accessories"), **admins get instant notifications** with:
- User's email address
- Preview of their reply
- **Direct Gmail link** to view and respond

## 🚀 Quick Installation (3 Steps)

### Step 1: Install Database Table
```bash
mysql -u your_username -p lost_and_found < install_email_reply_system.sql
```

### Step 2: Add Webhook Route

Edit `app/Config/Routes.php`, add:
```php
// Email reply webhook (add after other routes)
$routes->post('webhook/email-reply', 'EmailWebhookController::receiveReply');
$routes->get('webhook/test-reply', 'EmailWebhookController::testReply');
```

### Step 3: Update ONE Email Function

Find where you send "Claim Resolved" emails and add 2 lines:

**BEFORE:**
```php
$email->setTo('user@example.com');
$email->setSubject('Claim Resolved');
$email->send();
```

**AFTER:**
```php
use App\Helpers\EmailTracker;

$email->setTo('user@example.com');
$email->setSubject('Claim Resolved');

// ADD THESE 2 LINES:
$messageId = EmailTracker::addTrackingHeaders($email, 'claim', $claimId);
if ($email->send()) {
    EmailTracker::trackSentEmail($messageId, 'user@example.com', 'Claim Resolved', 'claim', $claimId);
}
```

## 📱 Testing (No Email Service Needed Yet)

Test immediately without configuring email webhook:

1. Visit: `http://localhost:8080/webhook/test-reply`
2. Check admin notifications
3. Click notification → Goes to Gmail

## ⚙️ Production Setup (Later)

### Option A: Gmail Auto-Forward (Easiest)
1. In Gmail Settings → Forwarding
2. Forward to: `https://yourdomain.com/webhook/email-reply`
3. Done!

### Option B: Email Service Webhook
Configure SendGrid/Mailgun to forward replies to your webhook.

### Option C: IMAP Polling (No Service Needed)
Set up cron job to check Gmail every 5 minutes (see full docs).

## 📋 Files Created

1. ✅ **Database**: `email_threads` table
2. ✅ **Model**: `EmailThreadModel.php`  
3. ✅ **Controller**: `EmailWebhookController.php`
4. ✅ **Helper**: `EmailTracker.php`
5. ✅ **Migration**: `CreateEmailThreadsTable.php`

## 🎯 How It Works

```
User replies to "Claim Resolved" email
           ↓
Email service forwards to webhook
           ↓
System finds original email thread
           ↓
Creates notification for ALL admins
           ↓
Admin clicks → Opens Gmail to reply
```

## 🔍 Verify Installation

```sql
-- Check table exists
SHOW TABLES LIKE 'email_threads';

-- Track a test email (manual insert)
INSERT INTO email_threads (message_id, to_email, from_email, subject, entity_type, entity_id)
VALUES ('<test@example.com>', 'user@test.com', 'noreply@cspc.edu.ph', 'Test Email', 'claim', '123');

-- Check notifications
SELECT * FROM notifications_web WHERE title LIKE '%User Replied%';
```

## 💡 Admin Notification Example

**Title:** 📧 User Replied to Email

**Message:**
```
User secapistrano@my.cspc.edu.ph replied to: "Claim Resolved - Lost Accessories"

Preview: That's great, thank you very much.

Click to view and reply in Gmail.
```

**Click Action:** Opens Gmail search for that user's email

## 🎉 You're Done!

- Database table: ✅ Created
- Controllers: ✅ Ready
- Models: ✅ Ready
- Routes: ✅ (Add manually)

Just add the EmailTracker to your email code and you're set!

---

**See EMAIL_REPLY_NOTIFICATION_SETUP.md for advanced configuration.**
