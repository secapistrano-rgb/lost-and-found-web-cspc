#!/bin/bash
# ============================================
# DEPLOY EMAIL REPLY NOTIFICATION SYSTEM
# ============================================
# This script uploads and installs the email reply notification system
# to your production server

SERVER="root@srv1052705.hstgr.cloud"
REMOTE_PATH="/var/www/html/capstone-cspc"  # Adjust this path as needed
LOCAL_PATH="."

echo "📤 Deploying Email Reply Notification System to $SERVER..."

# 1. Upload SQL installer
echo "Uploading database installer..."
scp install_email_reply_system.sql "$SERVER:$REMOTE_PATH/"

# 2. Upload Models
echo "Uploading EmailThreadModel..."
scp app/Models/EmailThreadModel.php "$SERVER:$REMOTE_PATH/app/Models/"

# 3. Upload Controllers
echo "Uploading EmailWebhookController..."
scp app/Controllers/EmailWebhookController.php "$SERVER:$REMOTE_PATH/app/Controllers/"

# 4. Upload Helpers
echo "Uploading EmailTracker helper..."
scp app/Helpers/EmailTracker.php "$SERVER:$REMOTE_PATH/app/Helpers/"

# 5. Upload Migration
echo "Uploading database migration..."
scp app/Database/Migrations/2026-01-15-000001_CreateEmailThreadsTable.php "$SERVER:$REMOTE_PATH/app/Database/Migrations/"

# 6. Upload documentation
echo "Uploading documentation..."
scp QUICKSTART_EMAIL_REPLIES.md "$SERVER:$REMOTE_PATH/"
scp EMAIL_REPLY_NOTIFICATION_SETUP.md "$SERVER:$REMOTE_PATH/"

echo ""
echo "✅ Files uploaded successfully!"
echo ""
echo "🔧 Next steps (run these on your server):"
echo ""
echo "1. SSH into your server:"
echo "   ssh $SERVER"
echo ""
echo "2. Install database table:"
echo "   cd $REMOTE_PATH"
echo "   mysql -u your_db_user -p your_db_name < install_email_reply_system.sql"
echo ""
echo "3. Add route to app/Config/Routes.php:"
echo "   \$routes->post('webhook/email-reply', 'EmailWebhookController::receiveReply');"
echo ""
echo "4. Test the system:"
echo "   curl https://yourdomain.com/webhook/test-reply"
echo ""
echo "📖 See QUICKSTART_EMAIL_REPLIES.md for more details"
