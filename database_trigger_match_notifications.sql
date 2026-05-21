-- ============================================
-- DATABASE TRIGGERS FOR ADMIN NOTIFICATIONS
-- ============================================
-- This file creates triggers to automatically notify admins when:
-- 1. New matches are found
-- 2. New items are reported (lost or found)
-- 3. New claims are submitted
-- 4. New feedback is received
-- 5. New support tickets are created

-- Drop existing triggers if they exist
DROP TRIGGER IF EXISTS after_match_insert_notification;
DROP TRIGGER IF EXISTS after_item_insert_notification;
DROP TRIGGER IF EXISTS after_claim_insert_notification;
DROP TRIGGER IF EXISTS after_feedback_insert_notification;
DROP TRIGGER IF EXISTS after_support_ticket_insert_notification;

DELIMITER $$

-- ============================================
-- 1. TRIGGER: Notify admins when new match is found
-- ============================================
CREATE TRIGGER after_match_insert_notification
AFTER INSERT ON matches
FOR EACH ROW
BEGIN
    INSERT INTO notifications_web (
        user_email,
        title,
        message,
        type,
        entity_type,
        entity_id,
        is_read,
        created_at,
        updated_at
    )
    SELECT 
        email,
        'New Match Found',
        CONCAT(
            'A new potential match has been found between lost item ''',
            COALESCE(NEW.lost_item_title, 'Unknown'),
            ''' and found item ''',
            COALESCE(NEW.found_item_title, 'Unknown'),
            ''' with a match score of ',
            ROUND(NEW.score * 100, 2),
            '%. Please review this match and take appropriate action.'
        ),
        'match',
        'match',
        NEW.id,
        0,
        NOW(),
        NOW()
    FROM admin_role
    WHERE role IN ('super_admin', 'admin', 'staff');
END$$

-- ============================================
-- 2. TRIGGER: Notify admins when new item is reported
-- ============================================
CREATE TRIGGER after_item_insert_notification
AFTER INSERT ON items
FOR EACH ROW
BEGIN
    INSERT INTO notifications_web (
        user_email,
        title,
        message,
        type,
        entity_type,
        entity_id,
        is_read,
        created_at,
        updated_at
    )
    SELECT 
        email,
        CONCAT('New ', UPPER(NEW.status), ' Item Reported'),
        CONCAT(
            'A new ',
            NEW.status,
            ' item has been reported: ''',
            COALESCE(NEW.title, 'Untitled'),
            '''. Reporter: ',
            COALESCE(NEW.reporter_name, 'Unknown'),
            ' (',
            COALESCE(NEW.reporter_email, 'No email'),
            '). Please review this item and take appropriate action.'
        ),
        'item',
        'item',
        NEW.id,
        0,
        NOW(),
        NOW()
    FROM admin_role
    WHERE role IN ('super_admin', 'admin', 'staff');
END$$

-- ============================================
-- 3. TRIGGER: Notify admins when new claim is submitted
-- ============================================
CREATE TRIGGER after_claim_insert_notification
AFTER INSERT ON claims
FOR EACH ROW
BEGIN
    INSERT INTO notifications_web (
        user_email,
        title,
        message,
        type,
        entity_type,
        entity_id,
        is_read,
        created_at,
        updated_at
    )
    SELECT 
        ar.email,
        'New Claim Submitted',
        CONCAT(
            'A new claim request has been submitted (Claim ID: ',
            NEW.id,
            ') for ',
            CASE 
                WHEN NEW.match_id IS NOT NULL THEN CONCAT('match ID: ', NEW.match_id)
                WHEN NEW.item_id IS NOT NULL THEN CONCAT('item ID: ', NEW.item_id)
                ELSE 'an item'
            END,
            '. Claim type: ',
            COALESCE(NEW.claim_type, 'ownership'),
            '. Please review the claim details and verify the claimant''s information to process this request.'
        ),
        'claim',
        'claim',
        NEW.id,
        0,
        NOW(),
        NOW()
    FROM admin_role ar
    WHERE ar.role IN ('super_admin', 'admin', 'staff');
END$$

-- ============================================
-- 4. TRIGGER: Notify admins when new feedback is submitted
-- ============================================
CREATE TRIGGER after_feedback_insert_notification
AFTER INSERT ON feedback
FOR EACH ROW
BEGIN
    INSERT INTO notifications_web (
        user_email,
        title,
        message,
        type,
        entity_type,
        entity_id,
        is_read,
        created_at,
        updated_at
    )
    SELECT 
        ar.email,
        'New Feedback Received',
        CONCAT(
            'New user feedback has been submitted by ',
            COALESCE(NEW.user_name, 'Anonymous'),
            ' (',
            COALESCE(NEW.user_email, 'No email'),
            ') ',
            CASE 
                WHEN NEW.rating IS NOT NULL THEN CONCAT('with a rating of ', NEW.rating, ' out of 5 stars')
                ELSE CONCAT('as a ', NEW.type)
            END,
            '. Please review the feedback and respond if necessary to improve user experience.'
        ),
        'system',
        'feedback',
        NEW.id,
        0,
        NOW(),
        NOW()
    FROM admin_role ar
    WHERE ar.role IN ('super_admin', 'admin', 'staff');
END$$

-- ============================================
-- 5. TRIGGER: Notify admins when new support ticket is created
-- ============================================
CREATE TRIGGER after_support_ticket_insert_notification
AFTER INSERT ON support_tickets
FOR EACH ROW
BEGIN
    INSERT INTO notifications_web (
        user_email,
        title,
        message,
        type,
        entity_type,
        entity_id,
        is_read,
        created_at,
        updated_at
    )
    SELECT 
        ar.email,
        'New Support Ticket Created',
        CONCAT(
            'A new support ticket has been created by ',
            COALESCE(NEW.user_name, 'Unknown'),
            ' (',
            COALESCE(NEW.user_email, 'No email'),
            ') with the subject: "',
            COALESCE(NEW.subject, 'No subject'),
            '", category: ',
            COALESCE(UPPER(NEW.category), 'OTHER'),
            ', and priority level: ',
            COALESCE(UPPER(NEW.priority), 'MEDIUM'),
            '. Please review and respond to this ticket promptly to assist the user.'
        ),
        'system',
        'support_ticket',
        NEW.id,
        0,
        NOW(),
        NOW()
    FROM admin_role ar
    WHERE ar.role IN ('super_admin', 'admin', 'staff');
END$$

DELIMITER ;

-- ============================================
-- VERIFICATION QUERY
-- ============================================
-- After running this script, verify triggers were created:
-- SHOW TRIGGERS FROM lost_and_found;
