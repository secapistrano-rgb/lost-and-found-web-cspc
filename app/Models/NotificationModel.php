<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications_web';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_email',
        'user_name',
        'title',
        'message',
        'type',
        'entity_type',
        'entity_id',
        'is_read',
        'priority',
        'action_url',
        'metadata'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_email' => 'required|valid_email',
        'title' => 'required|max_length[255]',
        'message' => 'required',
        'type' => 'required|in_list[item,claim,match,system,announcement]',
        'priority' => 'required|in_list[low,medium,high,urgent]'
    ];

    protected $validationMessages = [
        'user_email' => [
            'required' => 'User email is required',
            'valid_email' => 'Please provide a valid email address'
        ],
        'title' => [
            'required' => 'Notification title is required',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'message' => [
            'required' => 'Notification message is required'
        ],
        'type' => [
            'required' => 'Notification type is required',
            'in_list' => 'Invalid notification type'
        ],
        'priority' => [
            'required' => 'Priority is required',
            'in_list' => 'Invalid priority level'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get notifications for a specific user
     *
     * @param string $userEmail
     * @param int $limit
     * @param bool $unreadOnly
     * @return array
     */
    public function getUserNotifications($userEmail, $limit = 20, $unreadOnly = false)
    {
        $builder = $this->where('user_email', $userEmail);
        
        if ($unreadOnly) {
            $builder->where('is_read', 0);
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->limit($limit)
                      ->findAll();
    }

    /**
     * Get paginated notifications for a specific user
     *
     * @param string $userEmail
     * @param int $perPage
     * @param bool $unreadOnly
     * @param string $group
     * @return array
     */
    public function getUserNotificationsPaginated($userEmail, $perPage = 10, $unreadOnly = false, $group = 'notifications')
    {
        $builder = $this->where('user_email', $userEmail);
        
        if ($unreadOnly) {
            $builder->where('is_read', 0);
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->paginate($perPage, $group);
    }

    /**
     * Get notifications with manual pagination
     *
     * @param string $userEmail
     * @param int $perPage
     * @param int $offset
     * @param bool $unreadOnly
     * @return array
     */
    public function getUserNotificationsManual($userEmail, $perPage = 10, $offset = 0, $unreadOnly = false)
    {
        $builder = $this->where('user_email', $userEmail);
        
        if ($unreadOnly) {
            $builder->where('is_read', 0);
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->limit($perPage, $offset)
                      ->findAll();
    }

    /**
     * Get total count of notifications for a user
     *
     * @param string $userEmail
     * @param bool $unreadOnly
     * @return int
     */
    public function getUserNotificationsCount($userEmail, $unreadOnly = false)
    {
        $builder = $this->where('user_email', $userEmail);
        
        if ($unreadOnly) {
            $builder->where('is_read', 0);
        }
        
        return $builder->countAllResults();
    }

    /**
     * Get unread notification count for a user
     *
     * @param string $userEmail
     * @return int
     */
    public function getUnreadCount($userEmail)
    {
        return $this->where('user_email', $userEmail)
                   ->where('is_read', 0)
                   ->countAllResults();
    }

    /**
     * Mark notification as read
     *
     * @param int $notificationId
     * @param string $userEmail
     * @return bool
     */
    public function markAsRead($notificationId, $userEmail)
    {
        log_message('debug', '=== NotificationModel markAsRead DEBUG START ===');
        log_message('debug', 'Table name: ' . $this->table);
        log_message('debug', 'Notification ID: ' . $notificationId);
        log_message('debug', 'User email: ' . $userEmail);
        
        // First check if the notification exists
        $notification = $this->where('id', $notificationId)
                            ->where('user_email', $userEmail)
                            ->first();
        
        log_message('debug', 'Found notification: ' . ($notification ? json_encode($notification) : 'null'));
        
        if (!$notification) {
            log_message('debug', 'Notification not found, returning false');
            return false;
        }
        
        // Check if already read
        if ($notification['is_read'] == 1) {
            log_message('debug', 'Notification already marked as read');
            return true; // Consider it successful since it's already read
        }
        
        $updateData = [
            'is_read' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        log_message('debug', 'Update data: ' . json_encode($updateData));
        
        $result = $this->where('id', $notificationId)
                      ->where('user_email', $userEmail)
                      ->set($updateData)
                      ->update();
        
        log_message('debug', 'Update result: ' . ($result ? 'true' : 'false'));
        log_message('debug', 'Affected rows: ' . $this->db->affectedRows());
        
        // Verify the update
        $updatedNotification = $this->where('id', $notificationId)
                                   ->where('user_email', $userEmail)
                                   ->first();
        
        log_message('debug', 'Updated notification: ' . ($updatedNotification ? json_encode($updatedNotification) : 'null'));
        log_message('debug', '=== NotificationModel markAsRead DEBUG END ===');
        
        return $result;
    }

    /**
     * Mark all notifications as read for a user
     *
     * @param string $userEmail
     * @return bool
     */
    public function markAllAsRead($userEmail)
    {
        return $this->where('user_email', $userEmail)
                   ->where('is_read', 0)
                   ->set('is_read', 1)
                   ->set('updated_at', date('Y-m-d H:i:s'))
                   ->update();
    }

    /**
     * Create a new notification
     *
     * @param array $data
     * @return int|false
     */
    public function createNotification($data)
    {
        // Set default values
        $data['is_read'] = $data['is_read'] ?? 0;
        $data['priority'] = $data['priority'] ?? 'medium';
        $data['created_at'] = date('Y-m-d H:i:s');
        
        // Convert metadata to JSON if it's an array
        if (isset($data['metadata']) && is_array($data['metadata'])) {
            $data['metadata'] = json_encode($data['metadata']);
        }
        
        return $this->insert($data);
    }

    /**
     * Delete old notifications (older than specified days)
     *
     * @param int $days
     * @return bool
     */
    public function deleteOldNotifications($days = 30)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        return $this->where('created_at <', $cutoffDate)
                   ->delete();
    }

    /**
     * Get notifications by type
     *
     * @param string $type
     * @param int $limit
     * @return array
     */
    public function getNotificationsByType($type, $limit = 50)
    {
        return $this->where('type', $type)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get notifications by entity
     *
     * @param string $entityType
     * @param string $entityId
     * @return array
     */
    public function getNotificationsByEntity($entityType, $entityId)
    {
        return $this->where('entity_type', $entityType)
                   ->where('entity_id', $entityId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Get admin notifications (notifications marked as admin notifications)
     *
     * @param string $adminEmail
     * @param int $limit
     * @return array
     */
    public function getAdminNotifications($adminEmail, $limit = 50)
    {
        return $this->where('user_email', $adminEmail)
                   ->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get all admin notifications across all admins
     *
     * @param int $limit
     * @return array
     */
    public function getAllAdminNotifications($limit = 100)
    {
        return $this->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get notifications by priority
     *
     * @param string $priority
     * @param int $limit
     * @return array
     */
    public function getNotificationsByPriority($priority, $limit = 50)
    {
        return $this->where('priority', $priority)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get urgent notifications for admins
     *
     * @param string $adminEmail
     * @param int $limit
     * @return array
     */
    public function getUrgentAdminNotifications($adminEmail, $limit = 20)
    {
        return $this->where('user_email', $adminEmail)
                   ->where('priority', 'urgent')
                   ->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                   ->where('is_read', 0)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get notification statistics for admin dashboard
     *
     * @param string $adminEmail
     * @return array
     */
    public function getAdminNotificationStats($adminEmail)
    {
        $stats = [];
        
        // Total admin notifications
        $stats['total'] = $this->where('user_email', $adminEmail)
                              ->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                              ->countAllResults();
        
        // Unread admin notifications
        $stats['unread'] = $this->where('user_email', $adminEmail)
                               ->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                               ->where('is_read', 0)
                               ->countAllResults();
        
        // Urgent unread notifications
        $stats['urgent'] = $this->where('user_email', $adminEmail)
                               ->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                               ->where('priority', 'urgent')
                               ->where('is_read', 0)
                               ->countAllResults();
        
        // Notifications by type
        $typeStats = $this->select('type, COUNT(*) as count')
                          ->where('user_email', $adminEmail)
                          ->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                          ->where('is_read', 0)
                          ->groupBy('type')
                          ->findAll();
        
        $stats['by_type'] = [];
        foreach ($typeStats as $typeStat) {
            $stats['by_type'][$typeStat['type']] = $typeStat['count'];
        }
        
        return $stats;
    }

    /**
     * Mark all admin notifications as read for a specific admin
     *
     * @param string $adminEmail
     * @return bool
     */
    public function markAllAdminNotificationsAsRead($adminEmail)
    {
        return $this->where('user_email', $adminEmail)
                   ->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                   ->where('is_read', 0)
                   ->set(['is_read' => 1, 'updated_at' => date('Y-m-d H:i:s')])
                   ->update();
    }

    /**
     * Get recent activity notifications for admin dashboard
     *
     * @param string $adminEmail
     * @param int $hours
     * @param int $limit
     * @return array
     */
    public function getRecentAdminActivity($adminEmail, $hours = 24, $limit = 10)
    {
        $cutoffTime = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));
        
        return $this->where('user_email', $adminEmail)
                   ->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                   ->where('created_at >=', $cutoffTime)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Get notifications by module for admin filtering
     *
     * @param string $adminEmail
     * @param string $module
     * @param int $limit
     * @return array
     */
    public function getAdminNotificationsByModule($adminEmail, $module, $limit = 50)
    {
        $moduleTypes = [
            'items' => ['item'],
            'claims' => ['claim'],
            'matches' => ['match'],
            'feedback' => ['feedback'],
            'support' => ['support'],
            'users' => ['user'],
            'reports' => ['report'],
            'system' => ['system', 'announcement']
        ];
        
        $types = $moduleTypes[$module] ?? [$module];
        
        return $this->where('user_email', $adminEmail)
                   ->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                   ->whereIn('type', $types)
                   ->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }

    /**
     * Delete admin notifications older than specified days
     *
     * @param int $days
     * @return bool
     */
    public function deleteOldAdminNotifications($days = 90)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        return $this->where("JSON_EXTRACT(metadata, '$.is_admin_notification') = true")
                   ->where('created_at <', $cutoffDate)
                   ->delete();
    }

    /**
     * Get all notifications for admin management (across all users)
     *
     * @param int $limit
     * @param int $offset
     * @param array $filters
     * @return array
     */
    public function getAllNotificationsForAdmin($limit = 20, $offset = 0, $filters = [])
    {
        log_message('debug', '=== MODEL getAllNotificationsForAdmin DEBUG START ===');
        log_message('debug', 'Model parameters - limit: ' . $limit . ', offset: ' . $offset);
        log_message('debug', 'Model filters: ' . json_encode($filters));
        
        $builder = $this->select('notifications_web.*, users.full_name as user_full_name')
                        ->join('users', 'users.email = notifications_web.user_email', 'left');
        
        // Apply filters
        if (!empty($filters['user_email'])) {
            $builder->where('notifications_web.user_email', $filters['user_email']);
            log_message('debug', 'Applied user_email filter: ' . $filters['user_email']);
        }
        
        if (!empty($filters['type'])) {
            $builder->where('notifications_web.type', $filters['type']);
            log_message('debug', 'Applied type filter: ' . $filters['type']);
        }
        
        if (!empty($filters['priority'])) {
            $builder->where('notifications_web.priority', $filters['priority']);
            log_message('debug', 'Applied priority filter: ' . $filters['priority']);
        }
        
        if (!empty($filters['is_read'])) {
            $builder->where('notifications_web.is_read', $filters['is_read'] === 'read' ? 1 : 0);
            log_message('debug', 'Applied is_read filter: ' . $filters['is_read']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('notifications_web.created_at >=', $filters['date_from']);
            log_message('debug', 'Applied date_from filter: ' . $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('notifications_web.created_at <=', $filters['date_to']);
            log_message('debug', 'Applied date_to filter: ' . $filters['date_to']);
        }
        
        $finalBuilder = $builder->orderBy('notifications_web.created_at', 'DESC')
                               ->limit($limit, $offset);
        
        // Log the final SQL query
        $sql = $finalBuilder->getCompiledSelect(false);
        log_message('debug', 'Final SQL query: ' . $sql);
        
        $result = $finalBuilder->findAll();
        log_message('debug', 'Query result count: ' . count($result));
        log_message('debug', '=== MODEL getAllNotificationsForAdmin DEBUG END ===');
        
        return $result;
    }

    /**
     * Get total count of all notifications for admin
     *
     * @param array $filters
     * @return int
     */
    public function getAllNotificationsCountForAdmin($filters = [])
    {
        $builder = $this->select('notifications_web.*')
                        ->join('users', 'users.email = notifications_web.user_email', 'left');
        
        // Apply same filters as getAllNotificationsForAdmin
        if (!empty($filters['user_email'])) {
            $builder->where('notifications_web.user_email', $filters['user_email']);
        }
        
        if (!empty($filters['type'])) {
            $builder->where('notifications_web.type', $filters['type']);
        }
        
        if (!empty($filters['priority'])) {
            $builder->where('notifications_web.priority', $filters['priority']);
        }
        
        if (!empty($filters['is_read'])) {
            $builder->where('notifications_web.is_read', $filters['is_read'] === 'read' ? 1 : 0);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('notifications_web.created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('notifications_web.created_at <=', $filters['date_to']);
        }
        
        return $builder->countAllResults();
    }

    /**
     * Get notification statistics for admin dashboard
     *
     * @return array
     */
    public function getAllNotificationStats()
    {
        $stats = [];
        
        // Total notifications
        $stats['total'] = $this->countAllResults();
        
        // Unread notifications
        $stats['unread'] = $this->where('is_read', 0)->countAllResults();
        
        // Notifications by type
        $stats['by_type'] = $this->select('type, COUNT(*) as count')
                                 ->groupBy('type')
                                 ->findAll();
        
        // Notifications by priority
        $stats['by_priority'] = $this->select('priority, COUNT(*) as count')
                                     ->groupBy('priority')
                                     ->findAll();
        
        // Recent notifications (last 7 days)
        $weekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        $stats['recent'] = $this->where('created_at >=', $weekAgo)->countAllResults();
        
        return $stats;
    }

    /**
     * Get all unique users who have notifications
     *
     * @return array
     */
    public function getAllNotificationUsers()
    {
        return $this->select('DISTINCT notifications_web.user_email, notifications_web.user_name, users.full_name')
                    ->join('users', 'users.email = notifications_web.user_email', 'left')
                    ->orderBy('notifications_web.user_name', 'ASC')
                    ->findAll();
    }

    /**
     * Bulk mark notifications as read for admin
     *
     * @param array $notificationIds
     * @return bool
     */
    public function bulkMarkAsRead($notificationIds)
    {
        if (empty($notificationIds)) {
            return false;
        }
        
        return $this->whereIn('id', $notificationIds)
                   ->set('is_read', 1)
                   ->set('updated_at', date('Y-m-d H:i:s'))
                   ->update();
    }

    /**
     * Bulk delete notifications for admin
     *
     * @param array $notificationIds
     * @return bool
     */
    public function bulkDelete($notificationIds)
    {
        if (empty($notificationIds)) {
            return false;
        }
        
        return $this->whereIn('id', $notificationIds)->delete();
    }

    /**
     * Delete all notifications for a specific user (admin function)
     *
     * @param string $userEmail
     * @return bool
     */
    public function deleteAllUserNotifications($userEmail)
    {
        return $this->where('user_email', $userEmail)->delete();
    }
}