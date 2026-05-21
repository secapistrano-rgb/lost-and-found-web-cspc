-- Simple Step-by-Step Notification Test
-- Run these commands one at a time to test each trigger

-- STEP 1: Check current notification count
SELECT COUNT(*) as 'Total Notifications Before Test' FROM notifications_web;

-- STEP 2: Test Match Notification
-- This simulates the Python matching service creating a match
INSERT INTO matches (item_id, user_id, score, status, created_at) 
VALUES (1, 1, 92.5, 'pending', NOW());

-- STEP 3: Verify the match notification was created
SELECT 'Match Notification:' as 'Type', id, title, message, is_read, created_at 
FROM notifications_web 
WHERE title LIKE '%Match%' 
ORDER BY id DESC LIMIT 1;

-- STEP 4: Check how many admins received the notification
SELECT COUNT(*) as 'Admins Notified' FROM notifications_web 
WHERE title LIKE '%Match%' AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MINUTE);

-- STEP 5: View the notification as it would appear to admins
SELECT 
    CONCAT('ID: ', id) as 'Notification',
    title as 'Title',
    LEFT(message, 80) as 'Message Preview',
    admin_id as 'Admin ID',
    is_read as 'Read Status'
FROM notifications_web 
WHERE title LIKE '%Match%' 
ORDER BY id DESC LIMIT 5;
