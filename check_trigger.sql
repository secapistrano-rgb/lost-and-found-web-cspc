-- ============================================
-- DIAGNOSTIC: Check current trigger definition
-- Run this on VPS to see what the trigger actually looks like
-- ============================================

USE lost_and_found;

-- Show the current trigger definition
SHOW CREATE TRIGGER after_claim_status_update;

-- Alternative: Check all triggers on claims table
SELECT 
    TRIGGER_NAME,
    EVENT_MANIPULATION,
    EVENT_OBJECT_TABLE,
    ACTION_TIMING,
    ACTION_STATEMENT
FROM information_schema.TRIGGERS
WHERE EVENT_OBJECT_TABLE = 'claims'
AND TRIGGER_SCHEMA = 'lost_and_found';
