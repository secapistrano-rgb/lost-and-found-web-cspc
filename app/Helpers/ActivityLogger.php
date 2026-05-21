<?php

namespace App\Helpers;

use App\Models\ActivityLogModel;

class ActivityLogger
{
    private static $instance = null;
    private $activityLogModel;
    private $session;
    private $request;

    private function __construct()
    {
        $this->activityLogModel = new ActivityLogModel();
        $this->session = session();
        $this->request = service('request');
    }

    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Log an activity
     */
    public function log($module, $action, $description = null, $options = [])
    {
        // Get current user info from session
        $userData = $this->getCurrentUserData();
        
        $logData = [
            'user_id' => $userData['user_id'],
            'user_name' => $userData['user_name'],
            'user_email' => $userData['user_email'],
            'user_role' => $userData['user_role'],
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'severity' => $options['severity'] ?? 'low',
            'entity_type' => $options['entity_type'] ?? null,
            'entity_id' => $options['entity_id'] ?? null,
            'old_values' => $options['old_values'] ?? null,
            'new_values' => $options['new_values'] ?? null,
        ];

        return $this->activityLogModel->logActivity($logData);
    }

    /**
     * Log user login
     */
    public function logLogin($userId, $userEmail, $userName = null)
    {
        return $this->log(
            'Authentication',
            'Login',
            "User {$userEmail} logged in successfully",
            [
                'severity' => 'low',
                'entity_type' => 'user',
                'entity_id' => $userId
            ]
        );
    }

    /**
     * Log user logout
     */
    public function logLogout($userId, $userEmail, $userName = null)
    {
        return $this->log(
            'Authentication',
            'Logout',
            "User {$userEmail} logged out",
            [
                'severity' => 'low',
                'entity_type' => 'user',
                'entity_id' => $userId
            ]
        );
    }

    /**
     * Log item creation
     */
    public function logItemCreated($itemId, $itemName, $oldValues = null, $newValues = null)
    {
        return $this->log(
            'Items',
            'Create',
            "New item '{$itemName}' was created",
            [
                'severity' => 'medium',
                'entity_type' => 'item',
                'entity_id' => $itemId,
                'old_values' => $oldValues,
                'new_values' => $newValues
            ]
        );
    }

    /**
     * Log item update
     */
    public function logItemUpdated($itemId, $itemName, $oldValues = null, $newValues = null)
    {
        return $this->log(
            'Items',
            'Update',
            "Item '{$itemName}' was updated",
            [
                'severity' => 'medium',
                'entity_type' => 'item',
                'entity_id' => $itemId,
                'old_values' => $oldValues,
                'new_values' => $newValues
            ]
        );
    }

    /**
     * Log item deletion
     */
    public function logItemDeleted($itemId, $itemName, $oldValues = null)
    {
        return $this->log(
            'Items',
            'Delete',
            "Item '{$itemName}' was deleted",
            [
                'severity' => 'high',
                'entity_type' => 'item',
                'entity_id' => $itemId,
                'old_values' => $oldValues
            ]
        );
    }

    /**
     * Log claim creation
     */
    public function logClaimCreated($claimId, $itemName, $oldValues = null, $newValues = null)
    {
        return $this->log(
            'Claims',
            'Create',
            "New claim was created for item '{$itemName}'",
            [
                'severity' => 'medium',
                'entity_type' => 'claim',
                'entity_id' => $claimId,
                'old_values' => $oldValues,
                'new_values' => $newValues
            ]
        );
    }

    /**
     * Log claim status change
     */
    public function logClaimStatusChanged($claimId, $itemName, $oldStatus, $newStatus, $oldValues = null, $newValues = null)
    {
        return $this->log(
            'Claims',
            'Status Change',
            "Claim for item '{$itemName}' status changed from '{$oldStatus}' to '{$newStatus}'",
            [
                'severity' => 'high',
                'entity_type' => 'claim',
                'entity_id' => $claimId,
                'old_values' => $oldValues,
                'new_values' => $newValues
            ]
        );
    }

