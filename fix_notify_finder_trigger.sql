-- ============================================
-- FIX: Drop and recreate notify_finder_on_claim trigger
-- This trigger was referencing c.claimant_email which doesn't exist
-- The correct column is c.claimant_id
-- ============================================

USE lost_and_found;

-- Drop the old trigger
DROP TRIGGER IF EXISTS notify_finder_on_claim;

-- Recreate the correct trigger
DELIMITER $$

CREATE TRIGGER `notify_finder_on_claim` 
AFTER UPDATE ON `items` 
FOR EACH ROW 
BEGIN
    DECLARE notification_id VARCHAR(255);
    DECLARE notification_title VARCHAR(255);
    DECLARE notification_message TEXT;
    DECLARE notification_data JSON;
    DECLARE finder_email VARCHAR(255);
    DECLARE claimant_name VARCHAR(255);
    DECLARE claimant_email VARCHAR(255);
    
    
    
    IF (OLD.status != NEW.status) 
       AND (NEW.status IN ('claimed', 'returned')) 
       AND (NEW.type = 'found') THEN
        
        
        SET finder_email = NEW.reporter_email;
        
        
        SELECT u.full_name, c.claimant_id
        INTO claimant_name, claimant_email
        FROM claims c
        LEFT JOIN users u ON c.claimant_id = u.email
        WHERE c.item_id = NEW.id 
          AND c.status = 'approved'
        ORDER BY c.updated_at DESC
        LIMIT 1;
        
        
        IF claimant_email IS NULL THEN
            SELECT u.full_name, i.reporter_email
            INTO claimant_name, claimant_email
            FROM matches m
            INNER JOIN items i ON m.lost_item_id = i.id
            LEFT JOIN users u ON i.reporter_email = u.email
            WHERE m.found_item_id = NEW.id
              AND m.status IN ('accepted', 'resolved')
            ORDER BY m.updated_at DESC
            LIMIT 1;
        END IF;
        
        
        SET notification_id = CONCAT('finderClaim_', UNIX_TIMESTAMP(NOW()), '_', SUBSTRING(MD5(RAND()), 1, 9));
        
        
        SET notification_title = '🎉 Your Found Item Was Claimed!';
        
        
        SET notification_message = CONCAT(
            'Great news! The item you found and reported "',
            LEFT(NEW.title, 50),
            CASE 
                WHEN LENGTH(NEW.title) > 50 THEN '...' 
                ELSE '' 
            END,
            '" has been successfully claimed and returned to its owner',
            CASE 
                WHEN claimant_name IS NOT NULL THEN CONCAT(' (', claimant_name, ')')
                ELSE ''
            END,
            '. Thank you for being an honest finder! Your good deed helped reunite someone with their lost item. 🙏'
        );
        
        
        SET notification_data = JSON_OBJECT(
            'type', 'finderClaim',
            'itemId', NEW.id,
            'itemTitle', NEW.title,
            'itemType', NEW.type,
            'claimStatus', NEW.status,
            'claimantEmail', claimant_email,
            'claimantName', claimant_name,
            'dateReported', NEW.date_reported,
            'dateClaimed', NOW()
        );
        
        
        INSERT INTO notifications (
            id,
            user_email,
            type,
            title,
            message,
            data,
            is_read,
            created_at
        ) VALUES (
            notification_id,
            finder_email,
            'finderClaim',
            notification_title,
            notification_message,
            notification_data,
            0,
            NOW()
        );
    END IF;
END$$

DELIMITER ;

-- Verify the trigger was created
SHOW TRIGGERS WHERE `Table` = 'items' AND `Trigger` = 'notify_finder_on_claim';

SELECT 'Trigger fixed successfully! The notify_finder_on_claim trigger has been recreated without claimant_email reference.' AS status;
