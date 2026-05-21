<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Feedback Analytics
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Force override any background colors with highest specificity */
    html, html body, body {
        background: #f8fafc !important;
        background-color: #f8fafc !important;
        background-image: none !important;
    }
    
    .main-content, div.main-content {
        background: #f8fafc !important;
        background-color: #f8fafc !important;
        background-image: none !important;
    }
    
    .container-fluid, div.container-fluid {
        background: transparent !important;
        background-color: transparent !important;
        background-image: none !important;
    }
    
    /* Ensure the entire page wrapper has no background */
    .page-wrapper, .content-wrapper {
        background: transparent !important;
        background-color: transparent !important;
        background-image: none !important;
    }
    
    /* Modern glass card styling similar to respond.php */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .analytics-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--cspc-primary);
    }
    
    .analytics-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
        min-height: 160px;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card.card-total {
        border-left: 4px solid #667eea;
    }
    
    .stat-card.card-positive {
        border-left: 4px solid #22c55e;
    }
    
    .stat-card.card-negative {
        border-left: 4px solid #f97316;
    }
    
    .stat-card.card-neutral {
        border-left: 4px solid #ef4444;
    }
    
    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.6;
        color: #6c757d;
    }
    
    .card-total .stat-icon {
        color: #0d6efd;
    }
    
    .card-positive .stat-icon {
        color: #198754;
    }
    
    .card-negative .stat-icon {
        color: #dc3545;
    }
    
    .card-neutral .stat-icon {
        color: #6f42c1;
    }
    
    .stat-count {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 10px 0;
        line-height: 1;
        color: #1f2937;
    }
    
    .stat-label {
        font-size: 1rem;
        margin: 0.5rem 0 0 0;
        color: #6b7280;
        font-weight: 500;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--cspc-primary);
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        margin: 1rem 0;
    }
    
    .rating-bar {
        background: rgba(233, 236, 239, 0.5);
        border-radius: 0.5rem;
        height: 20px;
        margin: 0.5rem 0;
        overflow: hidden;
        border: 1px solid rgba(222, 226, 230, 0.3);
    }
    
    .rating-fill {
        background: rgba(40, 167, 69, 0.8);
        height: 100%;
        border-radius: 0.5rem;
    }
    
    .category-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .category-item:last-child {
        border-bottom: none;
    }
    
    .trend-up {
        color: #28a745;
    }
    
    .trend-down {
        color: #dc3545;
    }
    
    .trend-neutral {
        color: #6c757d;
    }
    
    .filter-card {
        background: rgba(248, 249, 250, 0.95);
        border: 1px solid rgba(222, 226, 230, 0.3);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    /* Modern form styling */
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        transform: translateY(-1px);
        background: rgba(255, 255, 255, 1);
    }
    
    .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    /* Modern button styling */
    .btn-primary {
        background: rgba(13, 110, 253, 0.9);
        border: 1px solid rgba(13, 110, 253, 0.3);
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
        color: white;
    }
    
    .btn-primary:hover {
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.3);
        background: rgba(13, 110, 253, 1);
        transform: translateY(-1px);
    }
    
    .btn-outline-secondary {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(107, 114, 128, 0.3);
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        color: #6b7280;
    }
    
    .btn-outline-secondary:hover {
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.2);
        background: rgba(107, 114, 128, 0.1);
        border-color: rgba(107, 114, 128, 0.5);
        transform: translateY(-1px);
    }
    
    /* Text gradient styling */
    .text-gradient {
        background: linear-gradient(135deg, #000000 0%, #1e3a8a 50%, #581c87 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Breadcrumb styling */
    .breadcrumb {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 0;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .breadcrumb-item a {
        color: #6366f1;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .breadcrumb-item a:hover {
        color: #4f46e5;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Modern Header Section -->
    <div class="glass-card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8">
                    <div class="mb-3 mb-lg-0">
                        <h1 class="display-6 display-md-5 fw-bold text-dark mb-2">
                            <i class="bi bi-graph-up text-primary me-3"></i>
                            Analytics Overview
                        </h1>
                        <p class="lead text-muted mb-0">
                            View comprehensive insights and statistics for your help & support system
                        </p>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge bg-success me-2">
                                <i class="bi bi-circle-fill me-1"></i>Live Updates
                            </span>
                            <small class="text-muted">Last Updated: <?= date('M d, Y \a\t H:i A') ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="d-flex flex-column flex-lg-row gap-2 justify-content-lg-end">
                        <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                        </a>
                        <button class="btn btn-primary" onclick="exportAnalytics()">
                            <i class="bi bi-download me-1"></i> Export Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb Navigation -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= site_url('feedback') ?>">Help & Support</a></li>
            <li class="breadcrumb-item active" aria-current="page">Analytics</li>
        </ol>
    </nav>

    <!-- Date Range Filter -->
    <div class="filter-card">
        <form method="GET" action="<?= site_url('feedback/analytics') ?>">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="<?= request()->getGet('date_from') ?? date('Y-m-01') ?>">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="<?= request()->getGet('date_to') ?? date('Y-m-d') ?>">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        <option value="general" <?= request()->getGet('category') == 'general' ? 'selected' : '' ?>>General</option>
                        <option value="bug_report" <?= request()->getGet('category') == 'bug_report' ? 'selected' : '' ?>>Bug Report</option>
                        <option value="feature_request" <?= request()->getGet('category') == 'feature_request' ? 'selected' : '' ?>>Feature Request</option>
                        <option value="complaint" <?= request()->getGet('category') == 'complaint' ? 'selected' : '' ?>>Complaint</option>
                        <option value="suggestion" <?= request()->getGet('category') == 'suggestion' ? 'selected' : '' ?>>Suggestion</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Apply Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Key Metrics -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100 card-total">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">Total Feedback</div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?= $analytics['total_feedback'] ?? 0 ?></div>
                        <small class="text-muted">+<?= $analytics['growth_rate'] ?? 0 ?>% this month</small>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-chat-dots-fill fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100 card-positive">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">Average Rating</div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?= number_format($analytics['avg_rating'] ?? 0, 1) ?></div>
                        <small class="text-muted">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star<?= $i <= round($analytics['avg_rating'] ?? 0) ? '-fill' : '' ?>" style="color: #fbbf24;"></i>
                            <?php endfor; ?>
                        </small>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-star-fill fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100 card-negative">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">Response Rate</div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?= $analytics['response_rate'] ?? 0 ?>%</div>
                        <small class="text-muted"><?= $analytics['pending_count'] ?? 0 ?> pending</small>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-reply-fill fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card h-100 card-neutral">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">Avg Response Time</div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?= $analytics['avg_response_time'] ?? 0 ?>h</div>
                        <small class="text-muted">Target: 24h</small>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-clock-fill fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Feedback Trends -->
        <div class="col-lg-8">
            <div class="analytics-card">
                <h5><i class="bi bi-graph-up"></i> Feedback Trends</h5>
                <div class="chart-container">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Status Distribution -->
        <div class="col-lg-4">
            <div class="analytics-card">
                <h5><i class="bi bi-pie-chart"></i> Status Distribution</h5>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Rating Distribution -->
        <div class="col-lg-6">
            <div class="analytics-card">
                <h5><i class="bi bi-star"></i> Rating Distribution</h5>
                <?php if (isset($analytics['rating_distribution'])): ?>
                    <?php foreach ($analytics['rating_distribution'] as $rating => $data): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>
                                <?= $rating ?> Star<?= $rating != 1 ? 's' : '' ?>
                                <?php for($i = 1; $i <= $rating; $i++): ?>
                                    <i class="bi bi-star-fill" style="color: #ffc107; font-size: 0.8rem;"></i>
                                <?php endfor; ?>
                            </span>
                            <span class="text-muted"><?= $data['count'] ?> (<?= $data['percentage'] ?>%)</span>
                        </div>
                        <div class="rating-bar">
                            <div class="rating-fill" style="width: <?= $data['percentage'] ?>%"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Category Breakdown -->
        <div class="col-lg-6">
            <div class="analytics-card">
                <h5><i class="bi bi-tags"></i> Category Breakdown</h5>
                <?php if (isset($analytics['category_stats'])): ?>
                    <?php foreach ($analytics['category_stats'] as $category => $data): ?>
                        <div class="category-item">
                            <div>
                                <strong><?= ucfirst(str_replace('_', ' ', $category)) ?></strong><br>
                                <small class="text-muted"><?= $data['count'] ?> feedback items</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary"><?= $data['percentage'] ?>%</span><br>
                                <small class="text-muted">Avg: <?= number_format($data['avg_rating'], 1) ?> ⭐</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="analytics-card">
                <h5><i class="bi bi-clock-history"></i> Recent Activity</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($analytics['recent_feedback'])): ?>
                                <?php foreach ($analytics['recent_feedback'] as $feedback): ?>
                                    <tr>
                                        <td><?= date('M d, Y', strtotime($feedback['created_at'])) ?></td>
                                        <td><?= esc($feedback['user_name']) ?></td>
                                        <td>
                                            <a href="<?= site_url('feedback/view/' . $feedback['id']) ?>">
                                                <?= esc(ucfirst(str_replace('_', ' ', $feedback['type']))) ?>
                                            </a>
                                        </td>
                                        <td><span class="badge bg-secondary"><?= ucfirst(str_replace('_', ' ', $feedback['category'])) ?></span></td>
                                        <td>
                                            <?php if ($feedback['rating']): ?>
                                                <?php for($i = 1; $i <= $feedback['rating']; $i++): ?>
                                                    <i class="bi bi-star-fill" style="color: #ffc107; font-size: 0.8rem;"></i>
                                                <?php endfor; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $feedback['status'] == 'resolved' ? 'success' : ($feedback['status'] == 'responded' ? 'info' : 'warning') ?>">
                                                <?= ucfirst($feedback['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= site_url('feedback/view/' . $feedback['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No recent feedback found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Feedback Trends Chart
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
const trendsChart = new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($analytics['trends']['labels'] ?? []) ?>,
        datasets: [{
            label: 'Feedback Received',
            data: <?= json_encode($analytics['trends']['data'] ?? []) ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($analytics['status_labels'] ?? []) ?>,
        datasets: [{
            data: <?= json_encode($analytics['status_data'] ?? []) ?>,
            backgroundColor: [
                '#ffc107',
                '#17a2b8',
                '#28a745'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

function exportAnalytics() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');
    window.open(`<?= site_url('feedback/analytics') ?>?${params.toString()}`, '_blank');
}
</script>
<?= $this->endSection() ?>