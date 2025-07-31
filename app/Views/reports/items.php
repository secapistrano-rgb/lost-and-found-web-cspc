<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Items Report
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .stats-summary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .export-buttons {
        margin-bottom: 20px;
    }
    .table-responsive {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Items Report</h1>
        <a href="<?= site_url('reports') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <h5 class="mb-3">Filter Options</h5>
        <form method="GET" action="<?= site_url('reports/items') ?>">
            <div class="row">
                <div class="col-md-3">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="<?= $dateFrom ?>">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="<?= $dateTo ?>">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="category" name="category">
                        <option value="">All Categories</option>
                        <?php foreach($categories as $cat): ?>
                        <option value="<?= esc($cat['category']) ?>" <?= $selectedCategory === $cat['category'] ? 'selected' : '' ?>>
                            <?= esc($cat['category']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= $selectedStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="claimed" <?= $selectedStatus === 'claimed' ? 'selected' : '' ?>>Claimed</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="<?= site_url('reports/items') ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="stats-summary">
        <div class="row">
            <div class="col-md-3 text-center">
                <h3><?= number_format($totalItems) ?></h3>
                <p class="mb-0">Total Items</p>
            </div>
            <div class="col-md-3 text-center">
                <h3><?= count($categoryBreakdown) ?></h3>
                <p class="mb-0">Categories</p>
            </div>
            <div class="col-md-3 text-center">
                <h3><?= date('M d, Y', strtotime($dateFrom)) ?></h3>
                <p class="mb-0">From Date</p>
            </div>
            <div class="col-md-3 text-center">
                <h3><?= date('M d, Y', strtotime($dateTo)) ?></h3>
                <p class="mb-0">To Date</p>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="export-buttons">
        <div class="btn-group">
            <a href="<?= site_url('reports/export?type=items&format=csv&date_from=' . $dateFrom . '&date_to=' . $dateTo . '&category=' . $selectedCategory . '&status=' . $selectedStatus) ?>" 
               class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
            </a>
            <a href="<?= site_url('reports/export?type=items&format=json&date_from=' . $dateFrom . '&date_to=' . $dateTo . '&category=' . $selectedCategory . '&status=' . $selectedStatus) ?>" 
               class="btn btn-info">
                <i class="bi bi-file-earmark-code"></i> Export JSON
            </a>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="filter-card">
                <h5 class="mb-3">Category Breakdown</h5>
                <canvas id="categoryChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="filter-card">
                <h5 class="mb-3">Status Distribution</h5>
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="table-responsive">
        <h5 class="mb-3">Items Details</h5>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Condition</th>
                    <th>Found Date</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td><?= esc($item['id']) ?></td>
                        <td><?= esc($item['name']) ?></td>
                        <td>
                            <span class="badge bg-primary"><?= esc($item['category']) ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?= $item['status'] === 'pending' ? 'warning' : 'success' ?>">
                                <?= ucfirst($item['status']) ?>
                            </span>
                        </td>
                        <td><?= esc($item['condition']) ?></td>
                        <td><?= $item['date'] ? date('M d, Y', strtotime($item['date'])) : 'N/A' ?></td>
                        <td><?= esc($item['email']) ?></td>
                        <td><?= date('M d, Y H:i', strtotime($item['created_at'])) ?></td>
                        <td>
                            <a href="<?= site_url('items/view/' . $item['id']) ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">No items found for the selected criteria.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . $item['category'] . '"'; }, $categoryBreakdown)) ?>],
        datasets: [{
            data: [<?= implode(',', array_column($categoryBreakdown, 'count')) ?>],
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

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . ucfirst($item['status']) . '"'; }, $statusBreakdown)) ?>],
        datasets: [{
            label: 'Items',
            data: [<?= implode(',', array_column($statusBreakdown, 'count')) ?>],
            backgroundColor: [
                '#FFC107',
                '#28A745'
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
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
<?= $this->endSection() ?>