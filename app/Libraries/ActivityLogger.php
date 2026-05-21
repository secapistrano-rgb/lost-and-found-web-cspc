<?php

namespace App\Libraries;

use App\Models\ActivityLogModel;

class ActivityLogger
{
    protected $activityLogModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLogModel();
    }

    public function log($module, $action, $description, $userId = null, $entityType = null, $entityId = null, $severity = 'medium', $oldValues = null, $newValues = null)
    {
        $data = [
            'user_id' => $userId,
            'user_name' => $this->getUserName($userId),
            'user_email' => $this->getUserEmail($userId),
            'user_role' => $this->getUserRole($userId),
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'severity' => $severity,
            'ip_address' => $this->getClientIP(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->activityLogModel->insert($data);
    }

    // User Management Logs
    public function logUserLogin($userId, $email, $userType = 'user')
    {
        return $this->log(
            'Auth',
            'login',
            "User logged in: {$email}",
            $userId,
            'user',
            $userId,
            'low'
        );
    }

    public function logUserLogout($userId, $email)
    {
        return $this->log(
            'Auth',
            'logout',
            "User logged out: {$email}",
            $userId,
            'user',
            $userId,
            'low'
        );
    }

    public function logUserCreated($userId, $email, $createdBy)
    {
        return $this->log(
            'User',
            'create',
            "New user created: {$email}",
            $createdBy,
            'user',
            $userId,
            'medium'
        );
    }

    public function logUserUpdated($userId, $email, $updatedBy)
    {
        return $this->log(
            'User',
            'update',
            "User updated: {$email}",
            $updatedBy,
            'user',
            $userId,
            'medium'
        );
    }

    public function logUserDeleted($userId, $email, $deletedBy)
    {
        return $this->log(
            'User',
            'delete',
            "User deleted: {$email}",
            $deletedBy,
            'user',
            $userId,
            'high'
        );
    }

    public function logUserSuspended($userId, $email, $suspensionType, $reason, $suspendedBy)
    {
        return $this->log(
            'User',
            'suspend',
            "User suspended ({$suspensionType}): {$email} - Reason: {$reason}",
            $suspendedBy,
            'user',
            $userId,
            'high'
        );
    }

    public function logUserUnsuspended($userId, $email, $unsuspendedBy)
    {
        return $this->log(
            'User',
            'unsuspend',
            "User unsuspended: {$email}",
            $unsuspendedBy,
            'user',
            $userId,
            'medium'
        );
    }

    // Item Management Logs
    public function logItemCreated($itemId, $title, $type, $createdBy)
    {
        return $this->log(
            'Item',
            'create',
            "New {$type} item created: {$title}",
            $createdBy,
            'item',
            $itemId,
            'medium'
        );
    }

    public function logItemUpdated($itemId, $title, $updatedBy)
    {
        return $this->log(
            'Item',
            'update',
            "Item updated: {$title}",
            $updatedBy,
            'item',
            $itemId,
            'medium'
        );
    }

    public function logItemStatusChanged($itemId, $title, $oldStatus, $newStatus, $changedBy)
    {
        return $this->log(
            'Item',
            'status_change',
            "Item status changed: {$title} from {$oldStatus} to {$newStatus}",
            $changedBy,
            'item',
            $itemId,
            'medium'
        );
    }

    public function logItemDeleted($itemId, $title, $deletedBy)
    {
        return $this->log(
            'Item',
            'delete',
            "Item deleted: {$title}",
            $deletedBy,
            'item',
            $itemId,
            'high'
        );
    }

    // Match Management Logs
    public function logMatchCreated($matchId, $lostItemTitle, $foundItemTitle, $score, $createdBy)
    {
        return $this->log(
            'Match',
            'create',
            "New match created between '{$lostItemTitle}' and '{$foundItemTitle}' with score {$score}",
            $createdBy,
            'match',
            $matchId,
            'medium'
        );
    }

    public function logMatchStatusChanged($matchId, $oldStatus, $newStatus, $changedBy)
    {
        return $this->log(
            'Match',
            'status_change',
            "Match status changed from {$oldStatus} to {$newStatus}",
            $changedBy,
            'match',
            $matchId,
            'medium'
        );
    }

    public function logMatchDeleted($matchId, $deletedBy)
    {
        return $this->log(
            'Match',
            'delete',
            "Match deleted (ID: {$matchId})",
            $deletedBy,
            'match',
            $matchId,
            'high'
        );
    }

    // Claim Management Logs
    public function logClaimCreated($claimId, $matchId, $claimantEmail, $createdBy)
    {
        return $this->log(
            'Claim',
            'create',
            "New claim created by {$claimantEmail} for match {$matchId}",
            $createdBy,
            'claim',
            $claimId,
            'medium'
        );
    }

    public function logClaimStatusChanged($claimId, $oldStatus, $newStatus, $changedBy)
    {
        return $this->log(
            'Claim',
            'status_change',
            "Claim status changed from {$oldStatus} to {$newStatus}",
            $changedBy,
            'claim',
            $claimId,
            'medium'
        );
    }

    public function logClaimDeleted($claimId, $claimantEmail, $deletedBy)
    {
        return $this->log(
            'Claim',
            'delete',
            "Claim deleted for {$claimantEmail}",
            $deletedBy,
            'claim',
            $claimId,
            'high'
        );
    }

    // Feedback Management Logs
    public function logFeedbackCreated($feedbackId, $userEmail, $type, $createdBy)
    {
        return $this->log(
            'Feedback',
            'create',
            "New feedback created by {$userEmail} (Type: {$type})",
            $createdBy,
            'feedback',
            $feedbackId,
            'medium'
        );
    }

    public function logFeedbackDeleted($feedbackId, $userEmail, $deletedBy)
    {
        return $this->log(
            'Feedback',
            'delete',
            "Feedback deleted from {$userEmail}",
            $deletedBy,
            'feedback',
            $feedbackId,
            'high'
        );
    }

    public function logFeedbackStatusChanged($feedbackId, $userEmail, $oldStatus, $newStatus, $changedBy)
    {
        return $this->log(
            'Feedback',
            'status_change',
            "Feedback from {$userEmail} status changed from {$oldStatus} to {$newStatus}",
            $changedBy,
            'feedback',
            $feedbackId,
            'medium'
        );
    }

    public function logFeedbackResponseAdded($feedbackId, $userEmail, $adminName, $respondedBy)
    {
        return $this->log(
            'Feedback',
            'response_added',
            "Response added to feedback from {$userEmail} by {$adminName}",
            $respondedBy,
            'feedback',
            $feedbackId,
            'medium'
        );
    }

    // Role Management Logs
    public function logRoleCreated($roleId, $roleName, $roleEmail, $roleType, $createdBy)
    {
        return $this->log(
            'Role',
            'create',
            "New role created: {$roleName} ({$roleEmail}) as {$roleType}",
            $createdBy,
            'role',
            $roleId,
            'medium'
        );
    }

    public function logRoleUpdated($roleId, $roleName, $roleEmail, $updatedBy, $oldValues = null, $newValues = null)
    {
        return $this->log(
            'Role',
            'update',
            "Role updated: {$roleName} ({$roleEmail})",
            $updatedBy,
            'role',
            $roleId,
            'medium',
            $oldValues,
            $newValues
        );
    }

    public function logRoleDeleted($roleId, $roleName, $roleEmail, $deletedBy)
    {
        return $this->log(
            'Role',
            'delete',
            "Role deleted: {$roleName} ({$roleEmail})",
            $deletedBy,
            'role',
            $roleId,
            'high'
        );
    }

    // Helper methods
    private function getUserName($userId)
    {
        if (!$userId) return 'System';
        
        // First try to get name from current session if it's the same user
        $session = session();
        if ($session->get('user_id') == $userId) {
            $sessionName = $session->get('name') ?? $session->get('user_name') ?? $session->get('full_name');
            if ($sessionName) {
                return $sessionName;
            }
        }
        
        // First check admin_role table
        $adminRoleModel = new \App\Models\AdminRoleModel();
        $adminUser = $adminRoleModel->find($userId);
        if ($adminUser) {
            return $adminUser['name'] ?? 'Admin User';
        }
        
        // Then check regular users table
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        return $user ? $user['full_name'] : 'Unknown User';
    }

    private function getUserEmail($userId)
    {
        if (!$userId) return 'system@localhost';
        
        // First check admin_role table
        $adminRoleModel = new \App\Models\AdminRoleModel();
        $adminUser = $adminRoleModel->find($userId);
        if ($adminUser) {
            return $adminUser['email'];
        }
        
        // Then check regular users table
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        return $user ? $user['email'] : 'unknown@localhost';
    }

    private function getUserRole($userId)
    {
        if (!$userId) return 'system';
        
        // First check admin_role table
        $adminRoleModel = new \App\Models\AdminRoleModel();
        $adminUser = $adminRoleModel->find($userId);
        if ($adminUser) {
            return strtolower($adminUser['role']);
        }
        
        // Then check regular users table
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        if (!$user) return 'unknown';
        
        // For regular users, default role is 'user'
        return 'user';
    }

    private function getClientIP()
    {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}