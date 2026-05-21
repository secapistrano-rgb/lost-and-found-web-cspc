<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use App\Helpers\ActivityLogger;
use CodeIgniter\HTTP\ResponseInterface;

class NotificationController extends BaseController
{
    protected $notificationModel;
    private $activityLogger;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        $this->notificationModel = new NotificationModel();
        $this->activityLogger = ActivityLogger::getInstance();
    }

    /**
     * Display notifications page
     */
    public function index()
    {
        $userEmail = session()->get('email');
        if (!$userEmail) {
            return redirect()->to('/login');
        }

        // Get filter parameters
        $unreadOnly = $this->request->getGet('unread_only') === 'true';
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10; // Number of notifications per page

        // Get total count first to validate page number
        $totalNotifications = $this->notificationModel->getUserNotificationsCount($userEmail, $unreadOnly);
        $totalPages = ceil($totalNotifications / $perPage);
        
        // If requested page is greater than total pages, redirect to last page
        if ($page > $totalPages && $totalPages > 0) {
            $queryParams = $this->request->getGet();
            $queryParams['page'] = $totalPages;
            return redirect()->to(base_url('notifications?' . http_build_query($queryParams)));
        }
        
        // If no data and page > 1, redirect to page 1
        if ($totalNotifications == 0 && $page > 1) {
            $queryParams = $this->request->getGet();
            $queryParams['page'] = 1;
            return redirect()->to(base_url('notifications?' . http_build_query($queryParams)));
        }
        
        $offset = ($page - 1) * $perPage;

        // Get notifications with manual pagination
        $notifications = $this->notificationModel->getUserNotificationsManual($userEmail, $perPage, $offset, $unreadOnly);

        $data = [
            'title' => 'Notifications',
            'notifications' => $notifications,
            'unread_count' => $this->notificationModel->getUnreadCount($userEmail),
            'unread_only' => $unreadOnly,
            'filters' => ['unread_only' => $unreadOnly],
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_records' => $totalNotifications,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages,
                'previous_page' => $page - 1,
                'next_page' => $page + 1
            ]
        ];

        // Log notification page view activity
        $this->activityLogger->log(
            'Notifications',
            'view_page',
            'User viewed notifications page',
            [
                'severity' => 'low',
                'entity_type' => 'notifications',
                'entity_id' => session()->get('user_id'),
                'new_values' => [
                    'user_email' => $userEmail,
                    'total_notifications' => $totalNotifications,
                    'unread_count' => $data['unread_count'],
                    'unread_only_filter' => $unreadOnly,
                    'page' => $page
                ]
            ]
        );

        return view('notifications/index', $data);
    }

    /**
     * Get notifications for the current user (AJAX)
     */
    public function getNotifications()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in using multiple session variables for robustness
        $userEmail = session()->get('email');
        $userId = session()->get('user_id');
        $loggedIn = session()->get('logged_in');
        
        if (!$userEmail || !$userId || !$loggedIn) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'User not authenticated']);
        }

        try {
            $limit = $this->request->getGet('limit') ?? 20;
            $unreadOnly = $this->request->getGet('unread_only') === 'true';
            $adminOnly = $this->request->getGet('admin_only') === 'true';
            $module = $this->request->getGet('module');
            $priority = $this->request->getGet('priority');
            
            // Get notifications based on filters
            if ($adminOnly) {
                if ($module) {
                    $notifications = $this->notificationModel->getAdminNotificationsByModule($userEmail, $module, $limit);
                } else {
                    $notifications = $this->notificationModel->getAdminNotifications($userEmail, $limit);
                }
            } else {
                $notifications = $this->notificationModel->getUserNotifications($userEmail, $limit, $unreadOnly);
            }
            
            // Apply priority filter if specified
            if ($priority && !empty($notifications)) {
                $notifications = array_filter($notifications, function($notification) use ($priority) {
                    return $notification['priority'] === $priority;
                });
            }
            
            $unreadCount = $this->notificationModel->getUnreadCount($userEmail);

            // Format notifications for frontend
            $formattedNotifications = array_map(function($notification) {
                return [
                    'id' => $notification['id'],
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'type' => $notification['type'],
                    'priority' => $notification['priority'],
                    'is_read' => (bool)$notification['is_read'],
                    'action_url' => $notification['action_url'],
                    'created_at' => $notification['created_at'],
                    'time_ago' => $this->timeAgo($notification['created_at']),
                    'icon' => $this->getNotificationIcon($notification['type']),
                    'color' => $this->getNotificationColor($notification['type'])
                ];
            }, $notifications);

            return $this->response->setJSON([
                'success' => true,
                'notifications' => $formattedNotifications,
                'unread_count' => $unreadCount,
                'total_count' => count($formattedNotifications)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Failed to get notifications: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to load notifications']);
        }
    }

    /**
     * Mark notification as read (AJAX)
     */
    public function markAsRead($notificationId = null)
    {
        log_message('debug', '=== markAsRead Controller DEBUG START ===');
        log_message('debug', 'Notification ID received: ' . $notificationId);
        log_message('debug', 'Request method: ' . $this->request->getMethod());
        log_message('debug', 'Is AJAX: ' . ($this->request->isAJAX() ? 'true' : 'false'));
        
        // Set proper headers for JSON response
        $this->response->setHeader('Content-Type', 'application/json');
        
        // Allow both AJAX and regular POST requests for better compatibility
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'post') {
            log_message('debug', 'Request is not AJAX or POST, returning error');
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'Invalid request method'
            ]);
        }

        // Check if user is logged in using multiple session variables for robustness
        $userEmail = session()->get('email');
        $userId = session()->get('user_id');
        $loggedIn = session()->get('logged_in');
        
        log_message('debug', 'Session data - Email: ' . $userEmail . ', User ID: ' . $userId . ', Logged in: ' . ($loggedIn ? 'true' : 'false'));
        
        if (!$userEmail || !$userId || !$loggedIn) {
            log_message('debug', 'User not authenticated, returning error');
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'error' => 'User not authenticated'
            ]);
        }

        if ($notificationId === null || $notificationId === '' || !is_numeric($notificationId) || $notificationId < 1) {
            log_message('debug', 'Notification ID is missing or invalid, returning error');
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'Valid notification ID is required'
            ]);
        }

        try {
            log_message('debug', 'Calling notificationModel->markAsRead with ID: ' . $notificationId . ' and email: ' . $userEmail);
            $result = $this->notificationModel->markAsRead($notificationId, $userEmail);
            log_message('debug', 'markAsRead result: ' . ($result ? 'true' : 'false'));
            
            if ($result) {
                $unreadCount = $this->notificationModel->getUnreadCount($userEmail);
                log_message('debug', 'Unread count after update: ' . $unreadCount);
                
                // Log notification mark as read activity
                $this->activityLogger->log(
                    'Notifications',
                    'mark_as_read',
                    'User marked a notification as read',
                    [
                        'severity' => 'low',
                        'entity_type' => 'notification',
                        'entity_id' => $notificationId,
                        'new_values' => [
                            'user_email' => $userEmail,
                            'user_id' => $userId
                        ]
                    ]
                );
                
                $response = [
                    'success' => true,
                    'message' => 'Notification marked as read',
                    'unread_count' => $unreadCount,
                    'notification_id' => $notificationId
                ];
                log_message('debug', 'Returning success response: ' . json_encode($response));
                
                return $this->response->setStatusCode(200)->setJSON($response);
            } else {
                log_message('debug', 'markAsRead returned false, notification not found');
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'error' => 'Notification not found or already read'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Exception in markAsRead: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to update notification: ' . $e->getMessage()
            ]);
        }
        
        log_message('debug', '=== markAsRead Controller DEBUG END ===');
    }

    /**
     * Mark all notifications as read (AJAX)
     */
    public function markAllAsRead()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in using multiple session variables for robustness
        $userEmail = session()->get('email');
        $userId = session()->get('user_id');
        $loggedIn = session()->get('logged_in');
        
        if (!$userEmail || !$userId || !$loggedIn) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'User not authenticated']);
        }

        try {
            $result = $this->notificationModel->markAllAsRead($userEmail);
            
            // Log mark all as read activity
            $this->activityLogger->log(
                'Notifications',
                'mark_all_as_read',
                'User marked all notifications as read',
                [
                    'severity' => 'low',
                    'entity_type' => 'notifications',
                    'entity_id' => $userId,
                    'new_values' => [
                        'user_email' => $userEmail,
                        'user_id' => $userId
                    ]
                ]
            );
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'All notifications marked as read',
                'unread_count' => 0
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Failed to mark all notifications as read: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update notifications']);
        }
    }

    /**
     * Get unread notification count (AJAX)
     */
    public function getUnreadCount()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in using multiple session variables for robustness
        $userEmail = session()->get('email');
        $userId = session()->get('user_id');
        $loggedIn = session()->get('logged_in');
        
        if (!$userEmail || !$userId || !$loggedIn) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'User not authenticated']);
        }

        try {
            $unreadCount = $this->notificationModel->getUnreadCount($userEmail);
            
            return $this->response->setJSON([
                'success' => true,
                'unread_count' => $unreadCount
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Failed to get unread count: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to get notification count']);
        }
    }

    /**
     * Delete notification (AJAX)
     */
    public function delete($notificationId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in using multiple session variables for robustness
        $userEmail = session()->get('email');
        $userId = session()->get('user_id');
        $loggedIn = session()->get('logged_in');
        
        if (!$userEmail || !$userId || !$loggedIn) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'User not authenticated']);
        }

        if ($notificationId === null || $notificationId === '') {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Notification ID is required']);
        }

        // Validate that notification ID is a valid integer
        if (!is_numeric($notificationId) || $notificationId < 1) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid notification ID']);
        }

        try {
            $result = $this->notificationModel->where('id', $notificationId)
                                            ->where('user_email', $userEmail)
                                            ->delete();
            
            if ($result) {
                // Log notification deletion activity
                $this->activityLogger->log(
                    'Notifications',
                    'delete',
                    'User deleted a notification',
                    [
                        'severity' => 'medium',
                        'entity_type' => 'notification',
                        'entity_id' => $notificationId,
                        'new_values' => [
                            'user_email' => $userEmail,
                            'user_id' => $userId
                        ]
                    ]
                );
                
                $unreadCount = $this->notificationModel->getUnreadCount($userEmail);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Notification deleted',
                    'unread_count' => $unreadCount
                ]);
            } else {
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Notification not found']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Failed to delete notification: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete notification']);
        }
    }

    /**
     * Get admin notification statistics (AJAX)
     */
    public function getAdminStats()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in using multiple session variables for robustness
        $userEmail = session()->get('email');
        $userId = session()->get('user_id');
        $loggedIn = session()->get('logged_in');
        
        if (!$userEmail || !$userId || !$loggedIn) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'User not authenticated']);
        }

        try {
            $stats = $this->notificationModel->getAdminNotificationStats($userEmail);
            
            // Log admin stats view activity
            $this->activityLogger->log(
                'Notifications',
                'view_admin_stats',
                'User viewed admin notification statistics',
                [
                    'severity' => 'low',
                    'entity_type' => 'notification',
                    'entity_id' => null,
                    'new_values' => [
                        'user_email' => $userEmail,
                        'stats_data' => json_encode($stats),
                        'ip_address' => $this->request->getIPAddress(),
                        'user_agent' => $this->request->getUserAgent()->getAgentString(),
                        'user_id' => $userId
                    ]
                ]
            );
            
            return $this->response->setJSON([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Failed to get admin notification stats: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to load statistics']);
        }
    }

    /**
     * Mark all admin notifications as read (AJAX)
     */
    public function markAllAdminAsRead()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in using multiple session variables for robustness
        $userEmail = session()->get('email');
        $userId = session()->get('user_id');
        $loggedIn = session()->get('logged_in');
        
        if (!$userEmail || !$userId || !$loggedIn) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'User not authenticated']);
        }

        try {
            $result = $this->notificationModel->markAllAdminNotificationsAsRead($userEmail);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'All admin notifications marked as read'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Failed to mark all admin notifications as read: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to update notifications']);
        }
    }

    /**
     * Get urgent admin notifications (AJAX)
     */
    public function getUrgentNotifications()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        // Check if user is logged in using multiple session variables for robustness
        $userEmail = session()->get('email');
        $userId = session()->get('user_id');
        $loggedIn = session()->get('logged_in');
        
        if (!$userEmail || !$userId || !$loggedIn) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'User not authenticated']);
        }

        try {
            $limit = $this->request->getGet('limit') ?? 10;
            $notifications = $this->notificationModel->getUrgentAdminNotifications($userEmail, $limit);
            
            // Log urgent notifications view activity
            $this->activityLogger->log(
                'Notifications',
                'view_urgent',
                'User viewed urgent notifications',
                [
                    'severity' => 'medium',
                    'entity_type' => 'notification',
                    'entity_id' => null,
                    'new_values' => [
                        'user_email' => $userEmail,
                        'limit' => $limit,
                        'urgent_count' => count($notifications),
                        'ip_address' => $this->request->getIPAddress(),
                        'user_agent' => $this->request->getUserAgent()->getAgentString(),
                        'user_id' => $userId
                    ]
                ]
            );
            
            // Format notifications for frontend
            $formattedNotifications = array_map(function($notification) {
                return [
                    'id' => $notification['id'],
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'type' => $notification['type'],
                    'priority' => $notification['priority'],
                    'is_read' => (bool)$notification['is_read'],
                    'action_url' => $notification['action_url'],
                    'created_at' => $notification['created_at'],
                    'time_ago' => $this->timeAgo($notification['created_at']),
                    'icon' => $this->getNotificationIcon($notification['type']),
                    'color' => $this->getNotificationColor($notification['type'])
                ];
            }, $notifications);

            return $this->response->setJSON([
                'success' => true,
                'notifications' => $formattedNotifications,
                'count' => count($formattedNotifications)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Failed to get urgent notifications: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to load urgent notifications']);
        }
    }

    /**
     * Get notification icon based on type
     */
    private function getNotificationIcon($type)
    {
        $icons = [
            'item' => 'fas fa-box',
            'claim' => 'fas fa-hand-paper',
            'match' => 'fas fa-link',
            'system' => 'fas fa-cog',
            'announcement' => 'fas fa-bullhorn',
            'feedback' => 'fas fa-comment',
            'support' => 'fas fa-life-ring',
            'user' => 'fas fa-user',
            'report' => 'fas fa-chart-bar'
        ];

        return $icons[$type] ?? 'fas fa-bell';
    }

    /**
     * Get notification color based on type
     */
    private function getNotificationColor($type)
    {
        $colors = [
            'item' => 'primary',
            'claim' => 'warning',
            'match' => 'success',
            'system' => 'info',
            'announcement' => 'danger',
            'feedback' => 'info',
            'support' => 'warning',
            'user' => 'primary',
            'report' => 'secondary'
        ];

        return $colors[$type] ?? 'secondary';
    }

    /**
     * Calculate time ago from timestamp
     */
    private function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) {
            return 'just now';
        } elseif ($time < 3600) {
            $minutes = floor($time / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($time < 86400) {
            $hours = floor($time / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($time < 2592000) {
            $days = floor($time / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } elseif ($time < 31536000) {
            $months = floor($time / 2592000);
            return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
        } else {
            $years = floor($time / 31536000);
            return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
        }
    }

    /**
     * Check if current user has admin access
     */
    private function hasAdminAccess()
    {
        $userRole = session()->get('user_role') ?? session()->get('role');
        return in_array($userRole, ['admin', 'super_admin']);
    }

    /**
     * Admin notification management page
     */
    public function adminIndex()
    {
        // Debug: Check if method is being called
        error_log("=== ADMIN INDEX METHOD CALLED ===");
        
        if (!$this->hasAdminAccess()) {
            error_log("=== ADMIN ACCESS DENIED ===");
            return redirect()->to('/notifications')->with('error', 'Access denied. Admin privileges required.');
        }
        
        error_log("=== ADMIN ACCESS GRANTED ===");

        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        // Get filters
        $filters = [
            'user_email' => $this->request->getGet('user_email'),
            'type' => $this->request->getGet('type'),
            'priority' => $this->request->getGet('priority'),
            'is_read' => $this->request->getGet('is_read'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to')
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return !empty($value);
        });

        // Debug: Log before calling model methods
        log_message('debug', '=== ADMIN NOTIFICATIONS DEBUG START ===');
        log_message('debug', 'Parameters - perPage: ' . $perPage . ', offset: ' . $offset);
        log_message('debug', 'Filters before processing: ' . json_encode($filters));
        
        $notifications = $this->notificationModel->getAllNotificationsForAdmin($perPage, $offset, $filters);
        log_message('debug', 'After getAllNotificationsForAdmin - Count: ' . count($notifications));
        
        $totalNotifications = $this->notificationModel->getAllNotificationsCountForAdmin($filters);
        log_message('debug', 'After getAllNotificationsCountForAdmin - Total: ' . $totalNotifications);
        
        $totalPages = ceil($totalNotifications / $perPage);

        // Debug: Test direct database query
        $db = \Config\Database::connect();
        $directQuery = $db->query("SELECT COUNT(*) as total FROM notifications_web");
        $directResult = $directQuery->getRow();
        log_message('debug', 'Direct database count: ' . $directResult->total);
        
        // Debug: Test simple query
        $simpleQuery = $db->query("SELECT * FROM notifications_web LIMIT 3");
        $simpleResults = $simpleQuery->getResult();
        log_message('debug', 'Simple query results count: ' . count($simpleResults));
        
        if (!empty($notifications)) {
            log_message('debug', 'First notification: ' . json_encode($notifications[0]));
        } else {
            log_message('debug', 'No notifications returned from model');
        }
        log_message('debug', '=== ADMIN NOTIFICATIONS DEBUG END ===');

        // Get all users for filter dropdown
        $users = $this->notificationModel->getAllNotificationUsers();

        // Get notification stats
        $stats = $this->notificationModel->getAllNotificationStats();

        $data = [
            'title' => 'Admin - Notification Management',
            'notifications' => $notifications,
            'users' => $users,
            'stats' => $stats,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_records' => $totalNotifications,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages,
                'previous_page' => $page - 1,
                'next_page' => $page + 1
            ]
        ];

        // Log admin notification access
        $this->activityLogger->log(
            'Notifications',
            'admin_view',
            'Admin accessed notification management',
            [
                'severity' => 'medium',
                'entity_type' => 'notifications',
                'entity_id' => session()->get('user_id'),
                'new_values' => [
                    'total_notifications' => $totalNotifications,
                    'filters_applied' => !empty($filters),
                    'page' => $page
                ]
            ]
        );

        return view('notifications/admin_index', $data);
    }

    /**
     * Bulk mark notifications as read (admin)
     */
    public function adminBulkMarkRead()
    {
        if (!$this->hasAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied']);
        }

        try {
            $notificationIds = $this->request->getJSON(true)['notification_ids'] ?? [];
            
            if (empty($notificationIds)) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'No notifications selected']);
            }

            $result = $this->notificationModel->bulkMarkAsRead($notificationIds);

            if ($result) {
                // Log bulk mark as read activity
                $this->activityLogger->log(
                    'Notifications',
                    'admin_bulk_mark_read',
                    'Admin marked multiple notifications as read',
                    [
                        'severity' => 'medium',
                        'entity_type' => 'notifications',
                        'entity_id' => session()->get('user_id'),
                        'new_values' => [
                            'notification_count' => count($notificationIds),
                            'notification_ids' => $notificationIds
                        ]
                    ]
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => count($notificationIds) . ' notifications marked as read'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to mark notifications as read']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin bulk mark read failed: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to mark notifications as read']);
        }
    }

    /**
     * Bulk delete notifications (admin)
     */
    public function adminBulkDelete()
    {
        if (!$this->hasAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied']);
        }

        try {
            $notificationIds = $this->request->getJSON(true)['notification_ids'] ?? [];
            
            if (empty($notificationIds)) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'No notifications selected']);
            }

            $result = $this->notificationModel->bulkDelete($notificationIds);

            if ($result) {
                // Log bulk delete activity
                $this->activityLogger->log(
                    'Notifications',
                    'admin_bulk_delete',
                    'Admin deleted multiple notifications',
                    [
                        'severity' => 'high',
                        'entity_type' => 'notifications',
                        'entity_id' => session()->get('user_id'),
                        'new_values' => [
                            'notification_count' => count($notificationIds),
                            'notification_ids' => $notificationIds
                        ]
                    ]
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => count($notificationIds) . ' notifications deleted'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete notifications']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin bulk delete failed: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete notifications']);
        }
    }

    /**
     * Delete all notifications for a user (admin)
     */
    public function adminDeleteUserNotifications()
    {
        if (!$this->hasAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied']);
        }

        try {
            $userEmail = $this->request->getJSON(true)['user_email'] ?? '';
            
            if (empty($userEmail)) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'User email is required']);
            }

            $result = $this->notificationModel->deleteAllUserNotifications($userEmail);

            if ($result) {
                // Log user notifications deletion
                $this->activityLogger->log(
                    'Notifications',
                    'admin_delete_user_notifications',
                    'Admin deleted all notifications for user',
                    [
                        'severity' => 'high',
                        'entity_type' => 'notifications',
                        'entity_id' => session()->get('user_id'),
                        'new_values' => [
                            'target_user_email' => $userEmail
                        ]
                    ]
                );

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'All notifications deleted for user: ' . $userEmail
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete user notifications']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Admin delete user notifications failed: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to delete user notifications']);
        }
    }

    /**
     * Get all notification statistics for admin dashboard
     */
    public function getAllStats()
    {
        if (!$this->hasAdminAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied']);
        }

        try {
            $stats = $this->notificationModel->getAllNotificationStats();
            return $this->response->setJSON(['success' => true, 'stats' => $stats]);
        } catch (\Exception $e) {
            log_message('error', 'Failed to get admin notification stats: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to load statistics']);
        }
    }
}