<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\ClaimModel;
use App\Models\UserModel;

class ReportsController extends BaseController
{
    protected $itemModel;
    protected $claimModel;
    protected $userModel;

    public function __construct()
    {
        helper(['form', 'url', 'session']);
        $this->itemModel = new ItemModel();
        $this->claimModel = new ClaimModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Get overview statistics
        $data = [
            'totalItems' => $this->itemModel->countAll(),
            'totalClaims' => $this->claimModel->countAll(),
            'pendingClaims' => $this->claimModel->where('status', 'pending')->countAllResults(),
            'verifiedClaims' => $this->claimModel->where('status', 'verified')->countAllResults(),
            'rejectedClaims' => $this->claimModel->where('status', 'rejected')->countAllResults(),
            'claimedItems' => $this->itemModel->where('status', 'claimed')->countAllResults(),
            'pendingItems' => $this->itemModel->where('status', 'pending')->countAllResults(),
            
            // Recent activity
            'recentItems' => $this->itemModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'recentClaims' => $this->claimModel
                ->select('claims.*, items.name as item_name')
                ->join('items', 'items.id = claims.item_id', 'left')
                ->orderBy('claims.created_at', 'DESC')
                ->limit(5)
                ->findAll(),
            
            // Monthly trends
            'monthlyItemsData' => $this->getMonthlyItemsData(),
            'monthlyClaimsData' => $this->getMonthlyClaimsData(),
            
            // Category breakdown
            'categoryData' => $this->getCategoryBreakdown(),
            
            // Status distribution
            'itemStatusData' => $this->getItemStatusDistribution(),
            'claimStatusData' => $this->getClaimStatusDistribution(),
            
            // Performance metrics
            'averageClaimTime' => $this->getAverageClaimTime(),
            'successRate' => $this->getClaimSuccessRate()
        ];

        return view('reports/index', $data);
    }

    public function itemsReport()
    {
        $dateFrom = $this->request->getGet('date_from') ?: date('Y-m-01');
        $dateTo = $this->request->getGet('date_to') ?: date('Y-m-d');
        $category = $this->request->getGet('category');
        $status = $this->request->getGet('status');

        $builder = $this->itemModel->builder();
        $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        $builder->where('created_at <=', $dateTo . ' 23:59:59');

        if ($category) {
            $builder->where('category', $category);
        }

        if ($status) {
            $builder->where('status', $status);
        }

        $items = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();

        $data = [
            'items' => $items,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'selectedCategory' => $category,
            'selectedStatus' => $status,
            'categories' => $this->getUniqueCategories(),
            'totalItems' => count($items),
            'categoryBreakdown' => $this->getCategoryBreakdown($dateFrom, $dateTo),
            'statusBreakdown' => $this->getItemStatusDistribution($dateFrom, $dateTo)
        ];

        return view('reports/items', $data);
    }

    public function claimsReport()
    {
        $dateFrom = $this->request->getGet('date_from') ?: date('Y-m-01');
        $dateTo = $this->request->getGet('date_to') ?: date('Y-m-d');
        $status = $this->request->getGet('status');

        $builder = $this->claimModel->builder();
        $builder->select('claims.*, items.name as item_name, items.category as item_category');
        $builder->join('items', 'items.id = claims.item_id', 'left');
        $builder->where('claims.created_at >=', $dateFrom . ' 00:00:00');
        $builder->where('claims.created_at <=', $dateTo . ' 23:59:59');

        if ($status) {
            $builder->where('claims.status', $status);
        }

        $claims = $builder->orderBy('claims.created_at', 'DESC')->get()->getResultArray();

        $data = [
            'claims' => $claims,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'selectedStatus' => $status,
            'totalClaims' => count($claims),
            'statusBreakdown' => $this->getClaimStatusDistribution($dateFrom, $dateTo),
            'averageProcessingTime' => $this->getAverageClaimTime($dateFrom, $dateTo)
        ];

        return view('reports/claims', $data);
    }

    public function analytics()
    {
        $data = [
            'dailyTrends' => $this->getDailyTrends(),
            'categoryTrends' => $this->getCategoryTrends(),
            'claimEfficiency' => $this->getClaimEfficiencyMetrics(),
            'peakHours' => $this->getPeakHours(),
            'topCategories' => $this->getTopCategories(),
            'monthlyComparison' => $this->getMonthlyComparison()
        ];

        return view('reports/analytics', $data);
    }

    public function export()
    {
        $type = $this->request->getGet('type') ?: 'items';
        $format = $this->request->getGet('format') ?: 'csv';
        $dateFrom = $this->request->getGet('date_from') ?: date('Y-m-01');
        $dateTo = $this->request->getGet('date_to') ?: date('Y-m-d');

        if ($type === 'items') {
            $data = $this->getItemsForExport($dateFrom, $dateTo);
            $filename = 'items_report_' . date('Y-m-d');
        } else {
            $data = $this->getClaimsForExport($dateFrom, $dateTo);
            $filename = 'claims_report_' . date('Y-m-d');
        }

        if ($format === 'csv') {
            return $this->exportToCsv($data, $filename);
        } else {
            return $this->exportToJson($data, $filename);
        }
    }

