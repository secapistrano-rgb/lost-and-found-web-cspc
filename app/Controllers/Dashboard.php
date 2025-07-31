<?php

namespace App\Controllers;

use App\Models\ItemModel;

class Dashboard extends BaseController
{
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
                                          SUM(CASE WHEN `condition` = 'lost' THEN 1 ELSE 0 END) as lost_count,
                                          SUM(CASE WHEN `condition` = 'found' THEN 1 ELSE 0 END) as found_count")
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
            'lostCount'    => $itemModel->where('condition', 'lost')->countAllResults(),
            'foundCount'   => $itemModel->where('condition', 'found')->countAllResults(),
            'recentActivity' => $itemModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'chartData' => $chartData
        ];
        
        return view('dashboard/home', $data);
    }

    public function items()
    {
        $itemModel = new ItemModel();

        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $status = $this->request->getGet('status');
        $condition = $this->request->getGet('condition');

        $itemModel->select('items.*, claims.status as claim_status')
                  ->join('claims', 'claims.item_id = items.id', 'left');

        if ($search) {
            $itemModel->groupStart()
                      ->like('items.name', $search)
                      ->orLike('items.description', $search)
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

        if ($condition) {
            $itemModel->where('items.condition', $condition);
        }

        $data = [
            'allItems' => $itemModel->findAll(),
            'lostItems' => $itemModel->where('items.status', 'lost')->findAll(),
            'foundItems' => $itemModel->where('items.status', 'found')->findAll(),
            'categories' => $itemModel->distinct()->findColumn('category') ?? [],
            'search' => $search,
            'selectedCategory' => $category,
            'selectedStatus' => $status,
            'selectedCondition' => $condition,
        ];

        return view('dashboard/items', $data);
    }
}
