<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
CSPC Lost and Found – Reports & Analytics
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .dashboard-container {
        background: var(--cspc-light, #f8f9fa);
        min-height: 100vh;
        padding: 15px 0;
    }
    
    .dashboard-header {
        background: white;
        border-radius: 1rem;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }
    
    .dashboard-subtitle {
        color: #6c757d;
        font-size: 0.95rem;
        margin: 0;
    }
    
    .date-range-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
    }
    
    .date-tab {
        padding: 8px 16px;
        border: 1px solid #dee2e6;
        background: white;
        color: #6c757d;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .date-tab.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }
    
    .date-tab:hover {
        background: #f8f9fa;
        color: #495057;
        text-decoration: none;
    }
    
    .date-tab.active:hover {
        background: #0056b3;
        color: white;
    }
    
    .export-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .export-btn {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        background: white;
        color: #6c757d;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
    }
    
    .export-btn:hover {
        background: #f8f9fa;
        color: #495057;
        text-decoration: none;
    }
    
    .stats-card {
        border-radius: 1rem;
        padding: 12px;
        margin-bottom: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: none;
        position: relative;
        height: auto;
        min-height: 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow: hidden;
    }
    
    .stats-card:nth-child(1) {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
    }
    
    .stats-card:nth-child(2) {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white !important;
    }
    
    .stats-card:nth-child(3) {
        background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);
        color: white !important;
    }
    
    .stats-card:nth-child(4) {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white !important;
    }
    
    .stats-card:nth-child(5) {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white !important;
    }
    
    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 4px;
        line-height: 1;
    }
    
    .metric-label {
        font-size: 0.75rem;
        margin-bottom: 4px;
        line-height: 1.2;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        opacity: 0.9;
    }
    
    .stats-card .metric-value,
    .stats-card .metric-label {
        color: inherit;
    }
    
    .metric-subtitle {
        font-size: 0.75rem;
        color: #adb5bd;
        margin: 0;
    }
    
    /* Search and Filter Section Styles */
    .search-filter-section .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-radius: 12px;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }
    
    .search-filter-section .form-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    
    .search-filter-section .form-control,
    .search-filter-section .form-select {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        background-color: #fff;
    }
    
    .search-filter-section .form-control:focus,
    .search-filter-section .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background-color: #fff;
    }
    
    .search-filter-section .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-right: none;
        border-radius: 8px 0 0 8px;
        color: #6c757d;
    }
    
    .search-filter-section .input-group .form-control {
        border-left: none;
        border-radius: 0 8px 8px 0;
    }
    
    .search-filter-section .btn {
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        min-width: 40px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .search-filter-section .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .search-filter-section .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    }
    
    .search-filter-section .btn-outline-secondary {
        border-color: #dee2e6;
        color: #6c757d;
    }
    
    .search-filter-section .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #495057;
    }
    
    .search-filter-section .col-md-1 {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }
    
    .search-filter-section .d-flex {
        gap: 0.5rem;
        align-items: center;
        justify-content: flex-start;
    }
    
    @media (max-width: 768px) {
        .search-filter-section .row > div {
            margin-bottom: 1rem;
        }
        
        .search-filter-section .col-md-1 {
            text-align: center;
            justify-content: center;
        }
        
        .search-filter-section .d-flex {
             justify-content: center;
         }
     }
    
    .chart-container {
        background: white;
        border-radius: 1rem;
        padding: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 16px;
        border: none;
        min-height: 200px;
        position: relative;
        overflow: hidden;
    }
    
    .chart-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #a8c8ec 0%, #d4a5d8 100%);
    }
    
    /* Equal height for Claims Status, Claims Report, and User Report */
    .row.mb-2:last-of-type .chart-container {
        height: 280px;
        display: flex;
        flex-direction: column;
    }
    
    .row.mb-2:last-of-type .chart-container .table-responsive {
        flex: 1;
        overflow-y: auto;
    }
    
    .row.mb-2:last-of-type .chart-container canvas {
        flex: 1;
    }
    
    .chart-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    

    
    /* Loading States */
    .chart-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
        color: #666;
    }
    
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 15px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .recent-activity {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .activity-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #007bff;
    }
    
    .activity-item.claim {
        border-left-color: #28a745;
    }
    
    .activity-item.item {
        border-left-color: #ffc107;
    }
    
    .activity-time {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .quick-actions {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .action-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 12px 18px;
        border-radius: 10px;
        margin: 5px;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }
    
    .action-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .analytics-card {
        background: white;
        border-radius: 6px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 12px;
        border: 1px solid #e9ecef;
    }
    
    .filter-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
    }
    
    .efficiency-metrics {
        display: flex;
        justify-content: space-around;
        text-align: center;
        padding: 15px 0;
    }
    
    .efficiency-item {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        margin: 0 5px;
        background: #f8f9fa;
    }
    
    .export-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
    }
    
    .btn-export {
        background: rgba(102, 126, 234, 0.1);
        border: 1px solid rgba(102, 126, 234, 0.3);
        color: #667eea;
        padding: 8px 15px;
        border-radius: 8px;
        margin: 0 2px;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }
    
    .btn-export:hover {
        background: #667eea;
        color: white;
    }
    
    /* Enhanced Responsive Design */
    @media (max-width: 1200px) {
        .col-lg-2 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }
    
    @media (max-width: 992px) {
        .dashboard-header {
            padding: 20px;
        }
        
        .chart-container {
            padding: 20px;
        }
        
        .col-lg-2 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 10px 0;
        }
        
        .dashboard-header {
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .dashboard-header h1 {
            font-size: 1.5rem;
        }
        
        .metric-value {
            font-size: 1.5rem;
        }
        
        .metric-label {
            font-size: 0.8rem;
        }
        
        .stats-card {
            height: 120px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .chart-container {
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .chart-title {
            font-size: 1rem;
        }
        
        .col-lg-2, .col-md-4, .col-sm-6 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 10px;
        }
        
        .row.mb-4.justify-content-center {
            margin-bottom: 20px !important;
        }
        
        .activity-item {
            padding: 12px;
        }
        
        .efficiency-metrics {
            flex-direction: column;
            gap: 10px;
        }
        
        .efficiency-item {
            margin: 0;
        }
    }
    
    @media (max-width: 576px) {
        .dashboard-header h1 {
            font-size: 1.3rem;
        }
        
        .metric-value {
            font-size: 1.3rem;
        }
        
        .stats-card {
            height: 100px;
            padding: 12px;
        }
        
        .metric-icon {
            font-size: 1.2rem;
            top: 10px;
            right: 10px;
        }
        
        .chart-container {
            padding: 12px;
        }
        
        .action-btn {
            padding: 8px 12px;
            font-size: 0.8rem;
            margin: 3px;
        }
    }
    
    /* Chart Responsiveness */
    .chart-responsive {
        position: relative;
        height: 0;
        overflow: hidden;
    }
    
    .chart-responsive canvas {
        position: absolute;
        top: 0;
        left: 0;
    }
</style>

<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="text-display text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent mb-2">
                        CSPC Lost and Found – Reports & Analytics
                    </h1>
                    <p class="dashboard-subtitle">Comprehensive analytics and insights</p>
                </div>
                <div class="export-buttons">
                    <button id="exportBtn" class="export-btn" onclick="exportFilteredReport()">
                        <i class="bi bi-file-earmark-pdf"></i>
                        PDF
                    </button>
                </div>
            </div>

        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending Review</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Denied</option>
                                <option value="resolved">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="categoryFilter" class="form-label">Category</label>
                            <select class="form-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                <option value="electronics">Electronics</option>
                                <option value="clothing">Clothing</option>
                                <option value="accessories">Accessories</option>
                                <option value="documents">Documents</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="dateFrom" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="dateFrom">
                        </div>
                        <div class="col-md-2">
                            <label for="dateTo" class="form-label">Date To</label>
                            <input type="date" class="form-control" id="dateTo">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" id="applyFilters" title="Apply Filters">
                                    <i class="bi bi-funnel"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="clearFilters" title="Clear Filters">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Row -->
        <div class="row mb-2">
            <div class="col-lg col-md-6 col-sm-6">
                <div class="stats-card text-center" style="animation-delay: 0s;">
                    <div class="metric-label">Total Reported Items</div>
                    <div class="metric-value"><?= number_format($totalItems ?? 432) ?></div>
                    <div class="w-1 h-1 bg-white rounded-full animate-pulse" style="width: 4px; height: 4px; background: rgba(255,255,255,0.6); border-radius: 50%; margin: 8px auto; animation: pulse 2s infinite;"></div>
                </div>
            </div>
            <div class="col-lg col-md-6 col-sm-6">
                <div class="stats-card text-center" style="animation-delay: 0.3s;">
                    <div class="metric-label">Items Successfully Returned</div>
                    <div class="metric-value"><?= number_format($claimSuccessRate ?? 76) ?>%</div>
                    <div class="w-1 h-1 bg-white rounded-full animate-pulse" style="width: 4px; height: 4px; background: rgba(255,255,255,0.6); border-radius: 50%; margin: 8px auto; animation: pulse 2s infinite; animation-delay: 0.5s;"></div>
                </div>
            </div>
            <div class="col-lg col-md-6 col-sm-6">
                <div class="stats-card text-center" style="animation-delay: 0.6s;">
                    <div class="metric-label">Average Processing Time</div>
                    <div class="metric-value"><?= number_format($averageClaimTime ?? 2.3, 1) ?> days</div>
                    <div class="w-1 h-1 bg-white rounded-full animate-pulse" style="width: 4px; height: 4px; background: rgba(255,255,255,0.6); border-radius: 50%; margin: 8px auto; animation: pulse 2s infinite; animation-delay: 1s;"></div>
                </div>
            </div>
            <div class="col-lg col-md-6 col-sm-6">
                <div class="stats-card text-center" style="animation-delay: 0.9s;">
                    <div class="metric-label">Total Active Users</div>
                    <div class="metric-value"><?= number_format($activeUsers ?? 287) ?></div>
                    <div class="w-1 h-1 bg-white rounded-full animate-pulse" style="width: 4px; height: 4px; background: rgba(255,255,255,0.6); border-radius: 50%; margin: 8px auto; animation: pulse 2s infinite; animation-delay: 1.5s;"></div>
                </div>
            </div>
            <div class="col-lg col-md-6 col-sm-6">a
                <div class="stats-card text-center" style="animation-delay: 1.2s;">
                    <div class="metric-label">Total Email Sent</div>
                    <div class="metric-value"><?= number_format($emailsSent ?? 92) ?></div>
                    <div class="w-1 h-1 bg-white rounded-full animate-pulse" style="width: 4px; height: 4px; background: rgba(255,255,255,0.6); border-radius: 50%; margin: 8px auto; animation: pulse 2s infinite; animation-delay: 2s;"></div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mb-2">
            <div class="col-lg-6 col-md-12">
                <div class="chart-container" style="animation-delay: 1.5s;">
                    <div class="chart-title">
                        <i class="bi bi-graph-up text-primary"></i>
                        Reported Items Trend
                    </div>
                    <div class="chart-loading" id="dailyTrendsLoading" style="display: none;">
                        <div class="loading-spinner"></div>
                        <span>Loading chart data...</span>
                    </div>
                    <canvas id="dailyTrendsChart" height="25"></canvas>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="chart-container" style="animation-delay: 1.8s;">
                    <div class="chart-title">
                        <i class="bi bi-pie-chart text-primary"></i>
                        Claim Status
                    </div>
                    <div class="chart-loading" id="claimStatusLoading" style="display: none;">
                        <div class="loading-spinner"></div>
                        <span>Loading chart data...</span>
                    </div>
                    <canvas id="claimStatusChart" height="25"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Bottom Row: Claims Status Chart and Reports -->
        <div class="row mb-2">
            <div class="col-lg-4 col-md-12">
                <div class="chart-container" style="animation-delay: 2.1s;">
                    <div class="chart-title">
                        <i class="bi bi-pie-chart text-info"></i>
                        Most Commonly Lost Items by Category
                    </div>
                    <canvas id="categoryChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="chart-container" style="animation-delay: 2.4s;">
                    <div class="chart-title">
                        <i class="bi bi-file-text text-primary"></i>
                        Claims Report
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <thead class="table-light">
                                <tr>
                                    <th style="font-size: 0.75rem; padding: 6px;">Claim ID</th>
                                    <th style="font-size: 0.75rem; padding: 6px;">Claimed By</th>
                                    <th style="font-size: 0.75rem; padding: 6px;">Item</th>
                                    <th style="font-size: 0.75rem; padding: 6px;">Status</th>
                                    <th style="font-size: 0.75rem; padding: 6px;">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentClaims)): ?>
                                    <?php foreach ($recentClaims as $claim): ?>
                                        <tr>
                                            <td style="font-size: 0.7rem; padding: 4px;">CLM<?= str_pad($claim['id'], 3, '0', STR_PAD_LEFT) ?></td>
                                            <td style="font-size: 0.7rem; padding: 4px;"><?= esc($claim['claimant_name'] ?? 'N/A') ?></td>
                                            <td style="font-size: 0.7rem; padding: 4px;"><?= esc(substr($claim['item_name'] ?? 'N/A', 0, 15)) ?><?= strlen($claim['item_name'] ?? '') > 15 ? '...' : '' ?></td>
                                            <td style="padding: 4px;">
                                                <?php 
                                                $statusClass = 'bg-secondary';
                                                switch($claim['status']) {
                                                    case 'pending':
                                                        $statusClass = 'bg-warning';
                                                        break;
                                                    case 'approved':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'rejected':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                    case 'resolved':
                                                        $statusClass = 'bg-info';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?>" style="font-size: 0.6rem;"><?= ucfirst($claim['status']) ?></span>
                                            </td>
                                            <td style="font-size: 0.7rem; padding: 4px;"><?= date('M d', strtotime($claim['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center" style="font-size: 0.7rem; padding: 8px;">No recent claims found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="chart-container" style="animation-delay: 2.7s;">
                    <div class="chart-title">
                        <i class="bi bi-people text-success"></i>
                        User Report
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            <thead class="table-light">
                                <tr>
                                    <th style="font-size: 0.75rem; padding: 6px;">User ID</th>
                                    <th style="font-size: 0.75rem; padding: 6px;">Name</th>
                                    <th style="font-size: 0.75rem; padding: 6px;">Reports</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topUsers)): ?>
                                    <?php foreach ($topUsers as $user): ?>
                                        <tr>
                                            <td style="font-size: 0.7rem; padding: 4px;">USR<?= str_pad($user['id'], 3, '0', STR_PAD_LEFT) ?></td>
                                            <td style="font-size: 0.7rem; padding: 4px;"><?= esc($user['name'] ?? 'N/A') ?></td>
                                            <td style="font-size: 0.7rem; padding: 4px;"><?= $user['report_count'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center" style="font-size: 0.7rem; padding: 8px;">No user data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Enhanced Chart.js Configuration with consistent styling
Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
Chart.defaults.color = '#6c757d';
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.plugins.legend.labels.padding = 15;

// Consistent color palette
const colorPalette = {
    primary: '#667eea',
    secondary: '#764ba2',
    success: '#22c55e',
    warning: '#facc15',
    danger: '#ef4444',
    info: '#3b82f6',
    light: '#f8f9fa',
    dark: '#343a40'
};

// Common chart options
const commonOptions = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        tooltip: {
            backgroundColor: 'rgba(0,0,0,0.8)',
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: 'rgba(255,255,255,0.1)',
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: true,
            animation: false
        }
    },
    animation: {
        duration: 800,
        easing: 'easeInOutQuart'
    }
};



// Daily Trends Chart
const dailyTrendsCtx = document.getElementById('dailyTrendsChart').getContext('2d');
const dailyLabels = [<?= !empty($dailyTrends) ? implode(',', array_map(function($item) { return '"' . date('M d', strtotime($item['date'])) . '"'; }, $dailyTrends)) : '"No Data"' ?>];
const itemsFoundData = [<?= !empty($dailyTrends) ? implode(',', array_map(function($item) { return $item['items_found']; }, $dailyTrends)) : '0' ?>];
const itemsLostData = [<?= !empty($dailyTrends) ? implode(',', array_map(function($item) { return $item['items_lost']; }, $dailyTrends)) : '0' ?>];

const dailyTrendsChart = new Chart(dailyTrendsCtx, {
    type: 'line',
    data: {
        labels: dailyLabels,
        datasets: [
            {
                label: 'Items Found',
                data: itemsFoundData,
                borderColor: colorPalette.primary,
                backgroundColor: colorPalette.primary + '20',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: colorPalette.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            },
            {
                label: 'Items Lost',
                data: itemsLostData,
                borderColor: colorPalette.danger,
                backgroundColor: colorPalette.danger + '20',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: colorPalette.danger,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }
        ]
    },
    options: {
         ...commonOptions,
         aspectRatio: window.innerWidth < 768 ? 1.5 : 2,
        plugins: { 
            ...commonOptions.plugins,
            legend: { 
                display: true,
                position: 'top'
            } 
        },
        scales: {
            x: { 
                grid: { display: false },
                ticks: {
                    maxRotation: window.innerWidth < 768 ? 45 : 0
                }
            },
            y: { 
                beginAtZero: true, 
                grid: { color: 'rgba(0, 0, 0, 0.1)' } 
            }
        }
    }
});

// Most Commonly Lost Items by Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryData = <?= json_encode($itemsPerCategory ?? []) ?>;

// Prepare data for the chart
const categoryLabels = categoryData.map(item => item.category ? item.category.charAt(0).toUpperCase() + item.category.slice(1) : 'Uncategorized');
const categoryCounts = categoryData.map(item => parseInt(item.count));
const categoryColors = [
    '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
    '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9'
];

const categoryChart = new Chart(categoryCtx, {
    type: 'bar',
    data: {
        labels: categoryLabels,
        datasets: [{
            label: 'Lost Items',
            data: categoryCounts,
            backgroundColor: categoryColors.slice(0, categoryLabels.length),
            borderColor: categoryColors.slice(0, categoryLabels.length).map(color => color + '80'),
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        ...commonOptions,
        aspectRatio: window.innerWidth < 768 ? 1.8 : 1.5,
        indexAxis: 'y', // Horizontal bar chart
        plugins: { 
            ...commonOptions.plugins,
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.9)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: 'rgba(255, 255, 255, 0.2)',
                borderWidth: 1,
                cornerRadius: 8,
                padding: 12,
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.x + ' items';
                    },
                    afterLabel: function(context) {
                        const total = categoryCounts.reduce((sum, count) => sum + count, 0);
                        const percentage = ((context.parsed.x / total) * 100).toFixed(1);
                        return percentage + '% of total lost items';
                    }
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: { 
                    display: true,
                    color: 'rgba(0, 0, 0, 0.1)'
                },
                ticks: {
                    maxRotation: window.innerWidth < 768 ? 45 : 0,
                    font: {
                        size: 11
                    }
                },
                title: {
                    display: true,
                    text: 'Number of Lost Items',
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                }
            },
            y: { 
                grid: { display: false },
                ticks: {
                    font: {
                        size: 10,
                        weight: '500'
                    },
                    callback: function(value, index) {
                        const label = this.getLabelForValue(value);
                        return label.length > 12 ? label.substring(0, 12) + '...' : label;
                    }
                }
            }
        },
        animation: {
            duration: 2000,
            easing: 'easeInOutCubic',
            delay: (context) => {
                return context.dataIndex * 100;
            }
        },
        hover: {
            animationDuration: 300
        }
    }
});