    /**
     * Log user management actions
     */
    public function logUserAction($action, $targetUserId, $targetUserEmail, $description, $oldValues = null, $newValues = null)
    {
        $severity = 'medium';
        if (in_array($action, ['Suspend', 'Delete', 'Role Change'])) {
            $severity = 'high';
        }

        return $this->log(
            'User Management',
            $action,
            $description,
            [
                'severity' => $severity,
                'entity_type' => 'user',
                'entity_id' => $targetUserId,
                'old_values' => $oldValues,
                'new_values' => $newValues
            ]
        );
    }

    /**
     * Log email actions
     */
    public function logEmailSent($emailType, $recipient, $subject, $templateId = null)
    {
        return $this->log(
            'Email',
            'Send',
            "Email '{$subject}' sent to {$recipient}",
            [
                'severity' => 'low',
                'entity_type' => 'email',
                'entity_id' => $templateId,
                'new_values' => [
                    'type' => $emailType,
                    'recipient' => $recipient,
                    'subject' => $subject
                ]
            ]
        );
    }

    /**
     * Log security events
     */
    public function logSecurityEvent($event, $description, $severity = 'high')
    {
        return $this->log(
            'Security',
            $event,
            $description,
            [
                'severity' => $severity
            ]
        );
    }

    /**
     * Log system events
     */
    public function logSystemEvent($event, $description, $severity = 'medium')
    {
        return $this->log(
            'System',
            $event,
            $description,
            [
                'severity' => $severity
            ]
        );
    }

    /**
     * Log report generation
     */
    public function logReportGenerated($reportType, $description, $parameters = null)
    {
        return $this->log(
            'Reports',
            'Generate',
            $description,
            [
                'severity' => 'low',
                'entity_type' => 'report',
                'new_values' => [
                    'type' => $reportType,
                    'parameters' => $parameters
                ]
            ]
        );
    }

    /**
     * Log user login activity
     */
    public function logUserLogin($userId, $email, $userType = 'user')
    {
        return $this->log(
            'Auth',
            'Login',
            "User logged in: {$email}",
            [
                'severity' => 'low',
                'entity_type' => 'user',
                'entity_id' => $userId
            ]
        );
    }

    /**
     * Log role creation
     */
    public function logRoleCreated($roleId, $roleName, $roleEmail, $roleType, $createdBy)
    {
        $this->log(
            'Role Management',
            'Role Created',
            "New role created: {$roleName} ({$roleEmail}) as {$roleType}",
            [
                'severity' => 'medium',
                'entity_type' => 'role',
                'entity_id' => $roleId,
                'new_values' => [
                    'role_name' => $roleName,
                    'role_email' => $roleEmail,
                    'role_type' => $roleType,
                    'created_by' => $createdBy
                ]
            ]
        );
    }

    /**
     * Log role update
     */
    public function logRoleUpdated($roleId, $roleName, $roleEmail, $updatedBy, $oldValues = null, $newValues = null)
    {
        $this->log(
            'Role Management',
            'Role Updated',
            "Role updated: {$roleName} ({$roleEmail})",
            [
                'severity' => 'medium',
                'entity_type' => 'role',
                'entity_id' => $roleId,
                'old_values' => $oldValues,
                'new_values' => $newValues ?? [
                    'role_name' => $roleName,
                    'role_email' => $roleEmail,
                    'updated_by' => $updatedBy
                ]
            ]
        );
    }

    /**
     * Log role deletion
     */
    public function logRoleDeleted($roleId, $roleName, $roleEmail, $deletedBy)
    {
        $this->log(
            'Role Management',
            'Role Deleted',
            "Role deleted: {$roleName} ({$roleEmail})",
            [
                'severity' => 'high',
                'entity_type' => 'role',
                'entity_id' => $roleId,
                'old_values' => [
                    'role_name' => $roleName,
                    'role_email' => $roleEmail,
                    'deleted_by' => $deletedBy
                ]
            ]
        );
    }

