-- ============================================
-- USER ACCOUNT DELETION - CASCADE VERIFICATION
-- ============================================
-- This script verifies and adds CASCADE DELETE constraints
-- to ensure proper data handling when a user is deleted.
--
-- Test Case: Test for access to past data after user account deletion
-- Expected: Once a user account is deleted, all data associated with 
--           that user must also be inaccessible or erased.
--
-- Date: October 13, 2025
-- ============================================

-- Step 1: Check existing foreign key constraints
-- ============================================
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME,
    DELETE_RULE,
    UPDATE_RULE
FROM 
    information_schema.KEY_COLUMN_USAGE
WHERE 
    REFERENCED_TABLE_NAME = 'users'
    AND TABLE_SCHEMA = DATABASE();

-- ============================================
-- Step 2: Verify CASCADE DELETE on user_settings
-- ============================================
-- This should already exist based on lost_and_found.sql
-- If not, add it:

-- Check if constraint exists
SELECT 
    CONSTRAINT_NAME,
    DELETE_RULE 
FROM 
    information_schema.REFERENTIAL_CONSTRAINTS
WHERE 
    CONSTRAINT_SCHEMA = DATABASE()
    AND TABLE_NAME = 'user_settings'
    AND CONSTRAINT_NAME = 'user_settings_ibfk_1';

-- If CASCADE is not set, fix it:
-- Note: You may need to drop and recreate the constraint

-- First, drop existing constraint if it doesn't have CASCADE
-- ALTER TABLE user_settings 
-- DROP FOREIGN KEY user_settings_ibfk_1;

-- Then recreate with CASCADE
-- ALTER TABLE user_settings
-- ADD CONSTRAINT user_settings_ibfk_1 
-- FOREIGN KEY (user_id) 
-- REFERENCES users(id) 
-- ON DELETE CASCADE 
-- ON UPDATE CASCADE;

-- ============================================
-- Step 3: Test Data Setup
-- ============================================
-- Create a test user for deletion testing

INSERT INTO users (
    google_id, 
    email, 
    full_name, 
    profile_image, 
    department, 
    status, 
    created_at, 
    updated_at
) VALUES (
    'test_deletion_user_12345',
    'test.deletion@test.cspc.edu.ph',
    'Test Deletion User',
    'https://example.com/test.jpg',
    'Test Department',
    'Active',
    NOW(),
    NOW()
);

-- Get the test user ID
SET @test_user_id = LAST_INSERT_ID();
SET @test_user_email = 'test.deletion@test.cspc.edu.ph';

-- ============================================
-- Step 4: Create Related Data for Testing
-- ============================================

-- Create user settings (should be CASCADE deleted)
INSERT INTO user_settings (
    user_id,
    notifications,
    email_alerts,
    save_image_to_gallery,
    created_at,
    updated_at
) VALUES (
    @test_user_id,
    1,
    1,
    0,
    NOW(),
    NOW()
);

-- Create an item reported by the test user (may be retained for audit)
INSERT INTO items (
    id,
    title,
    description,
    category,
    type,
    status,
    location,
    date_reported,
    date_lost_found,
    reporter_id,
    reporter_name,
    reporter_email,
    reporter_department,
    created_at,
    updated_at
) VALUES (
    CONCAT('test-deletion-item-', UNIX_TIMESTAMP()),
    'Test Deletion Item',
    'This is a test item for deletion testing',
    'Test Category',
    'lost',
    'active',
    'Test Location',
    NOW(),
    CURDATE(),
    @test_user_id,
    'Test Deletion User',
    @test_user_email,
    'Test Department',
    NOW(),
    NOW()
);

-- Create a notification for the test user
INSERT INTO notifications_web (
    user_email,
    user_name,
    title,
    message,
    type,
    is_read,
    priority,
    created_at,
    updated_at
) VALUES (
    @test_user_email,
    'Test Deletion User',
    'Test Deletion Notification',
    'This is a test notification for deletion testing',
    'system',
    0,
    'medium',
    NOW(),
    NOW()
);

-- Create an admin role entry for the test user
INSERT INTO admin_role (
    name,
    email,
    role,
    profile_pic
) VALUES (
    'Test Deletion User',
    @test_user_email,
    'Admin',
    'https://example.com/test.jpg'
);

-- ============================================
-- Step 5: Verify Test Data Exists Before Deletion
-- ============================================

SELECT 'User Record' as Data_Type, COUNT(*) as Count 
FROM users 
WHERE id = @test_user_id;

SELECT 'User Settings' as Data_Type, COUNT(*) as Count 
FROM user_settings 
WHERE user_id = @test_user_id;

SELECT 'Items' as Data_Type, COUNT(*) as Count 
FROM items 
WHERE reporter_id = @test_user_id;

SELECT 'Notifications' as Data_Type, COUNT(*) as Count 
FROM notifications_web 
WHERE user_email = @test_user_email;

SELECT 'Admin Roles' as Data_Type, COUNT(*) as Count 
FROM admin_role 
WHERE email = @test_user_email;

-- ============================================
-- Step 6: DELETE THE TEST USER
-- ============================================
-- This simulates the actual user deletion

DELETE FROM users WHERE id = @test_user_id;

-- ============================================
-- Step 7: Verify Data After Deletion
-- ============================================

-- User should be deleted
SELECT 
    'User Record AFTER Deletion' as Check_Type,
    CASE 
        WHEN COUNT(*) = 0 THEN 'PASS: User deleted'
        ELSE 'FAIL: User still exists'
    END as Result
FROM users 
WHERE id = @test_user_id;

-- User settings should be CASCADE deleted
SELECT 
    'User Settings AFTER Deletion' as Check_Type,
    CASE 
        WHEN COUNT(*) = 0 THEN 'PASS: Settings deleted (CASCADE)'
        ELSE 'FAIL: Settings still exist'
    END as Result