    // Helper methods for data analysis
    private function getMonthlyItemsData()
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                FROM items 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month";
        
        return $this->itemModel->db->query($sql)->getResultArray();
    }

    private function getMonthlyClaimsData()
    {
        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
                FROM claims 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month";
        
        return $this->claimModel->db->query($sql)->getResultArray();
    }

    private function getCategoryBreakdown($dateFrom = null, $dateTo = null)
    {
        $builder = $this->itemModel->builder();
        $builder->select('category, COUNT(*) as count');
        
        if ($dateFrom && $dateTo) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }
        
        return $builder->groupBy('category')->get()->getResultArray();
    }

    private function getItemStatusDistribution($dateFrom = null, $dateTo = null)
    {
        $builder = $this->itemModel->builder();
        $builder->select('status, COUNT(*) as count');
        
        if ($dateFrom && $dateTo) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }
        
        return $builder->groupBy('status')->get()->getResultArray();
    }

    private function getClaimStatusDistribution($dateFrom = null, $dateTo = null)
    {
        $builder = $this->claimModel->builder();
        $builder->select('status, COUNT(*) as count');
        
        if ($dateFrom && $dateTo) {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }
        
        return $builder->groupBy('status')->get()->getResultArray();
    }

    private function getAverageClaimTime($dateFrom = null, $dateTo = null)
    {
        $sql = "SELECT AVG(TIMESTAMPDIFF(HOUR, claims.created_at, claims.updated_at)) as avg_hours
                FROM claims 
                WHERE status IN ('verified', 'rejected')";
        
        if ($dateFrom && $dateTo) {
            $sql .= " AND created_at >= '$dateFrom 00:00:00' AND created_at <= '$dateTo 23:59:59'";
        }
        
        $result = $this->claimModel->db->query($sql)->getRowArray();
        return round($result['avg_hours'] ?? 0, 2);
    }

    private function getClaimSuccessRate()
    {
        $total = $this->claimModel->where('status !=', 'pending')->countAllResults();
        $verified = $this->claimModel->where('status', 'verified')->countAllResults();
        
        return $total > 0 ? round(($verified / $total) * 100, 2) : 0;
    }

    private function getDailyTrends()
    {
        $sql = "SELECT DATE(created_at) as date, COUNT(*) as items_count
                FROM items 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at)
                ORDER BY date";
        
        return $this->itemModel->db->query($sql)->getResultArray();
    }

    private function getCategoryTrends()
    {
        $sql = "SELECT category, 
                       COUNT(*) as total,
                       SUM(CASE WHEN status = 'claimed' THEN 1 ELSE 0 END) as claimed
                FROM items 
                GROUP BY category
                ORDER BY total DESC";
        
        return $this->itemModel->db->query($sql)->getResultArray();
    }

    private function getClaimEfficiencyMetrics()
    {
        $sql = "SELECT 
                    AVG(CASE WHEN status = 'verified' THEN TIMESTAMPDIFF(HOUR, created_at, updated_at) END) as avg_approval_time,
                    AVG(CASE WHEN status = 'rejected' THEN TIMESTAMPDIFF(HOUR, created_at, updated_at) END) as avg_rejection_time,
                    COUNT(CASE WHEN status = 'verified' THEN 1 END) as verified_count,
                    COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected_count,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count
                FROM claims";
        
        return $this->claimModel->db->query($sql)->getRowArray();
    }

    private function getPeakHours()
    {
        $sql = "SELECT HOUR(created_at) as hour, COUNT(*) as count
                FROM items 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY HOUR(created_at)
                ORDER BY count DESC";
        
        return $this->itemModel->db->query($sql)->getResultArray();
    }

    private function getTopCategories()
    {
        return $this->itemModel->builder()
            ->select('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('count', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
    }

    private function getMonthlyComparison()
    {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as items_count,
                    (SELECT COUNT(*) FROM claims WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(items.created_at, '%Y-%m')) as claims_count
                FROM items 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month";
        
        return $this->itemModel->db->query($sql)->getResultArray();
    }

    private function getUniqueCategories()
    {
        return $this->itemModel->builder()
            ->select('category')
            ->distinct()
            ->get()
            ->getResultArray();
    }

    private function getItemsForExport($dateFrom, $dateTo)
    {
        return $this->itemModel->builder()
            ->where('created_at >=', $dateFrom . ' 00:00:00')
            ->where('created_at <=', $dateTo . ' 23:59:59')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    private function getClaimsForExport($dateFrom, $dateTo)
    {
        return $this->claimModel->builder()
            ->select('claims.*, items.name as item_name, items.category as item_category')
            ->join('items', 'items.id = claims.item_id', 'left')
            ->where('claims.created_at >=', $dateFrom . ' 00:00:00')
            ->where('claims.created_at <=', $dateTo . ' 23:59:59')
            ->orderBy('claims.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    private function exportToCsv($data, $filename)
    {
        $response = service('response');
        $response->setHeader('Content-Type', 'text/csv');
        $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');

        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        return $response;
    }

    private function exportToJson($data, $filename)
    {
        $response = service('response');
        $response->setHeader('Content-Type', 'application/json');
        $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.json"');
        
        return $response->setJSON($data);
    }
}