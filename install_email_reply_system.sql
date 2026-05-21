-- ============================================
-- QUICK INSTALLATION FOR EMAIL REPLY NOTIFICATIONS
-- ============================================
-- Run this single file to set up everything

-- Create email_threads table
DROP TABLE IF EXISTS `email_threads`;

CREATE TABLE `email_threads` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `message_id` VARCHAR(255) NOT NULL COMMENT 'Email Message-ID header for tracking replies',
    `thread_id` VARCHAR(255) NULL COMMENT 'Email thread ID for grouping conversations',
    `to_email` VARCHAR(255) NOT NULL COMMENT 'Recipient email address',
    `from_email` VARCHAR(255) NOT NULL COMMENT 'Sender email address',
    `subject` VARCHAR(255) NOT NULL,
    `entity_type` VARCHAR(50) NULL COMMENT 'item, claim, match, etc.',
    `entity_id` VARCHAR(50) NULL,
    `has_reply` TINYINT(1) DEFAULT 0 COMMENT '1 if user has replied to this email',
    `last_reply_at` DATETIME NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_message_id` (`message_id`),
    KEY `idx_thread_id` (`thread_id`),
    KEY `idx_to_email` (`to_email`),
    KEY `idx_entity` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Success!
SELECT '✅ Email reply notification system installed successfully!' as status;

-- Show created table
SHOW TABLES LIKE 'email_threads';

-- Display structure
DESCRIBE email_threads;

-- Ready to use!
SELECT CONCAT(
    '\n🎉 SETUP COMPLETE!\n\n',
    'Next Steps:\n',
    '1. Add webhook route in app/Config/Routes.php\n',
    '2. Configure email service webhook URL\n',
    '3. Update email sending code to use EmailTracker\n\n',
    'When users reply to emails, admins will be notified!\n',
    'Notification will include Gmail link to view and respond.'
) as next_steps;
