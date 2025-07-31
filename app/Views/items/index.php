<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Item Management
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
    
    /* New styles for claim relationship indicators */
    .claim-indicator {
        font-size: 0.8rem;
        padding: 2px 6px;
        border-radius: 4px;
        margin-top: 4px;
        display: inline-block;
    }
    .has-claims {
        background-color: #e3f2fd;
        color: #1976d2;
        border: 1px solid #bbdefb;
    }
    .no-claims {
        background-color: #f3e5f5;
        color: #7b1fa2;
        border: 1px solid #e1bee7;
    }
    .claimed-item {
        background-color: #e8f5e8;
        border-left: 4px solid #4caf50;
    }
    .pending-item {
        background-color: #fff8e1;
        border-left: 4px solid #ff9800;
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Item Management</h1>
        <a href="<?= site_url('items/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Item
        </a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item Details</th>
                            <th>Status & Claims</th>
                            <th>Condition</th>
                            <th>Date Found</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $item): ?>
                                <?php
                                    $rowClass = '';
                                    if (strtolower(trim($item['status'])) === 'claimed') {
                                        $rowClass = 'claimed-item';
                                    } elseif (strtolower(trim($item['status'])) === 'pending') {
                                        $rowClass = 'pending-item';
                                    }
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <?php if (!empty($item['image'])): ?>
                                                    <img src="<?= base_url('uploads/' . $item['image']) ?>" 
                                                         alt="<?= esc($item['name']) ?>" 
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                                <?php else: ?>
                                                    <div style="width: 50px; height: 50px; background-color: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <strong><?= esc($item['name']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= esc($item['category']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <?php 
                                                $status_badge = '';
                                                $status = trim(strtolower($item['status']));
                                                if ($status == 'pending') {
                                                    $status_badge = 'bg-warning text-white';
                                                } else if ($status == 'claimed') {
                                                    $status_badge = 'bg-success';
                                                }
                                            ?>
                                            <span class="badge <?= $status_badge ?>"><?= esc(ucfirst($item['status'])) ?></span>
                                        </div>
                                        
                                        <!-- Claim relationship indicator -->
                                        <?php if (isset($item['claim_count'])): ?>
                                            <div class="mt-2">
                                                <?php if ($item['claim_count'] > 0): ?>
                                                    <span class="claim-indicator has-claims">
                                                        <i class="bi bi-person-check me-1"></i>
                                                        <?= $item['claim_count'] ?> claim(s)
                                                    </span>
                                                    <?php if (isset($item['verified_claim'])): ?>
                                                        <br>
                                                        <small class="text-success mt-1 d-block">
                                                            <i class="bi bi-check-circle me-1"></i>
                                                            Claimed by: <?= esc($item['verified_claim']['claimant_name']) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="claim-indicator no-claims">
                                                        <i class="bi bi-person-x me-1"></i>
                                                        No claims yet
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $condition_badge = '';
                                            $condition = trim(strtolower($item['condition']));
                                            if ($condition == 'lost') {
                                                $condition_badge = 'bg-danger';
                                            } else if ($condition == 'found') {
                                                $condition_badge = 'bg-success';
                                            }
                                        ?>
                                        <span class="badge <?= $condition_badge ?>"><?= esc(ucfirst($item['condition'])) ?></span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($item['created_at'])) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= site_url('items/view/' . $item['id']) ?>" class="btn btn-action-view" title="View"><i class="bi bi-eye"></i></a>
                                            <a href="<?= site_url('items/edit/' . $item['id']) ?>" class="btn btn-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                            <a href="<?= site_url('items/delete/' . $item['id']) ?>" class="btn btn-action-delete" onclick="return confirm('Are you sure you want to delete this item?');" title="Delete"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No items found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
