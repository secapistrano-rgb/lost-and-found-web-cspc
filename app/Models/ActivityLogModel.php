<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'module',
        'action',
        'description',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'severity',
        'read_status',
        'created_at'
    ];
    
    protected $useTimestamps = false; // We handle created_at manually
    
    protected $validationRules = [
        'module' => 'required|max_length[100]',
        'action' => 'required|max_length[100]',
        'severity' => 'required|in_list[low,medium,high,critical]'
    ];
    
    protected $validationMessages = [
        'module' => [
            'required' => 'Module is required',
            'max_length' => 'Module name cannot exceed 100 characters'
        ],
        'action' => [
            'required' => 'Action is required',
            'max_length' => 'Action name cannot exceed 100 characters'
        ],
        'severity' => [
            'required' => 'Severity is required',
            'in_list' => 'Severity must be one of: low, medium, high, critical'
        ]
    ];

    /**
     * Log an activity
     */
    public function logActivity($data)
    {
        // Ensure created_at is set
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        
        // Convert arrays/objects to JSON for old_values and new_values
        if (isset($data['old_values']) && is_array($data['old_values'])) {
            $data['old_values'] = json_encode($data['old_values']);
        }
        if (isset($data['new_values']) && is_array($data['new_values'])) {
            $data['new_values'] = json_encode($data['new_values']);
        }
        
        return $this->insert($data);
    }

    /**
     * Get activity logs with filtering and pagination
     */
    public function getActivityLogs($filters = [], $limit = 50, $offset = 0)
    {
        $builder = $this->builder();
        
        // Explicitly select all fields including the ID
        $builder->select('*');
        
        // Apply filters
        if (!empty($filters['user_id'])) {
            $builder->where('user_id', $filters['user_id']);
        }
        
        if (!empty($filters['module'])) {
            $builder->where('module', $filters['module']);
        }
        
        if (!empty($filters['action'])) {
            $builder->where('action', $filters['action']);
        }
        
        if (!empty($filters['severity'])) {
            $builder->where('severity', $filters['severity']);
        }
        
        if (!empty($filters['entity_type'])) {
            $builder->where('entity_type', $filters['entity_type']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                   ->like('description', $filters['search'])
                   ->orLike('user_name', $filters['search'])
                   ->orLike('user_email', $filters['search'])
                   ->groupEnd();
        }
        
        return $builder->orderBy('created_at', 'DESC')
                      ->limit($limit, $offset)
                      ->get()
                      ->getResultArray();
    }

    /**
     * Get total count of activity logs with filters
     */
    public function getActivityLogsCount($filters = [])
    {
        $builder = $this->builder();
        
        // Apply same filters as getActivityLogs
        if (!empty($filters['user_id'])) {
            $builder->where('user_id', $filters['user_id']);
        }
        
        if (!empty($filters['module'])) {
            $builder->where('module', $filters['module']);
        }
        
        if (!empty($filters['action'])) {
            $builder->where('action', $filters['action']);
        }
        
        if (!empty($filters['severity'])) {
            $builder->where('severity', $filters['severity']);
        }
        
        if (!empty($filters['entity_type'])) {
            $builder->where('entity_type', $filters['entity_type']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to']);
        }
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                   ->like('description', $filters['search'])
                   ->orLike('user_name', $filters['search'])
                   ->orLike('user_email', $filters['search'])
                   ->groupEnd();
        }
        
        return $builder->countAllResults();
    }

    /**
     * Get unique modules for filter dropdown
     */
    public function getUniqueModules()
    {
        return $this->builder()
                   ->select('module')
                   ->distinct()
                   ->orderBy('module', 'ASC')
                   ->get()
                   ->getResultArray();
    }

    /**
     * Get unique actions for filter dropdown
     */
    public function getUniqueActions()
    {
        return $this->builder()
                   ->select('action')
                   ->distinct()
                   ->orderBy('action', 'ASC')
                   ->get()
                   ->getResultArray();
    }

    /**
     * Get activity statistics
     */
    public function getActivityStats($days = 30)
    {
        $dateFrom = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        return [
            'total_activities' => $this->where('created_at >=', $dateFrom)->countAllResults(false),
            'by_module' => $this->select('module, COUNT(*) as count')
                               ->where('created_at >=', $dateFrom)
                               ->groupBy('module')
                               ->orderBy('count', 'DESC')
                               ->get()
                               ->getResultArray(),
            'by_severity' => $this->select('severity, COUNT(*) as count')
                                 ->where('created_at >=', $dateFrom)
                                 ->groupBy('severity')
                                 ->orderBy('count', 'DESC')
                                 ->get()
                                 ->getResultArray(),
            'recent_activities' => $this->where('created_at >=', $dateFrom)
                                       ->orderBy('created_at', 'DESC')
                                       ->limit(10)
                                       ->get()
                                       ->getResultArray()
        ];
    }

    /**
     * Clean old activity logs (older than specified days)
     */
    public function cleanOldLogs($days = 365)
    {
        $dateThreshold = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('created_at <', $dateThreshold)->delete();
    }
}