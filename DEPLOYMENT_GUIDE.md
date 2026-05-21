# Quick Deployment Guide for Email Reply Notification System

## 🚀 Upload to Server: root@srv1052705.hstgr.cloud

### Option 1: Automated Deployment (PowerShell - Windows)

```powershell
# Run the deployment script
.\deploy_email_system.ps1
```

### Option 2: Manual Upload via SCP

```powershell
# Set your server details
$SERVER = "root@srv1052705.hstgr.cloud"
$PATH = "/var/www/html/capstone-cspc"  # Adjust to your actual path

# Upload files one by one
scp install_email_reply_system.sql $SERVER:$PATH/
scp app/Models/EmailThreadModel.php $SERVER:$PATH/app/Models/
scp app/Controllers/EmailWebhookController.php $SERVER:$PATH/app/Controllers/
scp app/Helpers/EmailTracker.php $SERVER:$PATH/app/Helpers/
scp app/Database/Migrations/2026-01-15-000001_CreateEmailThreadsTable.php $SERVER:$PATH/app/Database/Migrations/
```

### Option 3: Use SFTP Client (WinSCP, FileZilla)

1. Connect to: `srv1052705.hstgr.cloud`
2. Username: `root`
3. Navigate to your project folder
4. Upload these files:
   - `install_email_reply_system.sql` → root folder
   - `EmailThreadModel.php` → `app/Models/`
   - `EmailWebhookController.php` → `app/Controllers/`
   - `EmailTracker.php` → `app/Helpers/`
   - `2026-01-15-000001_CreateEmailThreadsTable.php` → `app/Database/Migrations/`

## 🔧 Installation on Server

### Step 1: SSH into Server
```bash
ssh root@srv1052705.hstgr.cloud
```

### Step 2: Navigate to Project
```bash
cd /var/www/html/capstone-cspc  # Adjust to your path
```

### Step 3: Install Database
```bash
mysql -u root -p lost_and_found < install_email_reply_system.sql
```

### Step 4: Add Route

Edit `app/Config/Routes.php`:
```bash
nano app/Config/Routes.php
```

Add this line:
```php
// Email reply webhook
$routes->post('webhook/email-reply', 'EmailWebhookController::receiveReply');
$routes->get('webhook/test-reply', 'EmailWebhookController::testReply');
```

### Step 5: Set Permissions
```bash
chmod 755 app/Controllers/EmailWebhookController.php
chmod 755 app/Models/EmailThreadModel.php
chmod 755 app/Helpers/EmailTracker.php
```

### Step 6: Test Installation
```bash
curl https://yourdomain.com/webhook/test-reply
```

Or visit in browser:
```
https://yourdomain.com/webhook/test-reply
```

## ✅ Verify Installation

### Check Database Table
```bash
mysql -u root -p lost_and_found -e "DESCRIBE email_threads;"
```

### Check Files Exist
```bash
ls -la app/Controllers/EmailWebhookController.php
ls -la app/Models/EmailThreadModel.php
ls -la app/Helpers/EmailTracker.php
```

### Check Notifications Created
```bash
mysql -u root -p lost_and_found -e "SELECT * FROM notifications_web WHERE title LIKE '%User Replied%';"
```

## 🎯 Quick Test Flow

1. **Upload files** (using deployment script or manual SCP)
2. **SSH to server**: `ssh root@srv1052705.hstgr.cloud`
3. **Install DB**: `mysql -u root -p lost_and_found < install_email_reply_system.sql`
4. **Add route** to Routes.php
5. **Test**: Visit `https://yourdomain.com/webhook/test-reply`
6. **Check admin notifications** in your dashboard

## 🔐 Important Notes

- **Replace `/var/www/html/capstone-cspc`** with your actual project path
- **Database credentials**: Update if not using root
- **Domain**: Replace `yourdomain.com` with your actual domain
- **Backup first**: Always backup before deploying to production

## 📞 Need Help?

If deployment fails:
1. Check file paths are correct
2. Verify database credentials
3. Check server logs: `tail -f writable/logs/log-*.log`
4. Verify permissions on uploaded files

---

**Ready to deploy! Run `.\deploy_email_system.ps1` to start!**
