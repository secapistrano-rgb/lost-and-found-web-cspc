-- ============================================
-- FIX notifications_web TABLE STRUCTURE
-- ============================================
-- This fixes the ID column to be AUTO_INCREMENT so notifications get proper IDs
-- and cleans up broken notifications with id = 0

-- Step 1: Check current broken notifications
SELECT COUNT(*) as broken_notifications_count FROM notifications_web WHERE id = 0;

-- Step 2: Delete all broken notifications (id = 0)
-- These cannot be cleared/deleted properly anyway
DELETE FROM notifications_web WHERE id = 0;

-- Step 3: Add temporary sequential IDs to any remaining notifications
-- This ensures no duplicate IDs when we add AUTO_INCREMENT
SET @row_number = 0;
UPDATE notifications_web 
SET id = (@row_number:=@row_number + 1)
ORDER BY created_at ASC;

-- Step 4: Add a unique index to the id column first (if not exists)
-- This prepares the column to become a primary key
ALTER TABLE notifications_web ADD UNIQUE INDEX idx_notification_id (id);

-- Step 5: Now make ID column AUTO_INCREMENT with PRIMARY KEY
ALTER TABLE notifications_web 
DROP INDEX idx_notification_id,
MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT,
ADD PRIMARY KEY (id);

-- Step 6: Verify the fix
SHOW CREATE TABLE notifications_web;

-- Step 7: Check the notifications now have proper IDs
SELECT id, user_email, title, type, created_at 
FROM notifications_web 
ORDER BY created_at DESC 
LIMIT 10;

-- Success! Now:
-- 1. All existing notifications have unique IDs
-- 2. All future notifications will get unique auto-increment IDs
-- 3. Users can properly clear/delete notifications
-- 4. The triggers will work correctly