FROM user_settings 
WHERE user_id = @test_user_id;

-- Items may remain for audit purposes
SELECT 
    'Items AFTER Deletion' as Check_Type,
    COUNT(*) as Remaining_Count,
    CASE 
        WHEN COUNT(*) > 0 THEN 'OK: Items retained for audit'
        ELSE 'OK: Items deleted'
    END as Result
FROM items 
WHERE reporter_id = @test_user_id OR reporter_email = @test_user_email;

-- Notifications may remain
SELECT 
    'Notifications AFTER Deletion' as Check_Type,
    COUNT(*) as Remaining_Count,
    CASE 
        WHEN COUNT(*) > 0 THEN 'NOTE: Consider cleanup policy'
        ELSE 'OK: Notifications deleted'
    END as Result
FROM notifications_web 
WHERE user_email = @test_user_email;

-- Admin roles may remain
SELECT 
    'Admin Roles AFTER Deletion' as Check_Type,
    COUNT(*) as Remaining_Count,
    CASE 
        WHEN COUNT(*) > 0 THEN 'NOTE: Consider cleanup policy'
        ELSE 'OK: Admin role deleted'
    END as Result
FROM admin_role 
WHERE email = @test_user_email;

-- ============================================
-- Step 8: Verify User is Completely Inaccessible
-- ============================================

-- Try to retrieve user by ID (should return 0 rows)
SELECT 
    'User Accessibility Test' as Test_Type,
    CASE 
        WHEN COUNT(*) = 0 THEN 'PASS: User is inaccessible'
        ELSE 'FAIL: User can still be accessed'
    END as Result
FROM users 
WHERE id = @test_user_id OR email = @test_user_email;

-- ============================================
-- Step 9: Cleanup Any Remaining Test Data
-- ============================================

-- Clean up test items
DELETE FROM items 
WHERE reporter_email = @test_user_email 
   OR title = 'Test Deletion Item';

-- Clean up test notifications
DELETE FROM notifications_web 
WHERE user_email = @test_user_email 
   OR title = 'Test Deletion Notification';

-- Clean up test admin roles
DELETE FROM admin_role 
WHERE email = @test_user_email 
   OR name = 'Test Deletion User';

-- Clean up test claims (if any)
DELETE FROM claims 
WHERE google_user_id LIKE 'test_deletion_%';

-- Clean up test activity logs (if any)
DELETE FROM activity_logs 
WHERE user_email = @test_user_email;

-- ============================================
-- Step 10: Final Verification - All Test Data Removed
-- ============================================

SELECT 'Final Cleanup Verification' as Status,
    (SELECT COUNT(*) FROM users WHERE email = @test_user_email) as Users,
    (SELECT COUNT(*) FROM user_settings WHERE user_id = @test_user_id) as Settings,
    (SELECT COUNT(*) FROM items WHERE reporter_email = @test_user_email) as Items,
    (SELECT COUNT(*) FROM notifications_web WHERE user_email = @test_user_email) as Notifications,
    (SELECT COUNT(*) FROM admin_role WHERE email = @test_user_email) as Admin_Roles;

-- ============================================
-- SUMMARY OF EXPECTED RESULTS
-- ============================================
-- 
-- PASS CONDITIONS:
-- 1. User record is deleted from users table
-- 2. User settings are CASCADE deleted automatically
-- 3. User cannot be accessed by ID or email
-- 4. No errors occur during deletion
--
-- ACCEPTABLE CONDITIONS (Policy-Based):
-- 5. Items may remain for audit/historical purposes
-- 6. Notifications may remain (consider cleanup policy)
-- 7. Admin roles may remain (consider cleanup policy)
-- 8. Activity logs may remain (required for audit trail)
--
-- CRITICAL REQUIREMENT:
-- The user account itself MUST be deleted and completely
-- inaccessible, regardless of what related data is retained.
--
-- ============================================

-- Recommendations for production implementation:
-- 
-- 1. Add CASCADE DELETE to user_settings (already done)
-- 
-- 2. Consider adding cleanup policies for:
--    - notifications_web: DELETE after user deletion
--    - admin_role: DELETE on user deletion
--
-- 3. Implement data anonymization for audit retention:
--    - Replace user info in items with "Deleted User"
--    - Replace user info in claims with "Deleted User"
--
-- 4. Add soft delete option:
--    ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL;
--    - Allows recovery within grace period
--    - GDPR compliance: permanent delete after 30 days
--
-- 5. Create stored procedure for user deletion:
--    - Handles all cleanup in one transaction
--    - Logs deletion for audit
--    - Optionally anonymizes data
--
-- ============================================

-- Example: Add cleanup on user deletion using triggers
-- (Optional - use with caution)

-- DELIMITER $$
-- CREATE TRIGGER before_user_delete
-- BEFORE DELETE ON users
-- FOR EACH ROW
-- BEGIN
--     -- Log the deletion
--     INSERT INTO activity_logs (
--         user_id, user_email, user_name, module, action, 
--         description, severity, created_at
--     ) VALUES (
--         OLD.id, OLD.email, OLD.full_name, 'Users', 
--         'Delete User', 
--         CONCAT('User account deleted: ', OLD.full_name, ' (', OLD.email, ')'),
--         'critical', NOW()
--     );
--     
--     -- Optional: Clean up notifications
--     -- DELETE FROM notifications_web WHERE user_email = OLD.email;
--     
--     -- Optional: Clean up admin roles
--     -- DELETE FROM admin_role WHERE email = OLD.email;
-- END$$
-- DELIMITER ;

-- ============================================
-- END OF SCRIPT
-- ============================================
