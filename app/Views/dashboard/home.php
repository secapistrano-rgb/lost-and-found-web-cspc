<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .glass-card {
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .text-display {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-weight: 700;
        letter-spacing: -0.025em;
    }
    
    .text-subheading {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-weight: 500;
    }
    
    .text-caption {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-weight: 400;
    }
</style>
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="glass-card backdrop-blur-md bg-white/90 border border-white/20 rounded-2xl shadow-xl mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-6 space-y-4 lg:space-y-0">
                <div class="space-y-3">
                    <h1 class="text-display text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 bg-clip-text text-transparent">Dashboard</h1>
                    <p class="text-subheading text-base sm:text-lg text-gray-600 font-medium max-w-2xl">Monitor your lost and found items</p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-caption text-xs">Live Updates</span>
                        </div>
                        <div class="text-caption text-xs">Last Updated: <?= date('M d, Y \\a\\t g:i A') ?></div>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="<?= site_url('reports') ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="bi bi-file-earmark-text mr-2"></i>
                        Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 g-md-4 mb-4 responsive-stats-grid">
            <div class="col-6 col-lg-3">
                <div class="card responsive-stat-card bg-gradient-to-br from-blue-50/80 to-blue-100/80 backdrop-blur-xl border-0 shadow-lg h-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 0.5s;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
                    <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background: linear-gradient(to right, #60a5fa, #93c5fd, transparent);"></div>
                    <div class="card-body p-3 p-md-4 position-relative" style="z-index: 10;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <p class="small fw-medium text-primary mb-1 responsive-stat-label">Total Items</p>
                                <p class="h4 fw-bold text-primary mb-1 responsive-stat-number"><?= $totalItems ?? 0 ?></p>
                            </div>
                            <div class="bg-white bg-opacity-40 backdrop-blur-sm rounded-3 p-2 p-md-3 shadow-inner responsive-stat-icon">
                                <i class="bi bi-box text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card responsive-stat-card bg-gradient-to-br from-green-50/80 to-green-100/80 backdrop-blur-xl border-0 shadow-lg h-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 1s;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
                    <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background: linear-gradient(to right, #4ade80, #86efac, transparent);"></div>
                    <div class="card-body p-3 p-md-4 position-relative" style="z-index: 10;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <p class="small fw-medium text-success mb-1 responsive-stat-label">Claimed</p>
                                <p class="h4 fw-bold text-success mb-1 responsive-stat-number"><?= $claimedCount ?? 0 ?></p>
                                <p class="text-xs text-success-600 mb-0 d-none d-sm-block"><?= $totalItems > 0 ? round(($claimedCount / $totalItems) * 100, 1) : 0 ?>% success rate</p>
                            </div>
                            <div class="bg-white bg-opacity-40 backdrop-blur-sm rounded-3 p-2 p-md-3 shadow-inner responsive-stat-icon">
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card responsive-stat-card bg-gradient-to-br from-amber-50/80 to-amber-100/80 backdrop-blur-xl border-0 shadow-lg h-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 1.5s;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
                    <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background: linear-gradient(to right, #f59e0b, #fbbf24, transparent);"></div>
                    <div class="card-body p-3 p-md-4 position-relative" style="z-index: 10;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <p class="small fw-medium text-warning mb-1 responsive-stat-label">Pending Review</p>
                                <p class="h4 fw-bold text-warning mb-1 responsive-stat-number"><?= $pendingCount ?? 0 ?></p>
                                <p class="text-xs text-warning-600 mb-0 d-none d-sm-block">Awaiting claim</p>
                            </div>
                            <div class="bg-white bg-opacity-40 backdrop-blur-sm rounded-3 p-2 p-md-3 shadow-inner responsive-stat-icon">
                                <i class="bi bi-clock text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card responsive-stat-card bg-gradient-to-br from-purple-50/80 to-purple-100/80 backdrop-blur-xl border-0 shadow-lg h-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden animate-float" style="animation-delay: 2s;">
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
                    <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background: linear-gradient(to right, #a855f7, #c084fc, transparent);"></div>
                    <div class="card-body p-3 p-md-4 position-relative" style="z-index: 10;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <p class="small fw-medium text-purple mb-1 responsive-stat-label">Returned to Owner</p>
                                <p class="h4 fw-bold text-purple mb-1 responsive-stat-number"><?= $returnedCount ?? 0 ?></p>
                                <p class="text-xs text-purple-600 mb-0 d-none d-sm-block">Successfully returned</p>
                            </div>
                            <div class="bg-white bg-opacity-40 backdrop-blur-sm rounded-3 p-2 p-md-3 shadow-inner responsive-stat-icon">
                                <i class="bi bi-arrow-return-left text-purple fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Cards -->
         <div class="row g-3 g-md-4 responsive-activity-grid">
             <div class="col-12 col-xl-6">
                <div class="card h-100 responsive-activity-card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-2">
                                    <i class="bi bi-bar-chart-line text-primary fs-5"></i>
                                </div>
                                <div>
                                    <h3 class="h5 fw-semibold text-dark mb-1 responsive-card-title">Weekly Activity</h3>
                                    <p class="small text-muted mb-0 d-none d-sm-block">Items trend over 7 days</p>
                                </div>
                            </div>
                            <button class="btn btn-outline-secondary btn-sm responsive-refresh-btn" onclick="refreshChart()">
                                <i class="bi bi-arrow-clockwise"></i>
                                <span class="d-none d-sm-inline ms-1">Refresh</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="bg-light rounded-3 p-3 responsive-chart-container" style="height: 200px; position: relative;">
                            <canvas id="lostFoundChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="card h-100 responsive-activity-card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success bg-opacity-10 rounded-3 p-2">
                                    <i class="bi bi-clock-history text-success fs-5"></i>
                                </div>
                                <div>
                                    <h3 class="h5 fw-semibold text-dark mb-1 responsive-card-title">Recent Activity</h3>
                                    <p class="small text-muted mb-0 d-none d-sm-block">Latest additions</p>
                                </div>
                            </div>
                            <a href="<?= site_url('items') ?>" class="btn btn-primary btn-sm responsive-refresh-btn">
                                <span class="text-sm">View All</span>
                                <i class="bi bi-arrow-right ms-1 text-sm"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="responsive-activity-list" style="max-height: 200px; overflow-y: auto;">
                            <?php if (!empty($recentActivity)): ?>
                                <?php foreach ($recentActivity as $index => $item): ?>
                                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom mb-2 responsive-activity-item">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-3 d-flex align-items-center justify-content-center <?= $item['type'] === 'lost' ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' ?>" style="width: 40px; height: 40px;">
                                                <i class="bi bi-<?= $item['type'] === 'lost' ? 'search' : 'check-circle' ?>"></i>
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <h4 class="fw-medium text-dark mb-0 text-truncate responsive-item-title"><?= esc($item['title']) ?></h4>
                                                    <span class="badge <?= $item['type'] === 'lost' ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' ?> responsive-type-badge">
                                                        <span class="d-none d-sm-inline"><?= ucfirst($item['type'] ?? 'Item') ?></span>
                                                        <span class="d-inline d-sm-none"><?= $item['type'] === 'lost' ? 'L' : 'F' ?></span>
                                                    </span>
                                                </div>
                                                <p class="small text-muted mb-0 responsive-item-meta">
                                                    <span class="d-inline d-sm-none"><?= date('M d', strtotime($item['created_at'])) ?></span>
                                                    <span class="d-none d-sm-inline"><?= date('M d, Y g:i A', strtotime($item['created_at'])) ?></span>
                                                    <span class="mx-1">•</span>
                                                    <span>ID: #<?= str_pad($item['id'], 4, '0', STR_PAD_LEFT) ?></span>
                                                </p>
                                            </div>
                                        </div>
                                        <a href="<?= site_url('items/view/' . $item['id']) ?>" class="btn btn-outline-secondary btn-sm responsive-view-btn">
                                            <i class="bi bi-eye"></i>
                                            <span class="d-none d-md-inline ms-1">View</span>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4 py-md-5 responsive-empty-state">
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                                        <i class="bi bi-inbox text-muted display-6"></i>
                                    </div>
                                    <h6 class="fw-medium text-dark mb-2">No Recent Activity</h6>
                                    <p class="text-muted mb-0">New items and updates will appear here</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-3 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                                    <i class="bi bi-activity text-sm"></i>
                                </div>
                                <div class="space-y-0.5">
                                    <p class="text-sm font-medium text-gray-700 mb-0">Activity Summary</p>
                                    <div class="flex items-center space-x-1">
                                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                        <p class="text-xs text-gray-600 mb-0">Showing last <?= count($recentActivity ?? []) ?> activities</p>
                                    </div>
                                </div>
                            </div>
                            <button onclick="refreshActivity()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors w-full sm:w-auto justify-center">
                                <i class="bi bi-arrow-clockwise mr-2 text-sm"></i>
                                <span class="text-xs sm:text-sm">Refresh Activity</span>
                            </button>
                        </div>
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
    let chartInstance = null;
    let isRefreshing = false;
    
    document.addEventListener("DOMContentLoaded", function() {
        initializeChart();
        initializeMicroInteractions();
        updateActivityDisplay();
    });
    
    function initializeMicroInteractions() {
        // Simple hover effects for cards
        const statCards = document.querySelectorAll('.bg-white');
        statCards.forEach((card) => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }
    
    function updateActivityDisplay() {
        const activityCountElement = document.getElementById('activity-count');
        if (activityCountElement) {
            const count = document.querySelectorAll('.border-b').length;
            activityCountElement.textContent = `Showing last ${count} activities`;
        }
    }
    
    function initializeChart() {
        const chartData = <?= json_encode($chartData) ?>;

        if (chartData.lostData.some(d => d > 0) || chartData.foundData.some(d => d > 0)) {
            const ctx = document.getElementById('lostFoundChart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (chartInstance) {
                chartInstance.destroy();
            }
            
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Lost Items',
                            data: chartData.lostData,
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            pointBackgroundColor: 'rgb(239, 68, 68)',
                            pointBorderColor: 'rgba(255, 255, 255, 0.8)',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: 'rgb(239, 68, 68)',
                            pointHoverBorderColor: 'rgba(255, 255, 255, 1)',
                            pointHoverBorderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3
                        },
                        {
                            label: 'Found Items',
                            data: chartData.foundData,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            pointBackgroundColor: 'rgb(34, 197, 94)',
                            pointBorderColor: 'rgba(255, 255, 255, 0.8)',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: 'rgb(34, 197, 94)',
                            pointHoverBorderColor: 'rgba(255, 255, 255, 1)',
                            pointHoverBorderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 12,
                                boxHeight: 12,
                                padding: 20,
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 14,
                                    weight: '500'
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.9)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgba(255, 255, 255, 0.2)',
                            borderWidth: 1,
                            cornerRadius: 12,
                            padding: 16,
                            titleFont: {
                                family: 'Inter, sans-serif',
                                size: 14,
                                weight: '600'
                            },
                            bodyFont: {
                                family: 'Inter, sans-serif',
                                size: 13,
                                weight: '400'
                            },
                            displayColors: true,
                            boxPadding: 8,
                            usePointStyle: true,
                            callbacks: {
                                title: function(context) {
                                    return 'Week of ' + context[0].label;
                                },
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' items';
                                },
                                afterLabel: function(context) {
                                    const total = context.chart.data.datasets.reduce((sum, dataset) => {
                                        return sum + dataset.data[context.dataIndex];
                                    }, 0);
                                    return 'Total: ' + total + ' items';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)',
                                lineWidth: 1
                            },
                            ticks: {
                                padding: 15,
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 12,
                                    weight: '400'
                                },
                                color: '#6c757d',
                                callback: function(value) {
                                    return value + ' items';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Number of Items',
                                color: '#495057',
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 13,
                                    weight: '500'
                                },
                                padding: 16
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                padding: 10,
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#495057',
                                maxRotation: 0
                            },
                            title: {
                                display: true,
                                text: 'Time Period',
                                color: '#495057',
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 13,
                                    weight: '500'
                                },
                                padding: 16
                            }
                        }
                    },
                    animation: {
                        duration: 2500,
                        easing: 'easeInOutCubic',
                        delay: (context) => {
                            let delay = 0;
                            if (context.type === 'data' && context.mode === 'default') {
                                delay = context.dataIndex * 100 + context.datasetIndex * 200;
                            }
                            return delay;
                        }
                    },
                    hover: {
                        animationDuration: 300,
                        intersect: false
                    },
                    elements: {
                        point: {
                            hoverRadius: 8,
                            hitRadius: 10
                        },
                        line: {
                            borderJoinStyle: 'round',
                            borderCapStyle: 'round'
                        }
                    }
                }
            });
        } else {
            const chartContainer = document.getElementById('lostFoundChart').parentElement;
            chartContainer.innerHTML = `
                <div class="text-center text-muted d-flex flex-column justify-content-center align-items-center h-100">
                    <div class="feature-icon bg-light text-secondary rounded-circle mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-bar-chart display-6"></i>
                    </div>
                    <h6 class="mb-2">No Data Available</h6>
                    <p class="mb-0 small">No lost or found data for the last 7 days.</p>
                </div>
            `;
        }
    }
    
    function refreshChart() {
        if (isRefreshing) return;
        isRefreshing = true;
        
        const chartContainer = document.getElementById('lostFoundChart').parentElement;
        const originalContent = chartContainer.innerHTML;
        
        // Add sophisticated loading animation
        chartContainer.innerHTML = `
            <div class="glass-card text-center d-flex flex-column justify-content-center align-items-center h-100 animate-pulse">
                <div class="position-relative mb-4">
                    <div class="spinner-border text-primary-400 animate-spin" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <i class="bi bi-bar-chart-fill text-primary-600 fs-4"></i>
                    </div>
                </div>
                <h6 class="text-heading font-medium mb-2">Refreshing Analytics</h6>
                <p class="text-body-secondary mb-0 animate-pulse">Fetching latest data...</p>
                <div class="progress mt-3" style="width: 200px; height: 4px;">
                    <div class="progress-bar bg-gradient-to-r from-primary-400 to-primary-600 animate-shimmer" 
                         role="progressbar" style="width: 100%"></div>
                </div>
            </div>
        `;
        
        // Add fade out animation
        chartContainer.style.opacity = '0.7';
        chartContainer.style.transform = 'scale(0.98)';
        chartContainer.style.transition = 'all 0.3s ease';
        
        // Simulate refresh delay with progress
        setTimeout(() => {
            chartContainer.style.opacity = '1';
            chartContainer.style.transform = 'scale(1)';
            chartContainer.innerHTML = '<canvas id="lostFoundChart"></canvas>';
            initializeChart();
            isRefreshing = false;
            
            // Show success notification
            showNotification('Chart refreshed successfully!', 'success');
        }, 2000);
    }
    
    function refreshActivity() {
        const refreshBtn = event.target.closest('button');
        const originalContent = refreshBtn.innerHTML;
        
        // Enhanced loading state with ripple effect
        refreshBtn.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="spinner-border spinner-border-sm me-2 animate-spin" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="animate-pulse">Refreshing...</span>
            </div>
        `;
        refreshBtn.disabled = true;
        refreshBtn.style.transform = 'scale(0.95)';
        refreshBtn.style.transition = 'all 0.2s ease';
        
        // Add loading overlay to activity section
        const activitySection = document.querySelector('.activity-container');
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'position-absolute top-0 start-0 w-100 h-100 glass-dark d-flex align-items-center justify-content-center';
        loadingOverlay.style.zIndex = '10';
        loadingOverlay.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary-400 mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-body mb-0 animate-pulse">Syncing activities...</p>
            </div>
        `;
        
        activitySection.style.position = 'relative';
        activitySection.appendChild(loadingOverlay);
        
        // Actually reload the page to get fresh data
        setTimeout(() => {
            window.location.reload();
        }, 800);
    }
    
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const iconMap = {
            success: 'bi-check-circle-fill text-success-500',
            error: 'bi-x-circle-fill text-danger-500',
            warning: 'bi-exclamation-triangle-fill text-warning-500',
            info: 'bi-info-circle-fill text-info-500'
        };
        
        notification.className = `position-fixed top-0 end-0 m-4 glass-card animate-float`;
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.style.transform = 'translateX(100%)';
        notification.style.transition = 'transform 0.3s ease';
        
        notification.innerHTML = `
            <div class="d-flex align-items-center p-3">
                <i class="bi ${iconMap[type]} fs-5 me-3"></i>
                <div class="flex-grow-1">
                    <p class="text-heading mb-0 font-medium">${message}</p>
                </div>
                <button type="button" class="btn-close ms-2" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in animation
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto-dismiss after 4 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 4000);
    }
</script>
<?= $this->endSection() ?>
