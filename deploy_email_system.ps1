# ============================================
# DEPLOY EMAIL REPLY NOTIFICATION SYSTEM (Windows)
# ============================================
# PowerShell script to upload files to production server

$SERVER = "root@srv1052705.hstgr.cloud"
$REMOTE_PATH = "/var/www/html/capstone-cspc"  # Adjust as needed
$LOCAL_PATH = "."

Write-Host "`n📤 Deploying Email Reply Notification System to $SERVER..." -ForegroundColor Cyan

# Check if SCP is available
if (!(Get-Command scp -ErrorAction SilentlyContinue)) {
    Write-Host "❌ SCP not found. Please install OpenSSH client." -ForegroundColor Red
    Write-Host "Run: Add-WindowsCapability -Online -Name OpenSSH.Client~~~~0.0.1.0" -ForegroundColor Yellow
    exit
}

# 1. Upload SQL installer
Write-Host "`nUploading database installer..." -ForegroundColor Yellow
scp install_email_reply_system.sql "${SERVER}:${REMOTE_PATH}/"

# 2. Upload Models
Write-Host "Uploading EmailThreadModel..." -ForegroundColor Yellow
scp app/Models/EmailThreadModel.php "${SERVER}:${REMOTE_PATH}/app/Models/"

# 3. Upload Controllers
Write-Host "Uploading EmailWebhookController..." -ForegroundColor Yellow
scp app/Controllers/EmailWebhookController.php "${SERVER}:${REMOTE_PATH}/app/Controllers/"

# 4. Upload Helpers
Write-Host "Uploading EmailTracker helper..." -ForegroundColor Yellow
scp app/Helpers/EmailTracker.php "${SERVER}:${REMOTE_PATH}/app/Helpers/"

# 5. Upload Migration
Write-Host "Uploading database migration..." -ForegroundColor Yellow
scp app/Database/Migrations/2026-01-15-000001_CreateEmailThreadsTable.php "${SERVER}:${REMOTE_PATH}/app/Database/Migrations/"

# 6. Upload documentation
Write-Host "Uploading documentation..." -ForegroundColor Yellow
scp QUICKSTART_EMAIL_REPLIES.md "${SERVER}:${REMOTE_PATH}/"
scp EMAIL_REPLY_NOTIFICATION_SETUP.md "${SERVER}:${REMOTE_PATH}/"

Write-Host "`n✅ Files uploaded successfully!" -ForegroundColor Green
Write-Host "`n🔧 Next steps (run on your server):" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. SSH into your server:" -ForegroundColor White
Write-Host "   ssh $SERVER" -ForegroundColor Yellow
Write-Host ""
Write-Host "2. Install database table:" -ForegroundColor White
Write-Host "   cd $REMOTE_PATH" -ForegroundColor Yellow
Write-Host "   mysql -u your_db_user -p your_db_name < install_email_reply_system.sql" -ForegroundColor Yellow
Write-Host ""
Write-Host "3. Add route to app/Config/Routes.php:" -ForegroundColor White
Write-Host "   `$routes->post('webhook/email-reply', 'EmailWebhookController::receiveReply');" -ForegroundColor Yellow
Write-Host ""
Write-Host "4. Test the system:" -ForegroundColor White
Write-Host "   curl https://yourdomain.com/webhook/test-reply" -ForegroundColor Yellow
Write-Host ""
Write-Host "📖 See QUICKSTART_EMAIL_REPLIES.md for more details`n" -ForegroundColor Cyan
