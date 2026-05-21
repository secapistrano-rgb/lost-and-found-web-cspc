<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalyticsModel extends Model
{
    protected $table = 'analytics_data';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'metric_type',
        'metric_name',
        'metric_value',
        'metric_data',
        'date_recorded',
        'period_type',
        'category',
        'subcategory',
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'additional_data'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'metric_type' => 'required|in_list[counter,gauge,histogram,summary]',
        'metric_name' => 'required|min_length[2]|max_length[100]',
        'metric_value' => 'required|numeric',
        'date_recorded' => 'required|valid_date',
        'period_type' => 'required|in_list[hourly,daily,weekly,monthly,yearly]',
        'category' => 'required|min_length[2]|max_length[50]'
    ];

    protected $validationMessages = [
        'metric_type' => [
            'required' => 'Metric type is required.',
            'in_list' => 'Invalid metric type selected.'
        ],
        'metric_name' => [
            'required' => 'Metric name is required.',
            'min_length' => 'Metric name must be at least 2 characters long.'
        ],
        'metric_value' => [
            'required' => 'Metric value is required.',
            'numeric' => 'Metric value must be numeric.'
        ],
        'date_recorded' => [
            'required' => 'Date recorded is required.',
            'valid_date' => 'Please provide a valid date.'
        ],
        'period_type' => [
            'required' => 'Period type is required.',
            'in_list' => 'Invalid period type selected.'
        ],
        'category' => [
            'required' => 'Category is required.',
            'min_length' => 'Category must be at least 2 characters long.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Custom Methods

    /**
     * Record metric
     */
    public function recordMetric($metricName, $value, $category, $subcategory = null, $additionalData = null)
    {
        $data = [
            'metric_type' => 'counter',
            'metric_name' => $metricName,
            'metric_value' => $value,
            'date_recorded' => date('Y-m-d H:i:s'),
            'period_type' => 'daily',
            'category' => $category,
            'subcategory' => $subcategory,
            'user_id' => session('user_id'),
            'session_id' => session_id(),
            'ip_address' => $this->getClientIP(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'additional_data' => $additionalData ? json_encode($additionalData) : null
        ];
        
        return $this->insert($data);
    }



    /**
     * Get dashboard metrics
     */
    public function getDashboardMetrics($period = 'daily')
    {
        $db = \Config\Database::connect();
        
        switch ($period) {
            case 'weekly':
                $startDate = date('Y-m-d', strtotime('-7 days'));
                break;
            case 'monthly':
                $startDate = date('Y-m-d', strtotime('-30 days'));
                break;
            case 'yearly':
                $startDate = date('Y-m-d', strtotime('-365 days'));
                break;
            default:
                $startDate = date('Y-m-d');
        }
        
        $endDate = date('Y-m-d');
        
        return [
            'overview' => $this->getOverviewMetrics($startDate, $endDate, $db),
            'performance' => $this->getPerformanceMetrics($startDate, $endDate, $db),
            'user_engagement' => $this->getUserEngagementMetrics($startDate, $endDate, $db),
            'content_performance' => $this->getContentPerformanceMetrics($startDate, $endDate, $db)
        ];
    }

    /**
     * Get overview metrics
     */
    private function getOverviewMetrics($startDate, $endDate, $db)
    {
        return $this->where('date_recorded >=', $startDate)
                   ->where('date_recorded <=', $endDate)
                   ->countAllResults();
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics($startDate, $endDate, $db)
    {
        return $this->select('AVG(metric_value) as avg_value, COUNT(*) as total_metrics')
                   ->where('date_recorded >=', $startDate)
                   ->where('date_recorded <=', $endDate)
                   ->first();
    }

    /**
     * Get user engagement metrics
     */
    private function getUserEngagementMetrics($startDate, $endDate, $db)
    {
        return $this->select('category, COUNT(*) as count')
                   ->where('date_recorded >=', $startDate)
                   ->where('date_recorded <=', $endDate)
                   ->groupBy('category')
                   ->findAll();
    }

    /**
     * Get content performance metrics
     */
    private function getContentPerformanceMetrics($startDate, $endDate, $db)
    {
        return $this->select('metric_name, SUM(metric_value) as total_value')
                   ->where('date_recorded >=', $startDate)
                   ->where('date_recorded <=', $endDate)
                   ->where('category', 'content')
                   ->groupBy('metric_name')
                   ->orderBy('total_value', 'DESC')
                   ->limit(10)
                   ->findAll();
    }

    /**
     * Generate report data
     */
    public function generateReport($reportType, $startDate, $endDate, $filters = [])
    {
        $db = \Config\Database::connect();
        
        switch ($reportType) {
            case 'ticket_summary':
                return $this->generateTicketSummaryReport($startDate, $endDate, $db, $filters);
            case 'agent_performance':
                return $this->generateAgentPerformanceReport($startDate, $endDate, $db, $filters);
            case 'user_satisfaction':
                return $this->generateUserSatisfactionReport($startDate, $endDate, $db, $filters);
            case 'content_analytics':
                return $this->generateContentAnalyticsReport($startDate, $endDate, $db, $filters);
            default:
                return [];
        }
    }

    /**
     * Generate ticket summary report
     */
    private function generateTicketSummaryReport($startDate, $endDate, $db, $filters)
    {
        $builder = $this->select('DATE(date_recorded) as date, COUNT(*) as total_metrics, SUM(metric_value) as total_value')
                       ->where('date_recorded >=', $startDate)
                       ->where('date_recorded <=', $endDate);
        
        if (!empty($filters['category'])) {
            $builder->where('category', $filters['category']);
        }
        
        if (!empty($filters['metric_type'])) {
            $builder->where('metric_type', $filters['metric_type']);
        }
        
        return $builder->groupBy('DATE(date_recorded)')
                      ->orderBy('date')
                      ->findAll();
    }

    /**
     * Generate agent performance report
     */
    private function generateAgentPerformanceReport($startDate, $endDate, $db, $filters)
    {
        return $this->select('user_id, COUNT(*) as metrics_count, AVG(metric_value) as avg_value')
                   ->where('date_recorded >=', $startDate)
                   ->where('date_recorded <=', $endDate)
                   ->where('category', 'agent_performance')
                   ->groupBy('user_id')
                   ->orderBy('metrics_count', 'DESC')
                   ->findAll();
    }

    /**
     * Generate user satisfaction report
     */
    private function generateUserSatisfactionReport($startDate, $endDate, $db, $filters)
    {
        return $this->select('metric_value as rating, COUNT(*) as count')
                   ->where('date_recorded >=', $startDate)
                   ->where('date_recorded <=', $endDate)
                   ->where('category', 'satisfaction')
                   ->groupBy('metric_value')
                   ->orderBy('metric_value', 'DESC')
                   ->findAll();
    }

    /**
     * Generate content analytics report
     */
    private function generateContentAnalyticsReport($startDate, $endDate, $db, $filters)
    {
        return $this->select('metric_name, SUM(metric_value) as total_value, subcategory')
                   ->where('date_recorded >=', $startDate)
                   ->where('date_recorded <=', $endDate)
                   ->where('category', 'content')
                   ->groupBy('metric_name, subcategory')
                   ->orderBy('total_value', 'DESC')
                   ->limit(20)
                   ->findAll();
    }

    /**
     * Get client IP address
     */
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
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Clean old analytics data
     */
    public function cleanOldData($days = 365)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        return $this->where('created_at <', $cutoffDate)->delete();
    }
}