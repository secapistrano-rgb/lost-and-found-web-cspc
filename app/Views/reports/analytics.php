<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Advanced Analytics
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .analytics-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 25px;
        transition: transform 0.3s ease;
    }
    .analytics-card:hover {
        transform: translateY(-5px);
    }
    .metric-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        text-align: center;
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
    .efficiency-meter {
        position: relative;
        height: 200px;
    }
    .peak-hours-chart {
        height: 300px;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Advanced Analytics</h1>
        <a href="<?= site_url('reports') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="metric-header">
        <h2>System Performance Analytics</h2>
        <p class="mb-0">Comprehensive insights into your Lost & Found system performance</p>
    </div>

    <!-- Daily Trends -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="analytics-card">
                <h5 class="mb-3">
                    <i class="bi bi-graph-up text-primary"></i> Daily Activity Trends (Last 30 Days)
                </h5>
                <canvas id="dailyTrendsChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Category Performance -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="analytics-card">
                <h5 class="mb-3">
                    <i class="bi bi-pie-chart text-success"></i> Category Performance
                </h5>
                <canvas id="categoryTrendsChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="analytics-card">
                <h5 class="mb-3">
                    <i class="bi bi-speedometer2 text-info"></i> Claim Efficiency Metrics
                </h5>
                <div class="row text-center">
                    <div class="col-4">
                        <h4 class="text-success"><?= number_format($claimEfficiency['verified_count']) ?></h4>
                        <small class="text-muted">Verified</small>
                    </div>
                    <div class="col-4">
                        <h4 class="text-danger"><?= number_format($claimEfficiency['rejected_count']) ?></h4>
                        <small class="text-muted">Rejected</small>
                    </div>
                    <div class="col-4">
                        <h4 class="text-warning"><?= number_format($claimEfficiency['pending_count']) ?></h4>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-primary"><?= round($claimEfficiency['avg_approval_time'] ?? 0, 1) ?>h</h5>
                        <small class="text-muted">Avg. Approval Time</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-secondary"><?= round($claimEfficiency['avg_rejection_time'] ?? 0, 1) ?>h</h5>
                        <small class="text-muted">Avg. Rejection Time</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Peak Hours and Top Categories -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="analytics-card">
                <h5 class="mb-3">
                    <i class="bi bi-clock text-warning"></i> Peak Activity Hours
                </h5>
                <canvas id="peakHoursChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="analytics-card">
                <h5 class="mb-3">
                    <i class="bi bi-trophy text-warning"></i> Top Categories
                </h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Category</th>
                                <th>Items</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalTopItems = array_sum(array_column($topCategories, 'count'));
                            foreach($topCategories as $index => $category): 
                                $percentage = $totalTopItems > 0 ? round(($category['count'] / $totalTopItems) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td>
                                    <?php if($index === 0): ?>
                                        <i class="bi bi-trophy-fill text-warning"></i>
                                    <?php elseif($index === 1): ?>
                                        <i class="bi bi-award-fill text-secondary"></i>
                                    <?php elseif($index === 2): ?>
                                        <i class="bi bi-award-fill text-warning"></i>
                                    <?php else: ?>
                                        <?= $index + 1 ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($category['category']) ?></td>
                                <td><?= number_format($category['count']) ?></td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%">
                                            <?= $percentage ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Comparison -->
    <div class="row">
        <div class="col-12">
            <div class="analytics-card">
                <h5 class="mb-3">
                    <i class="bi bi-bar-chart text-primary"></i> Monthly Comparison (Last 6 Months)
                </h5>
                <canvas id="monthlyComparisonChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Daily Trends Chart
const dailyTrendsCtx = document.getElementById('dailyTrendsChart').getContext('2d');
const dailyTrendsChart = new Chart(dailyTrendsCtx, {
    type: 'line',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . date('M d', strtotime($item['date'])) . '"'; }, $dailyTrends)) ?>],
        datasets: [{
            label: 'Items Found',
            data: [<?= implode(',', array_column($dailyTrends, 'items_count')) ?>],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Category Trends Chart
const categoryTrendsCtx = document.getElementById('categoryTrendsChart').getContext('2d');
const categoryTrendsChart = new Chart(categoryTrendsCtx, {
    type: 'doughnut',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . $item['category'] . '"'; }, $categoryTrends)) ?>],
        datasets: [{
            data: [<?= implode(',', array_column($categoryTrends, 'total')) ?>],
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40',
                '#FF6384',
                '#36A2EB'
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

// Peak Hours Chart
const peakHoursCtx = document.getElementById('peakHoursChart').getContext('2d');
const peakHoursChart = new Chart(peakHoursCtx, {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . $item['hour'] . ':00"'; }, $peakHours)) ?>],
        datasets: [{
            label: 'Activity Count',
            data: [<?= implode(',', array_column($peakHours, 'count')) ?>],
            backgroundColor: 'rgba(255, 193, 7, 0.8)',
            borderColor: 'rgba(255, 193, 7, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Monthly Comparison Chart
const monthlyComparisonCtx = document.getElementById('monthlyComparisonChart').getContext('2d');
const monthlyComparisonChart = new Chart(monthlyComparisonCtx, {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . date('M Y', strtotime($item['month'] . '-01')) . '"'; }, $monthlyComparison)) ?>],
        datasets: [{
            label: 'Items',
            data: [<?= implode(',', array_column($monthlyComparison, 'items_count')) ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }, {
            label: 'Claims',
            data: [<?= implode(',', array_column($monthlyComparison, 'claims_count')) ?>],
            backgroundColor: 'rgba(255, 99, 132, 0.8)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
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