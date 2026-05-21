-- ============================================
-- CREATE TRIGGER FOR FAKE REPORT STATUS NOTIFICATIONS
-- ============================================
-- This trigger will notify all admins when an item's status is changed to 'fake_report'
-- It complements the existing application logic in ItemController.php

-- Drop existing trigger if it exists
DROP TRIGGER IF EXISTS `notify_admins_fake_report`;

DELIMITER $$

CREATE TRIGGER `notify_admins_fake_report` 
AFTER UPDATE ON `items` 
FOR EACH ROW
BEGIN
    -- Only trigger when status changes TO Fake Report (from any other status)
    IF (OLD.status != NEW.status) AND (NEW.status = 'Fake Report') THEN
        
        -- Insert notification for each admin
        INSERT INTO notifications_web (
            user_email,
            user_name,
            title,
            message,
            type,
            entity_type,
            entity_id,
            is_read,
            priority,
            action_url,
            metadata,
            created_at,
            updated_at
        )
        SELECT 
            ar.email,
            COALESCE(ar.name, 'Admin') as user_name,
            'Fake Report Flagged' as title,
            CONCAT(
                'Item "', 
                COALESCE(NEW.title, 'Unknown Item'), 
                '" (', 
                UPPER(NEW.type), 
                ') has been flagged as a fake report.\n\n',
                'Reporter: ', 
                COALESCE(NEW.reporter_name, 'Unknown'),
                ' (', 
                COALESCE(NEW.reporter_email, 'No email'),
                ')\n',
                'Category: ',
                COALESCE(NEW.category, 'Uncategorized'),
                '\n',
                'Date Reported: ',
                DATE_FORMAT(NEW.date_reported, '%Y-%m-%d %H:%i'),
                '\n\n',
                'Please review this item and take appropriate action.'
            ) as message,
            'item' as type,
            'item' as entity_type,
            NEW.id as entity_id,
            0 as is_read,
            'high' as priority,
            CONCAT('/items/view/', NEW.id) as action_url,
            JSON_OBJECT(
                'item_title', COALESCE(NEW.title, 'Unknown'),
                'item_type', NEW.type,
                'item_category', COALESCE(NEW.category, 'Unknown'),
                'reporter_email', COALESCE(NEW.reporter_email, 'Unknown'),
                'reporter_name', COALESCE(NEW.reporter_name, 'Unknown'),
                'old_status', OLD.status,
                'new_status', NEW.status,
                'flagged_at', NOW()
            ) as metadata,
            NOW() as created_at,
            NOW() as updated_at
        FROM admin_roles ar
        WHERE ar.role IN ('super_admin', 'admin', 'staff');
        
        -- Increment fake_report_count for the reporter
        UPDATE users 
        SET fake_report_count = COALESCE(fake_report_count, 0) + 1
        WHERE email = NEW.reporter_email;
        
    END IF;
END$$

DELIMITER ;

-- Verify the trigger was created
SHOW TRIGGERS LIKE 'items';

-- Test query to check current fake report items (should be empty initially)
SELECT 
    id,
    title,
    status,
    reporter_name,
    reporter_email,
    created_at
FROM items 
WHERE status = 'Fake Report'
ORDER BY created_at DESC
LIMIT 10;

-- Check if there are any notifications for fake reports
SELECT 
    user_email,
    title,
    message,
    created_at
FROM notifications_web
WHERE title = 'Fake Report Flagged'
ORDER BY created_at DESC
LIMIT 10;
