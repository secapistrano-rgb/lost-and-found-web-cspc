-- Test Script for Notification System
-- This script will create test records to trigger notifications

-- First, let's check current notifications count
SELECT COUNT(*) as 'Current Notifications' FROM notifications_web;

-- Test 1: Create a test match (simulates Python matching service)
INSERT INTO matches (item_id, user_id, score, status, created_at) 
VALUES (1, 1, 85.5, 'pending', NOW());

-- Wait a moment and check notifications
SELECT 'After Match Insert:' as 'Test';
SELECT id, title, message, created_at FROM notifications_web ORDER BY id DESC LIMIT 3;

-- Test 2: Create a test item (simulates new lost item report)
INSERT INTO items (user_name, user_email, item_name, item_description, category, location, date_lost, status, created_at)
VALUES ('Test User', 'testuser@example.com', 'Test Laptop', 'Black Dell laptop with stickers', 'Electronics', 'Library 3rd Floor', NOW(), 'pending', NOW());

-- Check notifications again
SELECT 'After Item Insert:' as 'Test';
SELECT id, title, message, created_at FROM notifications_web ORDER BY id DESC LIMIT 3;

-- Test 3: Create a test claim
INSERT INTO claims (item_id, user_name, user_email, claim_type, description, status, created_at)
VALUES (1, 'Jane Smith', 'jane.smith@example.com', 'ownership', 'This is my laptop, I can provide proof of purchase', 'pending', NOW());

-- Check notifications again
SELECT 'After Claim Insert:' as 'Test';
SELECT id, title, message, created_at FROM notifications_web ORDER BY id DESC LIMIT 3;

-- Test 4: Create a test feedback
INSERT INTO feedback (user_name, user_email, rating, message, created_at)
VALUES ('John Doe', 'john.doe@example.com', 5, 'Excellent service! Found my lost item quickly.', NOW());

-- Check notifications again
SELECT 'After Feedback Insert:' as 'Test';
SELECT id, title, message, created_at FROM notifications_web ORDER BY id DESC LIMIT 3;

-- Test 5: Create a test support ticket
INSERT INTO support_tickets (user_name, user_email, subject, message, priority, category, status, created_at)
VALUES ('Sarah Johnson', 'sarah.j@example.com', 'Cannot upload images', 'I am unable to upload photos when reporting lost items', 'high', 'technical', 'open', NOW());

-- Check final notifications
SELECT 'After Support Ticket Insert:' as 'Test';
SELECT id, title, message, created_at FROM notifications_web ORDER BY id DESC LIMIT 3;

-- Summary: Show all new notifications
SELECT '=== ALL TEST NOTIFICATIONS ===' as 'Summary';
SELECT id, title, LEFT(message, 50) as 'message_preview', created_at 
FROM notifications_web 
ORDER BY id DESC 
LIMIT 10;

-- Cleanup (optional - uncomment if you want to remove test data)
-- DELETE FROM notifications_web WHERE created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE);
-- DELETE FROM support_tickets WHERE user_name = 'Sarah Johnson';
-- DELETE FROM feedback WHERE user_name = 'John Doe';
-- DELETE FROM claims WHERE user_name = 'Jane Smith';
-- DELETE FROM items WHERE user_name = 'Test User';
-- DELETE FROM matches WHERE created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE);
