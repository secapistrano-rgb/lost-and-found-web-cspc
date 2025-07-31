<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Reports & Analytics Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .stats-card.success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .stats-card.warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .stats-card.info {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    .stats-card.danger {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .chart-container {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .metric-value {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .metric-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    .trend-indicator {
        font-size: 0.8rem;
        margin-top: 5px;
    }
    .recent-activity {
        max-height: 300px;
        overflow-y: auto;
    }
    .activity-item {
        border-left: 3px solid #007bff;
        padding-left: 15px;
        margin-bottom: 15px;
        padding-bottom: 10px;
    }
    .activity-item.claim {
        border-left-color: #28a745;
    }
    .activity-item.item {
        border-left-color: #ffc107;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Reports & Analytics Dashboard</h1>
        <div class="btn-group">
            <a href="<?= site_url('reports/items') ?>" class="btn btn-outline-primary">Items Report</a>
            <a href="<?= site_url('reports/claims') ?>" class="btn btn-outline-success">Claims Report</a>
            <a href="<?= site_url('reports/analytics') ?>" class="btn btn-outline-info">Advanced Analytics</a>
        </div>
    </div>

    <!-- Key Metrics Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-value"><?= number_format($totalItems) ?></div>
                <div class="metric-label">Total Items</div>
                <div class="trend-indicator">
                    <i class="bi bi-box-seam"></i> All time
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card success">
                <div class="metric-value"><?= number_format($totalClaims) ?></div>
                <div class="metric-label">Total Claims</div>
                <div class="trend-indicator">
                    <i class="bi bi-shield-check"></i> All time
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <div class="metric-value"><?= number_format($pendingClaims) ?></div>
                <div class="metric-label">Pending Claims</div>
                <div class="trend-indicator">
                    <i class="bi bi-clock"></i> Awaiting review
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card info">
                <div class="metric-value"><?= $successRate ?>%</div>
                <div class="metric-label">Success Rate</div>
                <div class="trend-indicator">
                    <i class="bi bi-graph-up"></i> Claim approval rate
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Metrics Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card danger">
                <div class="metric-value"><?= number_format($claimedItems) ?></div>
                <div class="metric-label">Claimed Items</div>
                <div class="trend-indicator">
                    <i class="bi bi-check-circle"></i> Successfully returned
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-value"><?= number_format($verifiedClaims) ?></div>
                <div class="metric-label">Verified Claims</div>
                <div class="trend-indicator">
                    <i class="bi bi-shield-check"></i> Approved claims
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <div class="metric-value"><?= number_format($rejectedClaims) ?></div>
                <div class="metric-label">Rejected Claims</div>
                <div class="trend-indicator">
                    <i class="bi bi-x-circle"></i> Declined claims
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card info">
                <div class="metric-value"><?= $averageClaimTime ?>h</div>
                <div class="metric-label">Avg. Processing Time</div>
                <div class="trend-indicator">
                    <i class="bi bi-stopwatch"></i> Hours to process
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3">Monthly Items Trend</h5>
                <canvas id="monthlyItemsChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3">Monthly Claims Trend</h5>
                <canvas id="monthlyClaimsChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Category and Status Distribution -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3">Items by Category</h5>
                <canvas id="categoryChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3">Claim Status Distribution</h5>
                <canvas id="claimStatusChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3">Recent Items</h5>
                <div class="recent-activity">
                    <?php if (!empty($recentItems)): ?>
                        <?php foreach($recentItems as $item): ?>
                        <div class="activity-item item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong><?= esc($item['name']) ?></strong>
                                    <br>
                                    <small class="text-muted"><?= esc($item['category']) ?> • <?= esc($item['status']) ?></small>
                                </div>
                                <small class="text-muted"><?= date('M d, Y', strtotime($item['created_at'])) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No recent items found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h5 class="mb-3">Recent Claims</h5>
                <div class="recent-activity">
                    <?php if (!empty($recentClaims)): ?>
                        <?php foreach($recentClaims as $claim): ?>
                        <div class="activity-item claim">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong><?= esc($claim['claimant_name']) ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= esc($claim['item_name']) ?> • 
                                        <span class="badge badge-<?= $claim['status'] === 'pending' ? 'warning' : ($claim['status'] === 'verified' ? 'success' : 'danger') ?>">
                                            <?= ucfirst($claim['status']) ?>
                                        </span>
                                    </small>
                                </div>
                                <small class="text-muted"><?= date('M d, Y', strtotime($claim['created_at'])) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No recent claims found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Items Chart
const monthlyItemsCtx = document.getElementById('monthlyItemsChart').getContext('2d');
const monthlyItemsChart = new Chart(monthlyItemsCtx, {
    type: 'line',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . date('M Y', strtotime($item['month'] . '-01')) . '"'; }, $monthlyItemsData)) ?>],
        datasets: [{
            label: 'Items',
            data: [<?= implode(',', array_column($monthlyItemsData, 'count')) ?>],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
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

// Monthly Claims Chart
const monthlyClaimsCtx = document.getElementById('monthlyClaimsChart').getContext('2d');
const monthlyClaimsChart = new Chart(monthlyClaimsCtx, {
    type: 'line',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . date('M Y', strtotime($item['month'] . '-01')) . '"'; }, $monthlyClaimsData)) ?>],
        datasets: [{
            label: 'Claims',
            data: [<?= implode(',', array_column($monthlyClaimsData, 'count')) ?>],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
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

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . $item['category'] . '"'; }, $categoryData)) ?>],
        datasets: [{
            data: [<?= implode(',', array_column($categoryData, 'count')) ?>],
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Claim Status Chart
const claimStatusCtx = document.getElementById('claimStatusChart').getContext('2d');
const claimStatusChart = new Chart(claimStatusCtx, {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . ucfirst($item['status']) . '"'; }, $claimStatusData)) ?>],
        datasets: [{
            label: 'Claims',
            data: [<?= implode(',', array_column($claimStatusData, 'count')) ?>],
            backgroundColor: [
                '#FFC107',
                '#28A745',
                '#DC3545'
            ]
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
</script>
<?= $this->endSection() ?>