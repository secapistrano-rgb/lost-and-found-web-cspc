<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Helpers\ActivityLogger;

class Dashboard extends BaseController
{
    private $activityLogger;

    public function __construct()
    {
        $this->activityLogger = ActivityLogger::getInstance();
    }

    public function index()
    {
        $itemModel = new ItemModel();

        // Prepare data for the last 7 days chart
        $chartData = [
            'labels' => [],
            'lostData' => [],
            'foundData' => [],
        ];

        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dates[$date] = ['lost' => 0, 'found' => 0];
            $chartData['labels'][] = date('D', strtotime($date));
        }

        $dailyStats = $itemModel->select("DATE(created_at) as date, 
                                          SUM(CASE WHEN `type` = 'lost' THEN 1 ELSE 0 END) as lost_count,
                                          SUM(CASE WHEN `type` = 'found' THEN 1 ELSE 0 END) as found_count")
                                          ->where('created_at >=', date('Y-m-d', strtotime('-6 days')))
                                          ->groupBy('DATE(created_at)')
                                          ->orderBy('date', 'ASC')
                                          ->findAll();
        
        foreach ($dailyStats as $stat) {
            if (isset($dates[$stat['date']])) {
                $dates[$stat['date']]['lost'] = (int)$stat['lost_count'];
                $dates[$stat['date']]['found'] = (int)$stat['found_count'];
            }
        }

        foreach ($dates as $dateData) {
            $chartData['lostData'][] = $dateData['lost'];
            $chartData['foundData'][] = $dateData['found'];
        }
        
        $data = [
            'totalItems'   => $itemModel->countAll(),
            'pendingCount' => $itemModel->where('status', 'pending')->countAllResults(),
            'claimedCount' => $itemModel->where('status', 'claimed')->countAllResults(),
            'returnedCount' => $itemModel->where('status', 'returned')->countAllResults(),
            'lostCount'    => $itemModel->where('type', 'lost')->countAllResults(),
            'foundCount'   => $itemModel->where('type', 'found')->countAllResults(),
            'recentActivity' => $itemModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'chartData' => $chartData
        ];

        // Log dashboard view activity
        $this->activityLogger->log(
            'Dashboard',
            'View Dashboard',
            'User viewed the main dashboard with statistics and recent activity',
            [
                'severity' => 'low',
                'new_values' => [
                    'total_items' => $data['totalItems'],
                    'pending_count' => $data['pendingCount'],
                    'claimed_count' => $data['claimedCount'],
                    'returned_count' => $data['returnedCount'],
                    'lost_count' => $data['lostCount'],
                    'found_count' => $data['foundCount'],
                    'recent_activity_count' => count($data['recentActivity'])
                ]
            ]
        );
        
        return view('dashboard/home', $data);
    }

    public function items()
    {
        $itemModel = new ItemModel();

        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $status = $this->request->getGet('status');
        $type = $this->request->getGet('type');

        $itemModel->select('items.*, claims.status as claim_status')
                  ->join('claims', 'claims.item_id = items.id', 'left');

        $stats = $itemModel->selectSum('id', 'total_items')
            ->selectSum("CASE WHEN `type` = 'lost' THEN 1 ELSE 0 END", 'lost_count')
            ->selectSum("CASE WHEN `type` = 'found' THEN 1 ELSE 0 END", 'found_count')
            ->first();

        if ($search) {
            $itemModel->groupStart()
                      ->like('items.title', $search)
                      ->orLike('items.description', $search)
                      ->orLike('items.category', $search)
                      ->orLike('items.location', $search)
                      ->groupEnd();
        }

        if ($category) {
            $itemModel->where('items.category', $category);
        }

        if ($status) {
            if ($status === 'claimed') {
                $itemModel->where('claims.status', 'approved');
            } else {
                $itemModel->where('items.status', $status);
            }
        }

        if ($type) {
            $itemModel->where('items.type', $type);
        }

        $data = [
            'allItems' => $itemModel->findAll(),
            'lostItems' => $itemModel->where('items.type', 'lost')->findAll(),
            'foundItems' => $itemModel->where('items.type', 'found')->findAll(),
            'search' => $search,
            'selectedCategory' => $category,
            'selectedStatus' => $status,
            'selectedType' => $type,
        ];

        // Log dashboard items view activity
        $this->activityLogger->log(
            'Dashboard',
            'View Dashboard Items',
            'User viewed the dashboard items page with filtering options',
            [
                'severity' => 'low',
                'new_values' => [
                    'total_items_displayed' => count($data['allItems']),
                    'lost_items_count' => count($data['lostItems']),
                    'found_items_count' => count($data['foundItems']),
                    'search_query' => $search,
                    'category_filter' => $category,
                    'status_filter' => $status,
                    'type_filter' => $type
                ]
            ]
        );

        return view('dashboard/items', $data);
    }
}
