<?php

namespace App\Helpers;

use App\Models\EmailThreadModel;

class EmailTracker
{
    /**
     * Generate a unique Message-ID for email tracking
     */
    public static function generateMessageId($entityType = null, $entityId = null)
    {
        $domain = parse_url(base_url(), PHP_URL_HOST) ?: 'lostfound.cspc.edu.ph';
        $uniqueId = uniqid('', true) . mt_rand();
        
        if ($entityType && $entityId) {
            return "<{$entityType}-{$entityId}-{$uniqueId}@{$domain}>";
        }
        
        return "<{$uniqueId}@{$domain}>";
    }

    /**
     * Track sent email for reply detection
     */
    public static function trackSentEmail($messageId, $toEmail, $subject, $entityType = null, $entityId = null)
    {
        $emailThreadModel = new EmailThreadModel();
        
        return $emailThreadModel->trackEmail([
            'message_id' => $messageId,
            'to_email' => $toEmail,
            'from_email' => 'noreply@lostfound.cspc.edu.ph',
            'subject' => $subject,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
        ]);
    }

    /**
     * Add tracking headers to email
     */
    public static function addTrackingHeaders(&$email, $entityType = null, $entityId = null)
    {
        $messageId = self::generateMessageId($entityType, $entityId);
        
        // Add Message-ID header
        $email->setHeader('Message-ID', $messageId);
        
        // Add Reply-To header
        $email->setReplyTo('lostfoundcspc@gmail.com', 'CSPC Lost and Found');
        
        return $messageId;
    }
}
