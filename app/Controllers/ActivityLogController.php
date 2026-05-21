<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;
use App\Helpers\ActivityLogger;

class ActivityLogController extends BaseController
{
    protected $activityLogModel;
    protected $activityLogger;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLogModel();
        $this->activityLogger = ActivityLogger::getInstance();
    }

    /**
     * Display activity logs with filtering and pagination
     */
    public function index()
    {
        // Check if user has permission to view activity logs
        if (!$this->hasPermission()) {
            return redirect()->to('/dashboard')->with('error', 'You do not have permission to access activity logs.');
        }

        // Get filter parameters
        $filters = [
            'user_id' => $this->request->getGet('user_id'),
            'module' => $this->request->getGet('module'),
            'action' => $this->request->getGet('action'),
            'severity' => $this->request->getGet('severity'),
            'entity_type' => $this->request->getGet('entity_type'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
            'search' => $this->request->getGet('search')
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return !empty($value);
        });

        // Pagination
        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = 50;
        
        // Get total count first to validate page number
        $totalLogs = $this->activityLogModel->getActivityLogsCount($filters);
        $totalPages = ceil($totalLogs / $perPage);
        
        // If requested page is greater than total pages, redirect to last page
        if ($page > $totalPages && $totalPages > 0) {
            $queryParams = $this->request->getGet();
            $queryParams['page'] = $totalPages;
            return redirect()->to(base_url('activity-logs?' . http_build_query($queryParams)));
        }
        
        // If no data and page > 1, redirect to page 1
        if ($totalLogs == 0 && $page > 1) {
            $queryParams = $this->request->getGet();
            $queryParams['page'] = 1;
            return redirect()->to(base_url('activity-logs?' . http_build_query($queryParams)));
        }
        
        $offset = ($page - 1) * $perPage;

        // Get activity logs
        $activityLogs = $this->activityLogModel->getActivityLogs($filters, $perPage, $offset);

        // Get filter options
        $modules = $this->activityLogModel->getUniqueModules();
        $actions = $this->activityLogModel->getUniqueActions();

        // Pagination data
        $totalPages = ceil($totalLogs / $perPage);

        $data = [
            'title' => 'Activity Logs',
            'activityLogs' => $activityLogs,
            'modules' => $modules,
            'actions' => $actions,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'per_page' => $perPage,
                'total_records' => $totalLogs,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages,
                'previous_page' => $page - 1,
                'next_page' => $page + 1
            ]
        ];

        // Log this view action
        $this->activityLogger->log(
            'Activity Logs',
            'View',
            'Viewed activity logs page',
            ['severity' => 'low']
        );

        return view('activity_logs/index', $data);
    }

    /**
     * Get activity log details via AJAX
     */
    public function details($id)
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Authentication required'])->setStatusCode(401);
        }

        $log = $this->activityLogModel->find($id);
        
        if (!$log) {
            return $this->response->setJSON(['success' => false, 'message' => 'Activity log not found'])->setStatusCode(404);
        }

        // Decode JSON fields
        if (!empty($log['old_values'])) {
            $log['old_values'] = json_decode($log['old_values'], true);
        }
        if (!empty($log['new_values'])) {
            $log['new_values'] = json_decode($log['new_values'], true);
        }
        if (!empty($log['additional_data'])) {
            $log['additional_data'] = json_decode($log['additional_data'], true);
        }

        return $this->response->setJSON(['success' => true, 'data' => $log]);
    }

    /**
     * Export activity logs to CSV
     */
    public function export()
    {
        if (!$this->hasPermission()) {
            return redirect()->to('/dashboard')->with('error', 'You do not have permission to export activity logs.');
        }

        // Get filter parameters
        $filters = [
            'user_id' => $this->request->getGet('user_id'),
            'module' => $this->request->getGet('module'),
            'action' => $this->request->getGet('action'),
            'severity' => $this->request->getGet('severity'),
            'entity_type' => $this->request->getGet('entity_type'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
            'search' => $this->request->getGet('search')
        ];

        // Remove empty filters
        $filters = array_filter($filters, function($value) {
            return !empty($value);
        });

        // Get all matching logs (no pagination for export)
        $activityLogs = $this->activityLogModel->getActivityLogs($filters, 10000, 0);

        // Set headers for CSV download
        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

        // Create CSV content
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, [
            'ID',
            'Date/Time',
            'User Name',
            'User Email',
            'User Role',
            'Module',
            'Action',
            'Description',
            'Entity Type',
            'Entity ID',
            'Severity',
            'IP Address'
        ]);

        // CSV data
        foreach ($activityLogs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['user_name'],
                $log['user_email'],
                $log['user_role'],
                $log['module'],
                $log['action'],
                $log['description'],
                $log['entity_type'],
                $log['entity_id'],
                $log['severity'],
                $log['ip_address']
            ]);
        }

        fclose($output);

        // Log the export action
        $this->activityLogger->log(
            'Activity Logs',
            'Export',
            'Exported activity logs to CSV',
            ['severity' => 'medium']
        );

        return $this->response;
    }

    /**
     * Mark activity logs as read
     */
    public function markAsRead()
    {
        if (!$this->hasPermission()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied'])->setStatusCode(403);
        }

        // Get JSON input
        $input = $this->request->getJSON(true);
        $logIds = $input['activity_ids'] ?? $this->request->getPost('log_ids');
        
        if (empty($logIds) || !is_array($logIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No log IDs provided']);
        }

        // Validate log IDs are numeric
        $logIds = array_filter($logIds, 'is_numeric');
        
        if (empty($logIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid log IDs provided']);
        }

        try {
            // Update read status for the specified logs
            $updated = $this->activityLogModel->whereIn('id', $logIds)->set(['read_status' => 1])->update();
            
            if ($updated) {
                // Log this action
                $this->activityLogger->log(
                    'Activity Logs',
                    'Mark as Read',
                    'Marked ' . count($logIds) . ' activity logs as read',
                    ['severity' => 'low']
                );

                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Successfully marked ' . count($logIds) . ' logs as read',
                    'updated_count' => count($logIds)
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'No logs were updated']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error marking logs as read: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while updating logs']);
        }
    }

    /**
     * Mark a single activity log as read
     */
    public function markSingleAsRead($id)
    {
        if (!$this->hasPermission()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission denied'])->setStatusCode(403);
        }

        if (!is_numeric($id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid log ID']);
        }

        try {
            $log = $this->activityLogModel->find($id);
            
            if (!$log) {
                return $this->response->setJSON(['success' => false, 'message' => 'Activity log not found']);
            }

            // Update read status
            $updated = $this->activityLogModel->update($id, ['read_status' => 1]);
            
            if ($updated) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Log marked as read'
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update log']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error marking single log as read: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while updating log']);
        }
    }

    /**
     * Get activity statistics for dashboard
     */
    public function stats()
    {
        if (!$this->hasPermission()) {
            return $this->response->setJSON(['error' => 'Permission denied'])->setStatusCode(403);
        }

        $days = (int)($this->request->getGet('days') ?? 30);
        $stats = $this->activityLogModel->getActivityStats($days);

        return $this->response->setJSON($stats);
    }

    /**
     * Clean old activity logs (admin only)
     */
    public function cleanup()
    {
        if (!$this->isAdmin()) {
            return redirect()->to('/dashboard')->with('error', 'Only administrators can perform cleanup operations.');
        }

        $days = (int)($this->request->getPost('days') ?? 365);
        
        if ($days < 30) {
            return redirect()->back()->with('error', 'Cannot delete logs newer than 30 days.');
        }

        $deletedCount = $this->activityLogModel->cleanOldLogs($days);

        // Log the cleanup action
        $this->activityLogger->log(
            'Activity Logs',
            'Cleanup',
            "Cleaned up {$deletedCount} activity logs older than {$days} days",
            ['severity' => 'high']
        );

        return redirect()->to('/activity-logs')->with('success', "Successfully deleted {$deletedCount} old activity logs.");
    }

    /**
     * Get unread activity logs count for notification badge
     */
    public function count()
    {
        // Check if user is logged in
        if (!session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Authentication required'])->setStatusCode(401);
        }

        try {
            // Count only unread activities (read_status = 0)
            $count = $this->activityLogModel->where('read_status', 0)->countAllResults();
            return $this->response->setJSON(['success' => true, 'count' => $count]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting unread activity logs count: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error getting count'])->setStatusCode(500);
        }
    }

    /**
     * Check if current user has permission to view activity logs
     */
    private function hasPermission()
    {
        $userRole = session()->get('user_role') ?? session()->get('role');
        
        // Allow admins and moderators to view activity logs
        return in_array($userRole, ['admin', 'moderator', 'super_admin']);
    }

    /**
     * Delete multiple activity logs (admin only)
     */
    public function deleteMultiple()
    {
        if (!$this->isAdmin()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Only administrators can delete activity logs'])->setStatusCode(403);
        }

        // Get JSON input
        $input = $this->request->getJSON(true);
        $logIds = $input['activity_ids'] ?? $this->request->getPost('activity_ids');
        
        if (empty($logIds) || !is_array($logIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No log IDs provided']);
        }

        try {
            // Validate log IDs are numeric
            $logIds = array_filter($logIds, 'is_numeric');
            
            if (empty($logIds)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid log IDs provided']);
            }

            // Perform bulk deletion
            $deleted = $this->activityLogModel->whereIn('id', $logIds)->delete();
            $deletedCount = $deleted ? count($logIds) : 0;

            // Log the bulk deletion action
            $this->activityLogger->log(
                'Activity Logs',
                'Bulk Delete',
                "Bulk deleted {$deletedCount} activity logs",
                ['severity' => 'high']
            );

            return $this->response->setJSON([
                'success' => true, 
                'message' => "Successfully deleted {$deletedCount} activity logs",
                'deleted_count' => $deletedCount,
                'requested_ids' => $logIds,
                'total_requested' => count($logIds)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error deleting activity logs: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error deleting activity logs'
            ])->setStatusCode(500);
        }
    }

    /**
     * Test deletion method for debugging
     */
    public function testDelete()
    {
        if (!$this->isAdmin()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Admin only']);
        }

        // Get the first activity log
        $firstLog = $this->activityLogModel->first();
        if (!$firstLog) {
            return $this->response->setJSON(['success' => false, 'message' => 'No logs found']);
        }

        $logId = $firstLog['id'];
        
        // Check if it exists before deletion
        $existsBefore = $this->activityLogModel->find($logId);
        
        // Try to delete it
        $deleteResult = $this->activityLogModel->delete($logId);
        
        // Check if it exists after deletion
        $existsAfter = $this->activityLogModel->find($logId);
        
        return $this->response->setJSON([
            'log_id' => $logId,
            'exists_before' => $existsBefore ? true : false,
            'delete_result' => $deleteResult ? true : false,
            'exists_after' => $existsAfter ? true : false,
            'total_logs_now' => $this->activityLogModel->countAllResults()
        ]);
    }

    /**
     * Check if current user is admin
     */
    private function isAdmin()
    {
        $userRole = session()->get('user_role') ?? session()->get('role');
        
        return in_array($userRole, ['admin', 'super_admin']);
    }
}