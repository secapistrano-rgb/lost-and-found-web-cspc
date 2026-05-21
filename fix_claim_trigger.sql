-- ============================================
-- FIX: Drop and recreate after_claim_status_update trigger
-- This trigger was referencing c.claimant_email which doesn't exist
-- ============================================

USE lost_and_found;

-- Drop the old trigger
DROP TRIGGER IF EXISTS after_claim_status_update;

-- Recreate the correct trigger
DELIMITER $$
CREATE TRIGGER `after_claim_status_update` AFTER UPDATE ON `claims` FOR EACH ROW BEGIN
    DECLARE item_title VARCHAR(255) DEFAULT 'Item';
    DECLARE notification_id VARCHAR(255);
    DECLARE notification_title VARCHAR(255);
    DECLARE notification_message TEXT;
    DECLARE notification_data JSON;
    
    -- Only create notification if status actually changed
    IF OLD.status != NEW.status THEN
        
        -- Generate unique notification ID
        SET notification_id = CONCAT('claimTrigger_', UNIX_TIMESTAMP(NOW(3)) * 1000, '_', SUBSTRING(MD5(RAND()), 1, 9));
        
        -- Get item title from associated item or match
        -- Note: Using NEW.match_id and NEW.item_id directly to avoid any column issues
        SELECT COALESCE(li.title, fi.title, 'Item') INTO item_title
        FROM matches m 
        LEFT JOIN items li ON m.lost_item_id = li.id OR NEW.item_id = li.id
        LEFT JOIN items fi ON m.found_item_id = fi.id OR NEW.item_id = fi.id
        WHERE m.id = NEW.match_id
        LIMIT 1;
        
        -- Set notification title based on new status
        CASE NEW.status
            WHEN 'approved' THEN 
                SET notification_title = 'Claim Request Approved';
            WHEN 'rejected' THEN 
                SET notification_title = 'Claim Request Rejected';
            WHEN 'resolved' THEN 
                SET notification_title = 'Claim Request Resolved';
            WHEN 'pending' THEN 
                SET notification_title = 'Claim Request Received';
            ELSE 
                SET notification_title = 'Claim Request Updated';
        END CASE;
        
        -- Set notification message based on new status
        CASE NEW.status
            WHEN 'approved' THEN 
                SET notification_message = CONCAT('Your claim request for "', item_title, '" has been approved! Please check your gmail account for more details from SCC admin, Thank you!!');
            WHEN 'rejected' THEN 
                SET notification_message = CONCAT('Your claim request for "', item_title, '" has been rejected. Sorry we did''nt accept you claim request for some reasons.');
            WHEN 'resolved' THEN 
                SET notification_message = CONCAT('Your claim request for "', item_title, '" has been resolved! The item has been returned to you. Thank you for using the Lost and Found system!');
            WHEN 'pending' THEN 
                SET notification_message = CONCAT('Your claim request for "', item_title, '" has been received and is pending review. Please wait for the approval, and the SSC support staff will email you. Thank you!!');
            ELSE 
                SET notification_message = CONCAT('Your claim request for "', item_title, '" has been updated. Please check your gmail account for more details from SCC support staff, Thank you!!');
        END CASE;
        
        -- Create JSON data for notification
        SET notification_data = JSON_OBJECT(
            'type', 'claimRequest',
            'claimId', NEW.id,
            'status', NEW.status,
            'updatedBy', 'trigger'
        );
        
        -- Insert notification into notifications table
        INSERT INTO notifications (
            id, 
            type, 
            title, 
            message, 
            data, 
            `read`, 
            created_at, 
            user_email
        ) VALUES (
            notification_id,
            'claimRequest',
            notification_title,
            notification_message,
            JSON_UNQUOTE(notification_data),
            0,
            NOW(),
            NEW.claimant_id
        );
        
    END IF;
END$$
DELIMITER ;

-- Verify the trigger was created
SHOW TRIGGERS WHERE `Table` = 'claims' AND `Trigger` = 'after_claim_status_update';

SELECT 'Trigger fixed successfully! The after_claim_status_update trigger has been recreated without claimant_email reference.' AS status;
