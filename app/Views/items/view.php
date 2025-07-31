<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Item Details - <?= esc($item['name']) ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .item-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 2.5rem;
        margin-bottom: 2rem;
    }

    .item-card .item-image {
        width: 100%;
        height: auto;
        max-height: 450px;
        object-fit: cover;
        border: 1px solid #eee;
    }

    .item-image-placeholder {
        width: 100%;
        height: 300px;
        border: 2px dashed #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }

    .item-card h2 {
        color: var(--cspc-dark);
        font-weight: 600;
    }

    .item-card .list-group-item {
        background-color: transparent;
        border-color: #e9ecef;
    }

    .item-card .badge {
        font-size: 0.9rem;
    }
    
    /* New styles for claims section */
    .claims-section {
        border-top: 1px solid #e9ecef;
        padding-top: 1rem;
        margin-top: 1rem;
    }
    .claim-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }
    .claim-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .claim-verified {
        border-color: #28a745;
        background-color: #f8fff9;
    }
    .claim-pending {
        border-color: #ffc107;
        background-color: #fffdf5;
    }
    .claim-rejected {
        border-color: #dc3545;
        background-color: #fff5f5;
    }
    .no-claims-message {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 2px dashed #dee2e6;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Item Details</h1>
        <div>
            <a href="<?= site_url('claims/create?item_id=' . $item['id']) ?>" class="btn btn-success me-2">
                <i class="bi bi-person-plus"></i> Create Claim
            </a>
            <a href="<?= site_url('items') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content Column -->
        <div class="col-lg-8">
            <!-- Item Image and Description Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><?= esc($item['name']) ?></h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($item['image'])) : ?>
                        <div class="text-center mb-4">
                            <img src="<?= base_url('uploads/' . $item['image']) ?>" alt="Item Image" class="img-fluid rounded" style="max-height: 400px;">
                        </div>
                    <?php else : ?>
                        <div class="item-image-placeholder rounded d-flex align-items-center justify-content-center mb-4">
                            <i class="bi bi-image-alt text-muted" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                    <h6 class="card-subtitle mb-2 text-muted">Description</h6>
                    <p class="card-text"><?= nl2br(esc($item['description'])) ?></p>
                </div>
            </div>
            
            <!-- Claims Section -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-people me-2"></i>Claims for this Item
                        <?php if (isset($claims) && count($claims) > 0): ?>
                            <span class="badge bg-primary"><?= count($claims) ?></span>
                        <?php endif; ?>
                    </h5>
                    <a href="<?= site_url('claims/create?item_id=' . $item['id']) ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus"></i> New Claim
                    </a>
                </div>
                <div class="card-body">
                    <?php if (isset($claims) && count($claims) > 0): ?>
                        <?php foreach ($claims as $claim): ?>
                            <div class="claim-card claim-<?= $claim['status'] ?>">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="mb-0 me-2"><?= esc($claim['claimant_name']) ?></h6>
                                            <?php if ($claim['status'] === 'verified'): ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Verified
                                                </span>
                                            <?php elseif ($claim['status'] === 'pending'): ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock me-1"></i>Pending
                                                </span>
                                            <?php elseif ($claim['status'] === 'rejected'): ?>
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Rejected
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-muted mb-2 small">
                                            <i class="bi bi-envelope me-1"></i><?= esc($claim['claimant_email']) ?> • 
                                            <i class="bi bi-telephone me-1"></i><?= esc($claim['claimant_contact']) ?>
                                        </p>
                                        <p class="mb-2"><?= esc($claim['claim_description']) ?></p>
                                        <small class="text-muted">
                                            Submitted: <?= date('F j, Y, g:i a', strtotime($claim['created_at'])) ?>
                                            <?php if ($claim['updated_at'] !== $claim['created_at']): ?>
                                                • Updated: <?= date('F j, Y, g:i a', strtotime($claim['updated_at'])) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="ms-3">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= site_url('claims/view/' . $claim['id']) ?>" class="btn btn-outline-primary" title="View Claim">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= site_url('claims/edit/' . $claim['id']) ?>" class="btn btn-outline-warning" title="Edit Claim">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-claims-message">
                            <i class="bi bi-person-x" style="font-size: 3rem; color: #dee2e6;"></i>
                            <h6 class="mt-3 mb-2">No Claims Yet</h6>
                            <p class="mb-3">This item hasn't been claimed by anyone yet.</p>
                            <a href="<?= site_url('claims/create?item_id=' . $item['id']) ?>" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-2"></i>Create First Claim
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <!-- Item Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Details</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Category:</strong> <?= esc($item['category']) ?></li>
                    <li class="list-group-item"><strong>Status:</strong>
                        <?php
                            $status_badge = '';
                            $status = strtolower(trim($item['status']));
                            switch ($status) {
                                case 'pending':
                                    $status_badge = 'bg-warning text-white';
                                    break;
                                case 'claimed':
                                    $status_badge = 'bg-success';
                                    break;
                            }
                        ?>
                        <span class="badge <?= $status_badge ?>"><?= esc(ucfirst($item['status'])) ?></span>
                        
                        <?php if ($status === 'claimed' && isset($claims)): ?>
                            <?php foreach ($claims as $claim): ?>
                                <?php if ($claim['status'] === 'verified'): ?>
                                    <br><small class="text-success mt-1">
                                        <i class="bi bi-person-check me-1"></i>
                                        Claimed by: <?= esc($claim['claimant_name']) ?>
                                    </small>
                                    <?php break; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item"><strong>Condition:</strong>
                        <?php
                            $condition_badge = '';
                            $condition = strtolower(trim($item['condition']));
                            switch ($condition) {
                                case 'lost':
                                    $condition_badge = 'bg-danger';
                                    break;
                                case 'found':
                                    $condition_badge = 'bg-success';
                                    break;
                            }
                        ?>
                        <span class="badge <?= $condition_badge ?>"><?= esc(ucfirst($item['condition'])) ?></span>
                    </li>
                    <li class="list-group-item"><strong>Date Found/Lost:</strong> <?= date('M d, Y', strtotime($item['found_date'] ?? $item['created_at'])) ?></li>
                    <?php if (!empty($item['email'])) : ?>
                        <li class="list-group-item"><strong>Contact Email:</strong> <?= esc($item['email']) ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Actions Card -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= site_url('items/edit/' . $item['id']) ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Item
                        </a>
                        <a href="<?= site_url('items/delete/' . $item['id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                            <i class="bi bi-trash"></i> Delete Item
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>