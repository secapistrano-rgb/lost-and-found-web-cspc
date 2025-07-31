<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
New Claim
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">New Claim</h1>
        <a href="<?= site_url('claims') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Cancel
        </a>
    </div>

    <?php if (session()->has('errors')) : ?>
        <div class="alert alert-danger">
            <?php foreach (session('errors') as $error) : ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form action="<?= route_to('claims.store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="item_id" class="form-label">Item to Claim</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                        <select name="item_id" id="item_id" class="form-select" required>
                            <option value="">Select an item...</option>
                            <?php if (!empty($items)):
 ?>
                                <?php foreach ($items as $item):
 ?>
                                    <option value="<?= esc($item['id']) ?>" <?= set_select('item_id', $item['id']) ?>><?= esc($item['name']) ?> (<?= esc($item['category']) ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                                        <div class="col-md-6 mb-3">
                        <label for="claimant_name" class="form-label">Claimant Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="claimant_name" id="claimant_name" class="form-control" value="<?= set_value('claimant_name') ?>" required>
                        </div>
                    </div>
                                        <div class="col-md-6 mb-3">
                        <label for="claimant_contact" class="form-label">Contact Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="tel" name="claimant_contact" id="claimant_contact" class="form-control" value="<?= set_value('claimant_contact') ?>" required>
                        </div>
                    </div>
                </div>

                                <div class="mb-3">
                    <label for="claimant_email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="claimant_email" id="claimant_email" class="form-control" value="<?= set_value('claimant_email') ?>" required>
                    </div>
                </div>

                                <div class="mb-3">
                    <label for="claim_description" class="form-label">Claim Description</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                        <textarea name="claim_description" id="claim_description" class="form-control" rows="4" required><?= set_value('claim_description') ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-shield-check"></i> Submit Claim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // No specific scripts needed for this page, but keeping the section for consistency.
</script>
<?= $this->endSection() ?>
