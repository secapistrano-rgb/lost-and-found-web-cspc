<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\ClaimModel;
use App\Models\UserModel;
use App\Helpers\ActivityLogger;

class ReportsController extends BaseController
{
    protected $itemModel;
    protected $claimModel;
    protected $userModel;
    protected $db;
    private $activityLogger;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        $this->itemModel = new ItemModel();
        $this->claimModel = new ClaimModel();
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
        $this->activityLogger = ActivityLogger::getInstance();
    }

    public function index()
    {
        $data = [
            'totalItems'           => $this->itemModel->countAll(),
            'claimSuccessRate'     => $this->getClaimSuccessRate(),
            'averageClaimTime'     => $this->getAverageClaimTime(),
            'activeUsers'          => $this->getActiveUsersCount(),
            'emailsSent'           => $this->getTotalEmailsSent(),
            'monthlyItems'         => $this->getMonthlyItemsData(),
            'monthlyClaims'        => $this->getMonthlyClaimsData(),
            'dailyTrends'          => $this->getDailyTrendsData(),
            'claimStatus'          => $this->getClaimStatusDistribution(),
            'claimedVsReturned'    => $this->getClaimedVsReturnedData(),
            'itemsPerCategory'     => $this->getItemsPerCategory(),
            'recentClaims'         => $this->getRecentClaims(),
            'topUsers'             => $this->getTopUsers(),
            'totalEmailsSent'      => $this->getTotalEmailsSent()
        ];

        // Log dashboard view activity
        $this->activityLogger->log(
            'Reports',
            'view_dashboard',
            'User viewed the reports dashboard',
            [
                'severity' => 'low',
                'entity_type' => 'reports',
                'entity_id' => session()->get('user_id'),
                'new_values' => [
                    'total_items' => $data['totalItems'],
                    'claim_success_rate' => $data['claimSuccessRate'],
                    'active_users' => $data['activeUsers']
                ]
            ]
        );

        return view('reports/index', $data);
    }

    private function getClaimSuccessRate($filters = [])
    {
        $builder = $this->claimModel->builder();
        
        // Apply filters manually to avoid ambiguous column references
        if (!empty($filters['search'])) {
            $builder->join('items i', 'claims.item_id = i.id', 'left');
            $builder->groupStart()
                   ->like('i.title', $filters['search'])
                   ->orLike('i.description', $filters['search'])
                   ->groupEnd();
        }
        
        if (!empty($filters['status'])) {
            $builder->where('claims.status', $filters['status']);
        }
        
        if (!empty($filters['category'])) {
            if (empty($filters['search'])) {
                $builder->join('items i', 'claims.item_id = i.id', 'left');
            }
            $builder->where('i.category', $filters['category']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('claims.created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('claims.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        $totalClaims = $builder->countAllResults();
        
        if ($totalClaims == 0) return 0;
        
        $builder = $this->claimModel->builder();
        
        // Apply filters manually to avoid ambiguous column references
        if (!empty($filters['search'])) {
            $builder->join('items i', 'claims.item_id = i.id', 'left');
            $builder->groupStart()
                   ->like('i.title', $filters['search'])
                   ->orLike('i.description', $filters['search'])
                   ->groupEnd();
        }
        
        if (!empty($filters['status'])) {
            $builder->where('claims.status', $filters['status']);
        }
        
        if (!empty($filters['category'])) {
            if (empty($filters['search'])) {
                $builder->join('items i', 'claims.item_id = i.id', 'left');
            }
            $builder->where('i.category', $filters['category']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('claims.created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('claims.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        $builder->where('claims.status', 'resolved');
        $successfulClaims = $builder->countAllResults();
        
        return round(($successfulClaims / $totalClaims) * 100, 0);
    }

    private function getAverageClaimTime($filters = [])
    {
        $builder = $this->db->table('claims c');
        $builder->select('AVG(DATEDIFF(c.updated_at, c.created_at)) as avg_days')
                ->join('items i', 'c.item_id = i.id', 'inner')
                ->where('c.status', 'resolved')
                ->where('i.status', 'returned');
        
        // Apply filters
        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('i.title', $filters['search'])
                    ->orLike('i.description', $filters['search'])
                    ->orLike('i.location', $filters['search'])
                    ->groupEnd();
        }
        
        if (!empty($filters['category'])) {
            $builder->where('i.category', $filters['category']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('c.created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('c.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        $result = $builder->get()->getRowArray();
        return $result ? round($result['avg_days'], 1) : 0;
    }

    private function getActiveUsersCount()
    {
        $sql = "SELECT COUNT(DISTINCT active_users.user_id) as active_count 
                FROM (
                    SELECT reporter_id as user_id FROM items 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    UNION
                    SELECT claimant_id as user_id FROM claims 
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ) as active_users
                WHERE active_users.user_id IS NOT NULL";
        $result = $this->db->query($sql)->getRowArray();
        return $result ? (int)$result['active_count'] : 0;
    }

    private function getNotificationsSentRate()
    {
        $processedClaims = $this->claimModel
            ->groupStart()
            ->where('status', 'approved')
            ->orWhere('status', 'rejected')
            ->groupEnd()
            ->countAllResults();
        return $processedClaims;
    }

    private function getMonthlyItemsData()
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                FROM items 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month";
        $result = $this->db->query($sql)->getResultArray();
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[$month] = ['month' => $month, 'count' => 0];
        }
        foreach ($result as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['count'] = (int)$row['count'];
            }
        }
        return array_values($months);
    }

    private function getMonthlyClaimsData()
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                FROM claims 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month";
        $result = $this->db->query($sql)->getResultArray();
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[$month] = ['month' => $month, 'count' => 0];
        }
        foreach ($result as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['count'] = (int)$row['count'];
            }
        }
        return array_values($months);
    }

    private function getDailyTrendsData()
    {
        $days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $days[$date] = ['found' => 0, 'lost' => 0];
        }

        $sqlFound = "SELECT DATE(created_at) as date, COUNT(*) as count 
                     FROM items 
                     WHERE type = 'found' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                     GROUP BY DATE(created_at)";
        $resultsFound = $this->db->query($sqlFound)->getResultArray() ?: [];
        foreach ($resultsFound as $row) {
            if (isset($days[$row['date']])) {
                $days[$row['date']]['found'] = (int)$row['count'];
            }
        }

        $sqlLost = "SELECT DATE(created_at) as date, COUNT(*) as count 
                    FROM items 
                    WHERE type = 'lost' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    GROUP BY DATE(created_at)";
        $resultsLost = $this->db->query($sqlLost)->getResultArray() ?: [];
        foreach ($resultsLost as $row) {
            if (isset($days[$row['date']])) {
                $days[$row['date']]['lost'] = (int)$row['count'];
            }
        }

        $trendData = [];
        foreach ($days as $date => $counts) {
            $trendData[] = [
                'date' => $date,
                'items_found' => $counts['found'],
                'items_lost' => $counts['lost']
            ];
        }
        return $trendData;
    }

    private function getItemsPerCategory($filters = [])
    {
        $builder = $this->itemModel->builder();
        $builder->select('category, COUNT(*) as count')
                ->where('type', 'lost');
        
        $this->applyItemFilters($builder, $filters);
        
        $results = $builder->groupBy('category')
                          ->orderBy('count', 'DESC')
                          ->get()
                          ->getResultArray();
        
        return $results ?: [];
    }

    private function getRecentClaims($filters = [])
    {
        $builder = $this->claimModel->builder();
        $builder->select('claims.id, claims.item_id, claims.match_id, claims.claimant_id, claims.claim_type, claims.status, claims.proof_of_ownership, claims.image, claims.google_user_id, claims.created_at, claims.updated_at, items.title as item_name, users.full_name as claimant_name, COALESCE(users.email, claims.claimant_id) as claimant_email')
                ->join('items', 'items.id = claims.item_id', 'left')
                ->join('users', 'users.email = claims.claimant_id', 'left');
        
        // Apply filters manually to avoid duplicate joins
        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('items.title', $filters['search'])
                    ->orLike('items.description', $filters['search'])
                    ->orLike('items.location', $filters['search'])
                    ->groupEnd();
        }
        
        if (!empty($filters['status'])) {
            $builder->where('claims.status', $filters['status']);
        }
        
        if (!empty($filters['category'])) {
            $builder->where('items.category', $filters['category']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('claims.created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('claims.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        return $builder->orderBy('claims.created_at', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    private function getTopUsers()
    {
        $sql = "SELECT u.id, u.full_name as name, COUNT(i.id) as report_count
                FROM users u
                LEFT JOIN items i ON u.email = i.reporter_id
                GROUP BY u.id, u.full_name
                HAVING report_count > 0
                ORDER BY report_count DESC
                LIMIT 5";
        $results = $this->db->query($sql)->getResultArray() ?: [];
        return $results;
    }

    private function getTotalEmailsSent()
    {
        // Change 'email_logs' to your actual table name
        $sql = "SELECT COUNT(*) as total FROM email_logs";
        $result = $this->db->query($sql)->getRowArray();
        return $result ? (int)$result['total'] : 0;
    }

    private function getFilteredItemCount($filters = [])
    {
        $builder = $this->itemModel->builder();
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('title', $filters['search'])
                    ->orLike('description', $filters['search'])
                    ->orLike('location_found', $filters['search'])
                    ->groupEnd();
        }
        
        if (!empty($filters['category'])) {
            $builder->where('category', $filters['category']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        return $builder->countAllResults();
    }

    private function applyClaimFilters($builder, $filters = [])
    {
        if (!empty($filters['search'])) {
            $builder->join('items i', 'claims.item_id = i.id', 'left')
                    ->groupStart()
                    ->like('i.title', $filters['search'])
                    ->orLike('i.description', $filters['search'])
                    ->orLike('i.location', $filters['search'])
                    ->groupEnd();
        }
        
        if (!empty($filters['status'])) {
            $builder->where('claims.status', $filters['status']);
        }
        
        if (!empty($filters['category'])) {
            if (empty($filters['search'])) {
                $builder->join('items i', 'claims.item_id = i.id', 'left');
            }
            $builder->where('i.category', $filters['category']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('claims.created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('claims.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
    }

    private function applyItemFilters($builder, $filters = [])
    {
        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('title', $filters['search'])
                    ->orLike('description', $filters['search'])
                    ->orLike('location', $filters['search'])
                    ->groupEnd();
        }
        
        if (!empty($filters['category'])) {
            $builder->where('category', $filters['category']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
     }

    // Filtered versions of methods that don't need complex filtering
    private function getMonthlyItemsDataFiltered($filters = [])
    {
        // For now, return original data - can be enhanced later
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                FROM items 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month";
        $result = $this->db->query($sql)->getResultArray();
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[$month] = ['month' => $month, 'count' => 0];
        }
        foreach ($result as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['count'] = (int)$row['count'];
            }
        }
        return array_values($months);
    }

    private function getMonthlyClaimsDataFiltered($filters = [])
    {
        // For now, return original data - can be enhanced later  
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                FROM claims 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month";
        $result = $this->db->query($sql)->getResultArray();
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[$month] = ['month' => $month, 'count' => 0];
        }
        foreach ($result as $row) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['count'] = (int)$row['count'];
            }
        }
        return array_values($months);
    }

    private function getDailyTrendsDataFiltered($filters = [])
    {
        // Initialize all 30 days with zero values like the original method
        $days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $days[$date] = ['found' => 0, 'lost' => 0];
        }

        // Build the base query for found items
        $foundQuery = $this->db->table('items i')
            ->select('DATE(i.created_at) as date, COUNT(*) as count')
            ->where('i.type', 'found')
            ->where('i.created_at >=', date('Y-m-d', strtotime('-30 days')));

        // Build the base query for lost items  
        $lostQuery = $this->db->table('items i')
            ->select('DATE(i.created_at) as date, COUNT(*) as count')
            ->where('i.type', 'lost')
            ->where('i.created_at >=', date('Y-m-d', strtotime('-30 days')));

        // Apply filters to both queries
        if (!empty($filters['status'])) {
            $foundQuery->join('claims c', 'c.item_id = i.id', 'inner')
                       ->where('c.status', $filters['status']);
            $lostQuery->join('claims c', 'c.item_id = i.id', 'inner')
                      ->where('c.status', $filters['status']);
        }
            
        if (!empty($filters['category'])) {
            $foundQuery->where('i.category', $filters['category']);
            $lostQuery->where('i.category', $filters['category']);
        }
        
        if (!empty($filters['search'])) {
            $foundQuery->groupStart()
                ->like('i.title', $filters['search'])
                ->orLike('i.description', $filters['search'])
                ->orLike('i.category', $filters['search'])
                ->groupEnd();
            $lostQuery->groupStart()
                ->like('i.title', $filters['search'])
                ->orLike('i.description', $filters['search'])
                ->orLike('i.category', $filters['search'])
                ->groupEnd();
        }
        
        if (!empty($filters['date_from'])) {
            $foundQuery->where('i.created_at >=', $filters['date_from']);
            $lostQuery->where('i.created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $foundQuery->where('i.created_at <=', $filters['date_to'] . ' 23:59:59');
            $lostQuery->where('i.created_at <=', $filters['date_to'] . ' 23:59:59');
        }

        // Execute queries and populate the days array
        $resultsFound = $foundQuery->groupBy('DATE(i.created_at)')->get()->getResultArray() ?: [];
        foreach ($resultsFound as $row) {
            if (isset($days[$row['date']])) {
                $days[$row['date']]['found'] = (int)$row['count'];
            }
        }

        $resultsLost = $lostQuery->groupBy('DATE(i.created_at)')->get()->getResultArray() ?: [];
        foreach ($resultsLost as $row) {
            if (isset($days[$row['date']])) {
                $days[$row['date']]['lost'] = (int)$row['count'];
            }
        }

        // Convert to the expected format
        $trendData = [];
        foreach ($days as $date => $counts) {
            $trendData[] = [
                'date' => $date,
                'items_found' => $counts['found'],
                'items_lost' => $counts['lost']
            ];
        }
        
        return $trendData;
    }

    private function getClaimStatusDistribution($filters = [])
    {
        $builder = $this->claimModel->builder();
        $builder->select('claims.status, COUNT(*) as count');
        
        // Apply filters manually to avoid ambiguous column references
        if (!empty($filters['search'])) {
            $builder->join('items i', 'claims.item_id = i.id', 'left');
            $builder->groupStart()
                   ->like('i.title', $filters['search'])
                   ->orLike('i.description', $filters['search'])
                   ->groupEnd();
        }
        
        if (!empty($filters['status'])) {
            $builder->where('claims.status', $filters['status']);
        }
        
        if (!empty($filters['category'])) {
            if (empty($filters['search'])) {
                $builder->join('items i', 'claims.item_id = i.id', 'left');
            }
            $builder->where('i.category', $filters['category']);
        }
        
        if (!empty($filters['date_from'])) {
            $builder->where('claims.created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $builder->where('claims.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        $results = $builder->groupBy('claims.status')->get()->getResultArray();
        $data = array_fill_keys(['pending', 'approved', 'rejected', 'resolved'], 0);
        
        foreach ($results as $row) {
            if (isset($data[$row['status']])) {
                $data[$row['status']] = (int)$row['count'];
            }
        }
        
        return $data;
    }

    private function getClaimedVsReturnedData($filters = [])
    {
        $builder = $this->itemModel->builder();
        $builder->select('
            SUM(CASE WHEN status = "claimed" THEN 1 ELSE 0 END) as claimed,
            SUM(CASE WHEN status = "returned" THEN 1 ELSE 0 END) as returned
        ');
        
        $this->applyItemFilters($builder, $filters);
        
        $result = $builder->get()->getRowArray();
        return [
            'claimed' => $result['claimed'] ?? 0,
            'returned' => $result['returned'] ?? 0
        ];
    }

    private function getTopUsersFiltered($filters = [])
    {
        $sql = "SELECT u.id, u.full_name as name, COUNT(i.id) as report_count
                FROM users u
                LEFT JOIN items i ON u.email = i.reporter_id
                GROUP BY u.id, u.full_name
                HAVING report_count > 0
                ORDER BY report_count DESC
                LIMIT 5";
        $results = $this->db->query($sql)->getResultArray() ?: [];
        return $results;
    }

    public function export()
    {
        // Get filter parameters from request
        $filters = [
            'search' => $this->request->getGet('search'),
            'status' => $this->request->getGet('status'),
            'category' => $this->request->getGet('category'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to')
        ];

        // Get filtered data for the report
        $data = [
            'totalItems'           => $this->getFilteredItemCount($filters),
            'claimSuccessRate'     => $this->getClaimSuccessRate($filters),
            'averageClaimTime'     => $this->getAverageClaimTime($filters),
            'activeUsers'          => $this->getActiveUsersCount(),
            'emailsSent'           => $this->getTotalEmailsSent(),
            'monthlyItems'         => $this->getMonthlyItemsDataFiltered($filters),
            'monthlyClaims'        => $this->getMonthlyClaimsDataFiltered($filters),
            'dailyTrends'          => $this->getDailyTrendsDataFiltered($filters),
            'claimStatus'          => $this->getClaimStatusDistribution($filters),
            'claimedVsReturned'    => $this->getClaimedVsReturnedData($filters),
            'itemsPerCategory'     => $this->getItemsPerCategory($filters),
            'allClaims'            => $this->getRecentClaims($filters),
            'topUsers'             => $this->getTopUsersFiltered($filters),
            'totalEmailsSent'      => $this->getTotalEmailsSent(),
            'reportDate'           => date('F j, Y'),
            'reportTime'           => date('g:i A'),
            'filters'              => $filters // Include filter info in the report
        ];

        // Log report export activity
        $this->activityLogger->log(
            'Reports',
            'export_pdf',
            'User exported a PDF report',
            [
                'severity' => 'low',
                'entity_type' => 'reports',
                'entity_id' => session()->get('user_id'),
                'new_values' => [
                    'total_items' => $data['totalItems'],
                    'filters_applied' => json_encode($filters),
                    'report_date' => $data['reportDate']
                ]
            ]
        );

        // Load Dompdf
        require_once ROOTPATH . 'vendor/autoload.php';
        
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($this->generatePdfHtml($data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Output the generated PDF
        $filename = 'CSPC_Lost_Found_Report_' . date('Y-m-d_H-i-s') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    private function generatePdfHtml($data)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>CSPC Lost and Found - Reports & Analytics</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                    margin: 0;
                    padding: 30px;
                    color: #2c3e50;
                    background: #ffffff;
                    line-height: 1.6;
                }
                .header {
                    text-align: center;
                    margin-bottom: 40px;
                    border-bottom: 3px solid #1e3a8a;
                    padding: 30px;
                    background: #ffffff;
                    border-radius: 0;
                    margin: -30px -30px 40px -30px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                .header h1 {
                    color: #1e3a8a;
                    margin: 0 0 10px 0;
                    font-size: 28px;
                    font-weight: 700;
                    letter-spacing: 0.5px;
                }
                .header h2 {
                    color: #3b82f6;
                    margin: 5px 0;
                    font-size: 18px;
                    font-weight: 500;
                }
                .header p {
                    margin: 8px 0;
                    color: #64748b;
                    font-size: 13px;
                }
                .header .subtitle {
                    font-size: 14px;
                    font-weight: 600;
                    color: #f59e0b;
                    margin-top: 10px;
                }
                .stats-grid {
                    display: table;
                    width: 100%;
                    margin-bottom: 40px;
                    border-spacing: 15px;
                }
                .stat-row {
                    display: table-row;
                }
                .stat-card {
                    display: table-cell;
                    width: 50%;
                    border: 2px solid #e5e7eb;
                    border-radius: 10px;
                    padding: 20px;
                    text-align: center;
                    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                }
                .stat-value {
                    font-size: 36px;
                    font-weight: 700;
                    color: #1e40af;
                    margin-bottom: 8px;
                    display: block;
                }
                .stat-label {
                    font-size: 13px;
                    color: #64748b;
                    font-weight: 500;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                .section {
                    margin-bottom: 35px;
                    page-break-inside: avoid;
                }
                .section h2 {
                    color: #1e3a8a;
                    border-bottom: 2px solid #3b82f6;
                    padding-bottom: 12px;
                    margin-bottom: 20px;
                    font-size: 18px;
                    font-weight: 600;
                    letter-spacing: 0.3px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 25px;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                }
                th, td {
                    border: 1px solid #e5e7eb;
                    padding: 12px 15px;
                    text-align: left;
                    font-size: 13px;
                }
                th {
                    background-color: #1e40af;
                    color: #ffffff;
                    font-weight: 600;
                    text-transform: uppercase;
                    font-size: 11px;
                    letter-spacing: 0.5px;
                }
                td {
                    background-color: #ffffff;
                }
                tr:nth-child(even) td {
                    background-color: #f8fafc;
                }
                tr:hover td {
                    background-color: #eff6ff;
                }
                .footer {
                    margin-top: 50px;
                    text-align: center;
                    font-size: 11px;
                    color: #64748b;
                    border-top: 2px solid #e5e7eb;
                    padding-top: 25px;
                    line-height: 1.8;
                }
                .footer p {
                    margin: 5px 0;
                }
                .footer strong {
                    color: #1e3a8a;
                    font-weight: 600;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>CSPC Lost and Found Management System</h1>
                <h2>Reports & Analytics Dashboard</h2>
                <p class="subtitle">Supreme Student Council (SSC)</p>
                <p>Report Generated: ' . $data['reportDate'] . ' at ' . $data['reportTime'] . '</p>
            </div>

            <div class="stats-grid">
                <div class="stat-row">
                    <div class="stat-card">
                        <span class="stat-value">' . number_format($data['totalItems']) . '</span>
                        <div class="stat-label">Total Reported Items</div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value">' . $data['claimSuccessRate'] . '%</span>
                        <div class="stat-label">Claim Success Rate</div>
                    </div>
                </div>
                <div class="stat-row">
                    <div class="stat-card">
                        <span class="stat-value">' . $data['averageClaimTime'] . '</span>
                        <div class="stat-label">Avg. Claim Processing Time (Days)</div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-value">' . number_format($data['activeUsers']) . '</span>
                        <div class="stat-label">Active Users (30 Days)</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Claim Status Distribution</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($data['claimStatus'] as $status => $count) {
            $html .= '<tr><td>' . ucfirst($status) . '</td><td>' . $count . '</td></tr>';
        }
        
        $html .= '
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2>Items by Category</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($data['itemsPerCategory'] as $item) {
            $html .= '<tr><td>' . $item['category'] . '</td><td>' . $item['count'] . '</td></tr>';
        }
        
        $html .= '
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2>All Claims Report</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Claimant</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($data['allClaims'] as $claim) {
            $html .= '<tr>
                        <td>' . ($claim['item_name'] ?? 'N/A') . '</td>
                        <td>' . ($claim['claimant_name'] ?? 'N/A') . '</td>
                        <td>' . ucfirst($claim['status']) . '</td>
                        <td>' . date('M j, Y', strtotime($claim['created_at'])) . '</td>
                      </tr>';
        }
        
        $html .= '
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2>Top Users by Reports</h2>
                <table>
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Report Count</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($data['topUsers'] as $user) {
            $html .= '<tr><td>' . $user['name'] . '</td><td>' . $user['report_count'] . '</td></tr>';
        }
        
        $html .= '
                    </tbody>
                </table>
            </div>

            <div class="footer">
                <p><strong>Supreme Student Council (SSC)</strong></p>
                <p>Camarines Sur Polytechnic Colleges (CSPC)</p>
                <p>This report was automatically generated by the CSPC Lost and Found Management System</p>
                <p>© ' . date('Y') . ' CSPC. All rights reserved. | Confidential Document</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    public function search()
    {
        // Get filter parameters
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $category = $this->request->getGet('category');
        $dateFrom = $this->request->getGet('dateFrom');
        $dateTo = $this->request->getGet('dateTo');
        
        // Log search activity
        $this->activityLogger->log(
            'Reports',
            'search_filter',
            'User performed a filtered search on reports',
            [
                'severity' => 'low',
                'entity_type' => 'reports',
                'entity_id' => session()->get('user_id'),
                'new_values' => [
                    'search_query' => $search,
                    'status_filter' => $status,
                    'category_filter' => $category,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo
                ]
            ]
        );
        
        // Build filtered data
        $data = [];
        
        // Filter items by category if specified
        if ($category || $search || $status) {
            $itemsQuery = $this->db->table('items i')
                ->select('i.category, COUNT(*) as count')
                ->where('i.type', 'lost');
            
            // Join with claims if status filter is applied
            if ($status) {
                $itemsQuery->join('claims c', 'c.item_id = i.id', 'inner')
                           ->where('c.status', $status);
            }
                
            if ($category) {
                $itemsQuery->where('i.category', $category);
            }
            
            if ($search) {
                $itemsQuery->groupStart()
                    ->like('i.title', $search)
                    ->orLike('i.description', $search)
                    ->orLike('i.category', $search)
                    ->groupEnd();
            }
            
            if ($dateFrom) {
                $itemsQuery->where('i.created_at >=', $dateFrom);
            }
            
            if ($dateTo) {
                $itemsQuery->where('i.created_at <=', $dateTo . ' 23:59:59');
            }
            
            $data['itemsPerCategory'] = $itemsQuery->groupBy('i.category')
                ->orderBy('count', 'DESC')
                ->get()
                ->getResultArray();
        } else {
            $data['itemsPerCategory'] = $this->getItemsPerCategory();
        }
        
        // Filter claims data
        $claimsQuery = $this->db->table('claims c')
            ->select('c.id, c.item_id, c.match_id, c.claimant_id, c.claim_type, c.status, c.proof_of_ownership, c.image, c.google_user_id, c.created_at, c.updated_at, i.title as item_name, u.full_name as claimant_name, COALESCE(u.email, c.claimant_id) as claimant_email')
            ->join('items i', 'i.id = c.item_id', 'left')
            ->join('users u', 'u.email = c.claimant_id', 'left');
            
        if ($status) {
            $claimsQuery->where('c.status', $status);
        }
        
        if ($search) {
            $claimsQuery->groupStart()
                ->like('i.title', $search)
                ->orLike('u.full_name', $search)
                ->groupEnd();
        }
        
        if ($dateFrom) {
            $claimsQuery->where('c.created_at >=', $dateFrom);
        }
        
        if ($dateTo) {
            $claimsQuery->where('c.created_at <=', $dateTo . ' 23:59:59');
        }
        
        $data['recentClaims'] = $claimsQuery->orderBy('c.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
            
        // Get updated claim status distribution
        $statusQuery = $this->db->table('claims')
            ->select('status, COUNT(*) as count')
            ->whereIn('status', ['pending', 'approved', 'rejected', 'resolved']);
            
        // Apply status filter - if a specific status is selected, only show that status
        if ($status) {
            $statusQuery->where('status', $status);
        }
            
        if ($dateFrom) {
            $statusQuery->where('created_at >=', $dateFrom);
        }
        
        if ($dateTo) {
            $statusQuery->where('created_at <=', $dateTo . ' 23:59:59');
        }
        
        $statusResults = $statusQuery->groupBy('status')->get()->getResultArray();
        $data['claimStatus'] = array_fill_keys(['pending', 'approved', 'rejected', 'resolved'], 0);
        
        foreach ($statusResults as $row) {
            if (isset($data['claimStatus'][$row['status']])) {
                $data['claimStatus'][$row['status']] = (int)$row['count'];
            }
        }
        
        // Get daily trends data with filters using the corrected method
        $data['dailyTrends'] = $this->getDailyTrendsDataFiltered([
            'status' => $status,
            'category' => $category,
            'search' => $search,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ]);
        
        // Return JSON response for AJAX
        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }
}
