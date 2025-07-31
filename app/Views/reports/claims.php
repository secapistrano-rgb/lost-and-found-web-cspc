<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Claims Report
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
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        <h1 class="mb-0">Claims Report</h1>
        <a href="<?= site_url('reports') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <h5 class="mb-3">Filter Options</h5>
        <form method="GET" action="<?= site_url('reports/claims') ?>">
            <div class="row">
                <div class="col-md-4">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="<?= $dateFrom ?>">
                </div>
                <div class="col-md-4">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="<?= $dateTo ?>">
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= $selectedStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="verified" <?= $selectedStatus === 'verified' ? 'selected' : '' ?>>Verified</option>
                        <option value="rejected" <?= $selectedStatus === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="<?= site_url('reports/claims') ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="stats-summary">
        <div class="row">
            <div class="col-md-3 text-center">
                <h3><?= number_format($totalClaims) ?></h3>
                <p class="mb-0">Total Claims</p>
            </div>
            <div class="col-md-3 text-center">
                <h3><?= $averageProcessingTime ?>h</h3>
                <p class="mb-0">Avg. Processing Time</p>
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
            <a href="<?= site_url('reports/export?type=claims&format=csv&date_from=' . $dateFrom . '&date_to=' . $dateTo . '&status=' . $selectedStatus) ?>" 
               class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
            </a>
            <a href="<?= site_url('reports/export?type=claims&format=json&date_from=' . $dateFrom . '&date_to=' . $dateTo . '&status=' . $selectedStatus) ?>" 
               class="btn btn-info">
                <i class="bi bi-file-earmark-code"></i> Export JSON
            </a>
        </div>
    </div>

    <!-- Status Distribution Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="filter-card">
                <h5 class="mb-3">Claims Status Distribution</h5>
                <canvas id="statusChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Claims Table -->
    <div class="table-responsive">
        <h5 class="mb-3">Claims Details</h5>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Claimant</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($claims)): ?>
                    <?php foreach($claims as $claim): ?>
                    <tr>
                        <td><?= esc($claim['id']) ?></td>
                        <td><?= esc($claim['item_name']) ?></td>
                        <td>
                            <span class="badge bg-primary"><?= esc($claim['item_category']) ?></span>
                        </td>
                        <td><?= esc($claim['claimant_name']) ?></td>
                        <td>
                            <small>
                                <?= esc($claim['claimant_contact']) ?><br>
                                <?= esc($claim['claimant_email']) ?>
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-<?= $claim['status'] === 'pending' ? 'warning' : ($claim['status'] === 'verified' ? 'success' : 'danger') ?>">
                                <?= ucfirst($claim['status']) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y H:i', strtotime($claim['created_at'])) ?></td>
                        <td><?= date('M d, Y H:i', strtotime($claim['updated_at'])) ?></td>
                        <td>
                            <a href="<?= site_url('claims/view/' . $claim['id']) ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">No claims found for the selected criteria.</td>
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
// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(function($item) { return '"' . ucfirst($item['status']) . '"'; }, $statusBreakdown)) ?>],
        datasets: [{
            label: 'Claims',
            data: [<?= implode(',', array_column($statusBreakdown, 'count')) ?>],
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