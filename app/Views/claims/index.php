<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Claims Management
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .btn-action-view {
        background-color: #00B2D4;
        color: black !important;
    }
    .btn-action-edit {
        background-color: #FFC107;
        color: black !important;
    }
    .btn-action-delete {
        background-color: #E53935;
        color: white !important;
    }
    .btn-group.btn-group-sm > .btn {
        padding: .25rem .5rem;
        font-size: .875rem;
    }
    .btn-group .btn:hover {
        opacity: 0.8;
    }
    .btn-group {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: .3rem;
    }
    .btn-group .btn {
        border: none;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: .3rem;
        border-bottom-left-radius: .3rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: .3rem;
        border-bottom-right-radius: .3rem;
    }
    
    /* New styles for improved relationship display */
    .item-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    .item-placeholder {
        width: 50px;
        height: 50px;
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }
    .claim-item-link {
        text-decoration: none;
        color: inherit;
        transition: all 0.2s ease;
    }
    .claim-item-link:hover {
        color: #0d6efd;
        text-decoration: none;
    }
    .status-timeline {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .relationship-indicator {
        border-left: 3px solid #0d6efd;
        padding-left: 10px;
        margin-left: 5px;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Claims Management</h1>
        <a href="<?= site_url('claims/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Claim
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="form-group me-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="verified">Verified</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search claims...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Item & Claim Details</th>
                            <th>Claimant</th>
                            <th class="text-center">Status & Timeline</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="claimsTableBody">
                        <?php if (!empty($claims)): ?>
                            <?php foreach($claims as $index => $claim): ?>
                            <tr>
                                <td class="text-center align-middle"><?= $index + 1 ?></td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <!-- Item thumbnail -->
                                        <div class="me-3">
                                            <?php if (!empty($claim['item_image'])): ?>
                                                <img src="<?= base_url('uploads/' . $claim['item_image']) ?>" 
                                                     alt="<?= esc($claim['item_name']) ?>" 
                                                     class="item-thumbnail">
                                            <?php else: ?>
                                                <div class="item-placeholder">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Item and claim info -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <a href="<?= site_url('items/view/' . $claim['item_id']) ?>" 
                                                   class="claim-item-link me-2">
                                                    <strong><?= esc($claim['item_name']) ?></strong>
                                                    <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.8rem;"></i>
                                                </a>
                                                <span class="badge bg-light text-dark"><?= esc($claim['item_category']) ?></span>
                                            </div>
                                            <div class="relationship-indicator">
                                                <small class="text-muted">
                                                    <i class="bi bi-arrow-right me-1"></i>
                                                    Claim ID: #<?= $claim['id'] ?> • 
                                                    Submitted: <?= date('M d, Y', strtotime($claim['created_at'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div>
                                        <strong><?= esc($claim['claimant_name']) ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-envelope me-1"></i><?= esc($claim['claimant_email']) ?>
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-telephone me-1"></i><?= esc($claim['claimant_contact']) ?>
                                        </small>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="mb-2">
                                        <?php if ($claim['status'] === 'pending'): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock me-1"></i>Pending
                                            </span>
                                        <?php elseif ($claim['status'] === 'verified'): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Verified
                                            </span>
                                        <?php elseif ($claim['status'] === 'rejected'): ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Rejected
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Unknown</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="status-timeline">
                                        <?php if ($claim['status'] === 'verified'): ?>
                                            <small class="text-success">
                                                <i class="bi bi-check2 me-1"></i>Item Claimed
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">
                                                Updated: <?= date('M d', strtotime($claim['updated_at'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('claims/view/' . $claim['id']) ?>" class="btn btn-action-view" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="<?= site_url('claims/edit/' . $claim['id']) ?>" class="btn btn-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <button class="btn btn-action-delete" onclick="confirmDelete(<?= $claim['id'] ?>)" title="Delete"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No claims found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter claims by status
        document.getElementById('statusFilter').addEventListener('change', function() {
            const status = this.value;
            const rows = document.querySelectorAll('#claimsTableBody tr');
            
            rows.forEach(row => {
                if (row.querySelector('td').colSpan === 5) {
                    row.style.display = '';
                    return;
                }
                const claimStatusCell = row.querySelector('td:nth-child(4) .badge');
                if (!claimStatusCell) return;
                
                let currentStatus = '';
                if (claimStatusCell.classList.contains('bg-warning')) currentStatus = 'pending';
                else if (claimStatusCell.classList.contains('bg-success')) currentStatus = 'verified';
                else if (claimStatusCell.classList.contains('bg-danger')) currentStatus = 'rejected';

                if (status === '' || currentStatus === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Search claims
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#claimsTableBody tr');
            
            rows.forEach(row => {
                if (row.querySelector('td').colSpan === 5) {
                    row.style.display = '';
                    return;
                }
                const itemName = row.querySelector('td:nth-child(2) strong').textContent.toLowerCase();
                const claimantName = row.querySelector('td:nth-child(3) strong').textContent.toLowerCase();
                
                if (itemName.includes(searchTerm) || claimantName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    function confirmDelete(claimId) {
        if (confirm('Are you sure you want to delete this claim?')) {
            window.location.href = '<?= site_url('claims/delete/') ?>' + claimId;
        }
    }
</script>
<?= $this->endSection() ?>