// Claim Status Chart
const claimStatusCtx = document.getElementById('claimStatusChart').getContext('2d');
const claimStatusChart = new Chart(claimStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending Review', 'Approved', 'Denied', 'Completed'],
        datasets: [{
            data: [
                <?= isset($claimStatus['pending']) ? $claimStatus['pending'] : 0 ?>,
                <?= isset($claimStatus['approved']) ? $claimStatus['approved'] : 0 ?>,
                <?= isset($claimStatus['rejected']) ? $claimStatus['rejected'] : 0 ?>,
                <?= isset($claimStatus['resolved']) ? $claimStatus['resolved'] : 0 ?>
            ],
            backgroundColor: [colorPalette.warning, colorPalette.success, colorPalette.danger, colorPalette.info],
            borderWidth: 2,
            borderColor: '#fff',
            hoverBorderWidth: 3
        }]
    },
    options: {
        ...commonOptions,
        aspectRatio: window.innerWidth < 768 ? 1.5 : 2,
        plugins: { 
            ...commonOptions.plugins,
            legend: { 
                position: window.innerWidth < 768 ? 'bottom' : 'bottom',
                labels: {
                    padding: 10,
                    usePointStyle: true,
                    font: {
                        size: 11
                    }
                }
            } 
        }
    }
});

