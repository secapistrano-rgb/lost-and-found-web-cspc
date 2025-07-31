<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Dashboard Overview</h1>
    </div>

    <div class="row g-4">
        <div class="col-lg-4 col-md-6">
            <div class="stat-card card-total">
                <div class="stat-icon"><i class="bi bi-box"></i></div>
                <h3 class="stat-count"><?= $totalItems ?? 0 ?></h3>
                <p class="stat-label">Total Items Managed</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="stat-card card-claimed">
                <div class="stat-icon"><i class="bi bi-check2-square"></i></div>
                <h3 class="stat-count"><?= $claimedCount ?? 0 ?></h3>
                <p class="stat-label">Successfully Claimed</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="stat-card card-pending">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <h3 class="stat-count"><?= $pendingCount ?? 0 ?></h3>
                <p class="stat-label">Pending Claims</p>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-bar-chart-line-fill me-2"></i>Weekly Activity</h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="lostFoundChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php if (!empty($recentActivity)): ?>
                            <?php foreach ($recentActivity as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <strong><?= esc($item['name']) ?></strong> was added.
                                        <small class="d-block text-muted">
                                            <?= date('M d, Y g:i A', strtotime($item['created_at'])) ?>
                                        </small>
                                    </div>
                                    <a href="<?= site_url('items/view/' . $item['id']) ?>" class="btn btn-sm btn-outline-secondary">View</a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item px-0">No recent activity.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartData = <?= json_encode($chartData) ?>;

        if (chartData.lostData.some(d => d > 0) || chartData.foundData.some(d => d > 0)) {
            const ctx = document.getElementById('lostFoundChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Lost Items',
                            data: chartData.lostData,
                            backgroundColor: '#4D69F9',
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        },
                        {
                            label: 'Found Items',
                            data: chartData.foundData,
                            backgroundColor: '#20C997',
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 12,
                                padding: 20
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                            },
                            ticks: {
                                padding: 10,
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                            }
                        }
                    }
                }
            });
        } else {
            const chartContainer = document.getElementById('lostFoundChart').parentElement;
            chartContainer.innerHTML = '<div class="text-center text-muted mt-5"><p>No lost or found data for the last 7 days.</p></div>';
        }
    });
</script>
<?= $this->endSection() ?>
