-- ============================================
-- EMAIL REPLY NOTIFICATION SYSTEM SETUP
-- ============================================
-- This creates the infrastructure to notify admins when users reply to emails

-- Create email_threads table for tracking sent emails
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

-- Success message
SELECT 'Email reply notification system tables created successfully!' as status;

-- Verify table creation
SHOW TABLES LIKE 'email_threads';

-- Display table structure
DESCRIBE email_threads;