// Claims Status Bar Chart - Removed as it was redundant with claimStatusChart

// Responsive chart updates
window.addEventListener('resize', function() {
    const isMobile = window.innerWidth < 768;
    
    // Update aspect ratios
    dailyTrendsChart.options.aspectRatio = isMobile ? 1.5 : 2;
    claimStatusChart.options.aspectRatio = isMobile ? 1.5 : 2;
    categoryChart.options.aspectRatio = isMobile ? 1.8 : 1.5;
    
    // Update legend positions
    claimStatusChart.options.plugins.legend.position = 'bottom';
    
    // Update tick rotations
    dailyTrendsChart.options.scales.x.ticks.maxRotation = isMobile ? 45 : 0;
    categoryChart.options.scales.x.ticks.maxRotation = isMobile ? 45 : 0;
    
    // Update all charts
    dailyTrendsChart.update('none');
    claimStatusChart.update('none');
    categoryChart.update('none');
});

// Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    
    // Set default date range (last 30 days)
    const today = new Date();
    const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
    dateTo.value = today.toISOString().split('T')[0];
    dateFrom.value = thirtyDaysAgo.toISOString().split('T')[0];
    
    // Filter function
    function applyFilters() {
        const filters = {
            status: statusFilter.value,
            category: categoryFilter.value,
            dateFrom: dateFrom.value,
            dateTo: dateTo.value
        };
        
        // Show loading state
        applyFiltersBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Loading...';
        applyFiltersBtn.disabled = true;
        
        // Build query string
        const queryParams = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (filters[key]) {
                queryParams.append(key, filters[key]);
            }
        });
        
        // Make AJAX call to search endpoint
        fetch(`<?= site_url('reports/search') ?>?${queryParams.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update daily trends chart
                updateDailyTrendsChart(data.data.dailyTrends || []);
                
                // Update category chart
                updateCategoryChart(data.data.itemsPerCategory || []);
                
                // Update claims status chart
                updateClaimStatusChart(data.data.claimStatus || {});
                
                // Update recent claims table
                updateRecentClaimsTable(data.data.recentClaims || []);
                
                showFilterMessage('Filters applied successfully!', 'success');
            } else {
                showFilterMessage('Error applying filters. Please try again.', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFilterMessage('Error applying filters. Please try again.', 'danger');
        })
        .finally(() => {
            // Reset button
            applyFiltersBtn.innerHTML = '<i class="bi bi-funnel"></i>';
            applyFiltersBtn.disabled = false;
        });
    }
    
    // Function to update daily trends chart with new data
    function updateDailyTrendsChart(newData) {
        if (!newData || newData.length === 0) {
            // No data scenario
            dailyTrendsChart.data.labels = ['No Data'];
            dailyTrendsChart.data.datasets[0].data = [0];
            dailyTrendsChart.data.datasets[1].data = [0];
        } else {
            const dailyLabels = newData.map(item => new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            const itemsFoundData = newData.map(item => parseInt(item.items_found) || 0);
            const itemsLostData = newData.map(item => parseInt(item.items_lost) || 0);
            
            dailyTrendsChart.data.labels = dailyLabels;
            dailyTrendsChart.data.datasets[0].data = itemsFoundData;
            dailyTrendsChart.data.datasets[1].data = itemsLostData;
        }
        dailyTrendsChart.update('active');
    }

    // Function to update category chart with new data
    function updateCategoryChart(newData) {
        if (!newData || newData.length === 0) {
            // No data scenario
            categoryChart.data.labels = ['No Data'];
            categoryChart.data.datasets[0].data = [0];
            categoryChart.data.datasets[0].backgroundColor = ['#e9ecef'];
        } else {
            const categoryLabels = newData.map(item => item.category ? item.category.charAt(0).toUpperCase() + item.category.slice(1) : 'Uncategorized');
            const categoryCounts = newData.map(item => parseInt(item.count));
            
            categoryChart.data.labels = categoryLabels;
            categoryChart.data.datasets[0].data = categoryCounts;
            categoryChart.data.datasets[0].backgroundColor = categoryColors.slice(0, categoryLabels.length);
        }
        categoryChart.update('active');
    }
    
    // Function to update claim status chart
    function updateClaimStatusChart(newData) {
        if (!newData || Object.keys(newData).length === 0) {
            // No data scenario
            claimStatusChart.data.datasets[0].data = [0, 0, 0, 0];
        } else {
            claimStatusChart.data.datasets[0].data = [
                newData.pending || 0,
                newData.approved || 0,
                newData.rejected || 0,
                newData.resolved || 0
            ];
        }
        claimStatusChart.update('active');
    }
    
    // Function to update recent claims table
    function updateRecentClaimsTable(newData) {
        const tableBody = document.querySelector('#recentClaimsTable tbody');
        if (tableBody) {
            tableBody.innerHTML = '';
            
            if (newData.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No claims found matching the filters</td></tr>';
            } else {
                newData.forEach(claim => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-truncate" style="max-width: 150px;">${claim.item_name || 'N/A'}</td>
                        <td class="text-truncate" style="max-width: 120px;">${claim.claimant_name || 'N/A'}</td>
                        <td><span class="badge bg-${getStatusColor(claim.status)}">${claim.status.charAt(0).toUpperCase() + claim.status.slice(1)}</span></td>
                        <td class="text-xs text-gray-500">${new Date(claim.created_at).toLocaleDateString()}</td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        }
    }
    
    // Helper function to get status color
    function getStatusColor(status) {
        const colors = {
            'pending': 'warning',
            'approved': 'success',
            'rejected': 'danger',
            'resolved': 'info'
        };
        return colors[status] || 'secondary';
    }
    
    // Clear filters function
    function clearFilters() {
        statusFilter.value = '';
        categoryFilter.value = '';
        dateFrom.value = thirtyDaysAgo.toISOString().split('T')[0];
        dateTo.value = today.toISOString().split('T')[0];
        
        showFilterMessage('Filters cleared!', 'info');
        
        // Apply filters to reset to default data
        applyFilters();
    }
    
    // Show filter message
    function showFilterMessage(message, type) {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 3000);
    }
    
    // Export filtered report function
    function exportFilteredReport() {
        const filters = {
            status: statusFilter.value,
            category: categoryFilter.value,
            date_from: dateFrom.value,
            date_to: dateTo.value
        };
        
        // Create a form to submit the export request with proper session handling
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '<?= site_url('reports/export') ?>';
        form.target = '_blank'; // Open in new tab
        
        // Add filter parameters as hidden inputs
        Object.keys(filters).forEach(key => {
            if (filters[key]) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = filters[key];
                form.appendChild(input);
            }
        });
        
        // Add CSRF token for security
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        // Submit the form
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    // Event listeners
    applyFiltersBtn.addEventListener('click', applyFilters);
    clearFiltersBtn.addEventListener('click', clearFilters);
    
    // Export button event listener
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            e.preventDefault();
            exportFilteredReport();
        });
    }
    
    // Auto-apply filters when dropdowns change
    statusFilter.addEventListener('change', applyFilters);
    categoryFilter.addEventListener('change', applyFilters);
    
    // Date validation
    dateFrom.addEventListener('change', function() {
        if (dateTo.value && dateFrom.value > dateTo.value) {
            showFilterMessage('Start date cannot be after end date!', 'warning');
            dateFrom.value = dateTo.value;
        }
    });
    
    dateTo.addEventListener('change', function() {
        if (dateFrom.value && dateTo.value < dateFrom.value) {
            showFilterMessage('End date cannot be before start date!', 'warning');
            dateTo.value = dateFrom.value;
        }
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Enter to apply filters when focused on any filter element
        if (e.key === 'Enter' && (e.target === statusFilter || e.target === categoryFilter)) {
            applyFilters();
        }
        
        // Escape to clear filters
        if (e.key === 'Escape') {
            clearFilters();
        }
    });
});
</script>
<?= $this->endSection() ?>