    /**
     * Get current user data for logging
     */
    private function getCurrentUserData()
    {
        $userData = [
            'user_id' => null,
            'user_name' => 'System',
            'user_email' => 'system@localhost',
            'user_role' => 'system'
        ];

        if ($this->session->get('logged_in')) {
            $userData = [
                'user_id' => $this->session->get('user_id'),
                'user_name' => $this->session->get('name') ?? 'Unknown User',
                'user_email' => $this->session->get('email') ?? 'unknown@localhost',
                'user_role' => $this->session->get('user_role') ?? 'user'
            ];
        }

        return $userData;
    }

    /**
     * Log feedback creation
     */
    public function logFeedbackCreated($feedbackId, $userEmail, $type, $createdBy)
    {
        return $this->log(
            'Feedback',
            'Create Feedback',
            "New feedback created by {$userEmail} (Type: {$type})",
            [
                'severity' => 'medium',
                'entity_type' => 'feedback',
                'entity_id' => $feedbackId,
                'new_values' => [
                    'feedback_id' => $feedbackId,
                    'user_email' => $userEmail,
                    'type' => $type,
                    'created_by' => $createdBy
                ]
            ]
        );
    }

    /**
     * Log feedback deletion
     */
    public function logFeedbackDeleted($feedbackId, $userEmail, $deletedBy)
    {
        return $this->log(
            'Feedback',
            'Delete Feedback',
            "Feedback deleted from {$userEmail}",
            [
                'severity' => 'high',
                'entity_type' => 'feedback',
                'entity_id' => $feedbackId,
                'new_values' => [
                    'feedback_id' => $feedbackId,
                    'user_email' => $userEmail,
                    'deleted_by' => $deletedBy
                ]
            ]
        );
    }

    /**
     * Log feedback status change
     */
    public function logFeedbackStatusChanged($feedbackId, $userEmail, $oldStatus, $newStatus, $changedBy)
    {
        return $this->log(
            'Feedback',
            'Update Feedback Status',
            "Feedback from {$userEmail} status changed from {$oldStatus} to {$newStatus}",
            [
                'severity' => 'medium',
                'entity_type' => 'feedback',
                'entity_id' => $feedbackId,
                'new_values' => [
                    'feedback_id' => $feedbackId,
                    'user_email' => $userEmail,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changed_by' => $changedBy
                ]
            ]
        );
    }

    /**
     * Log feedback response added
     */
    public function logFeedbackResponseAdded($feedbackId, $userEmail, $respondedBy)
    {
        return $this->log(
            'Feedback',
            'Add Feedback Response',
            "Response added to feedback from {$userEmail}",
            [
                'severity' => 'medium',
                'entity_type' => 'feedback',
                'entity_id' => $feedbackId,
                'new_values' => [
                    'feedback_id' => $feedbackId,
                    'user_email' => $userEmail,
                    'responded_by' => $respondedBy
                ]
            ]
        );
    }

    /**
     * Log match creation
     */
    public function logMatchCreated($matchId, $lostItemTitle, $foundItemTitle, $score, $createdBy)
    {
        return $this->log(
            'Matches',
            'Create Match',
            "Match created between '{$lostItemTitle}' and '{$foundItemTitle}' with score {$score}",
            [
                'severity' => 'medium',
                'entity_type' => 'match',
                'entity_id' => $matchId,
                'new_values' => [
                    'match_id' => $matchId,
                    'lost_item_title' => $lostItemTitle,
                    'found_item_title' => $foundItemTitle,
                    'score' => $score,
                    'created_by' => $createdBy
                ]
            ]
        );
    }
}

// Helper function for easy access
if (!function_exists('activity_logger')) {
    function activity_logger()
    {
        return \App\Helpers\ActivityLogger::getInstance();
    }
}