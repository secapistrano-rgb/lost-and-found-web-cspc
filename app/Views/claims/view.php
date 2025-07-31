<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Claim Details
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Claim Details</h1>
        <div>
            <a href="<?= site_url('claims/edit/' . $claim['id']) ?>" class="btn btn-warning">
                <i class="bi bi-pencil-square me-2"></i>Edit
            </a>
            <a href="<?= site_url('claims') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Claim and Item Details -->
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="bi bi-box-seam me-2"></i>Item Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?php if (!empty($claim['item_image'])): ?>
                                <img src="<?= base_url('uploads/' . $claim['item_image']) ?>" alt="<?= esc($claim['item_name']) ?>" class="img-fluid rounded">
                            <?php else: ?>
                                <div class="img-fluid rounded bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="bi bi-image-alt text-muted fs-1"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-1"><?= esc($claim['item_name']) ?></h4>
                            <p class="text-muted mb-2"><?= esc($claim['item_category']) ?></p>
                            <p><?= esc($claim['item_description']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-person-check me-2"></i>Claimant Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Claimant Name</dt>
                        <dd class="col-sm-8"><?= esc($claim['claimant_name']) ?></dd>

                        <dt class="col-sm-4">Contact</dt>
                        <dd class="col-sm-8"><?= esc($claim['claimant_contact']) ?></dd>

                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8"><?= esc($claim['claimant_email']) ?></dd>

                        <dt class="col-sm-4">Claim Date</dt>
                        <dd class="col-sm-8"><?= date('F j, Y, g:i a', strtotime($claim['created_at'])) ?></dd>

                        <dt class="col-sm-12 mt-3">Claim Description</dt>
                        <dd class="col-sm-12"><?= nl2br(esc($claim['claim_description'])) ?></dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Right Column: Status and Resolution -->
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="bi bi-info-circle me-2"></i>Claim Status</h5>
                </div>
                <div class="card-body">
                    <?php
                        $statusClass = '';
                        switch ($claim['status']) {
                            case 'approved':
                                $statusClass = 'success';
                                break;
                            case 'rejected':
                                $statusClass = 'danger';
                                break;
                            default:
                                $statusClass = 'warning';
                        }
                    ?>
                    <h3 class="text-center text-<?= $statusClass ?> mb-3">
                        <span class="badge bg-<?= $statusClass ?>-light text-<?= $statusClass ?> p-3 w-100">
                            <?= ucfirst(esc($claim['status'])) ?>
                        </span>
                    </h3>
                    <dl class="row">
                        <dt class="col-sm-5">Last Updated</dt>
                        <dd class="col-sm-7"><?= date('F j, Y, g:i a', strtotime($claim['updated_at'])) ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>