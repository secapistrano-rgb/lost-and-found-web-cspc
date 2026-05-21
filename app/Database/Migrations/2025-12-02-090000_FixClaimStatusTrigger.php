<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixClaimStatusTrigger extends Migration
{
    public function up()
    {
        $this->db->query('DROP TRIGGER IF EXISTS after_claim_status_update');

        $this->db->query(<<<'SQL'
CREATE TRIGGER `after_claim_status_update`
AFTER UPDATE ON `claims`
FOR EACH ROW
BEGIN
    DECLARE notification_id VARCHAR(255);
    DECLARE notification_title VARCHAR(255);
    DECLARE notification_message TEXT;
    DECLARE notification_data JSON;
    DECLARE status_label VARCHAR(50);
    DECLARE item_title VARCHAR(255);

    IF OLD.status <> NEW.status THEN
        SELECT title INTO item_title
        FROM items
        WHERE id = NEW.item_id
        LIMIT 1;

        IF item_title IS NULL THEN
            SET item_title = CONCAT('Item #', NEW.item_id);
        END IF;

        SET notification_id = CONCAT('claimStatus_', UNIX_TIMESTAMP(NOW()), '_', SUBSTRING(MD5(RAND()), 1, 9));
        SET notification_title = 'Claim Request Status Updated';

        CASE NEW.status
            WHEN 'pending' THEN
                SET status_label = 'Pending Review';
                SET notification_message = CONCAT(
                    'Your claim request for "', LEFT(item_title, 50),
                    CASE WHEN LENGTH(item_title) > 50 THEN '...' ELSE '' END,
                    '" has been received and is currently pending review. Our team will assess your submission shortly.'
                );
            WHEN 'reviewing' THEN
                SET status_label = 'Under Review';
                SET notification_message = CONCAT(
                    'Your claim request for "', LEFT(item_title, 50),
                    CASE WHEN LENGTH(item_title) > 50 THEN '...' ELSE '' END,
                    '" is now under review. Our staff is carefully evaluating your proof of ownership.'
                );
            WHEN 'approved' THEN
                SET status_label = 'Approved';
                SET notification_message = CONCAT(
                    'Congratulations! Your claim request for "', LEFT(item_title, 50),
                    CASE WHEN LENGTH(item_title) > 50 THEN '...' ELSE '' END,
                    '" has been approved. Please check your email for further instructions regarding item retrieval.'
                );
            WHEN 'rejected' THEN
                SET status_label = 'Rejected';
                SET notification_message = CONCAT(
                    'Your claim request for "', LEFT(item_title, 50),
                    CASE WHEN LENGTH(item_title) > 50 THEN '...' ELSE '' END,
                    '" has been declined. Please check your email for the reason and further guidance.'
                );
            WHEN 'completed' THEN
                SET status_label = 'Completed';
                SET notification_message = CONCAT(
                    'Your claim for "', LEFT(item_title, 50),
                    CASE WHEN LENGTH(item_title) > 50 THEN '...' ELSE '' END,
                    '" has been completed. Thank you for using the Lost and Found system.'
                );
            WHEN 'resolved' THEN
                SET status_label = 'Resolved';
                SET notification_message = CONCAT(
                    'Your claim request for "', LEFT(item_title, 50),
                    CASE WHEN LENGTH(item_title) > 50 THEN '...' ELSE '' END,
                    '" has been successfully resolved. The item has been returned to you. Thank you for your patience.'
                );
            ELSE
                SET status_label = NEW.status;
                SET notification_message = CONCAT(
                    'Your claim request for "', LEFT(item_title, 50),
                    CASE WHEN LENGTH(item_title) > 50 THEN '...' ELSE '' END,
                    '" status has been updated. Please tap to view details.'
                );
        END CASE;

        SET notification_data = JSON_OBJECT(
            'type', 'claimStatus',
            'claimId', NEW.id,
            'oldStatus', OLD.status,
            'newStatus', NEW.status,
            'itemTitle', item_title,
            'itemId', NEW.item_id,
            'statusLabel', status_label
        );

        INSERT INTO notifications (
            id,
            user_email,
            type,
            title,
            message,
            data,
            `read`,
            created_at
        ) VALUES (
            notification_id,
            COALESCE(NEW.claimant_id, NEW.google_user_id),
            'claimStatus',
            notification_title,
            notification_message,
            JSON_UNQUOTE(notification_data),
            0,
            NOW()
        );
    END IF;
END
SQL
        );
    }

    public function down()
    {
        $this->db->query('DROP TRIGGER IF EXISTS after_claim_status_update');
    }
}
