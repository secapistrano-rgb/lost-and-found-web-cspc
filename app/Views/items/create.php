<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Add New Item
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Add New Item</h1>
        <a href="<?= site_url('items') ?>" class="btn btn-outline-secondary">
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

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Enter Item Details</h5>
        </div>
        <div class="card-body">
            <form action="<?= site_url('items/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label"><i class="bi bi-box me-2"></i>Item Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= set_value('name') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label"><i class="bi bi-tag me-2"></i>Category</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="">Select Category...</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= esc($category) ?>" <?= set_select('category', $category) ?>><?= esc($category) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label"><i class="bi bi-card-text me-2"></i>Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3" required><?= set_value('description') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label"><i class="bi bi-envelope me-2"></i>Contact Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= set_value('email') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="found_date" class="form-label"><i class="bi bi-calendar-date me-2"></i>Date Found/Lost</label>
                        <input type="date" id="found_date" name="found_date" class="form-control" value="<?= set_value('found_date', date('Y-m-d')) ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="condition" class="form-label"><i class="bi bi-gem me-2"></i>Condition</label>
                        <select id="condition" name="condition" class="form-select" required>
                            <option value="">Select Condition...</option>
                            <option value="found" <?= set_select('condition', 'found') ?>>Found</option>
                            <option value="lost" <?= set_select('condition', 'lost') ?>>Lost</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label"><i class="bi bi-info-circle me-2"></i>Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="">Select Status...</option>
                            <option value="pending" <?= set_select('status', 'pending') ?>>Pending</option>
                            <option value="claimed" <?= set_select('status', 'claimed') ?>>Claimed</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label"><i class="bi bi-image me-2"></i>Upload Image</label>
                    <input type="file" id="image" name="image" class="form-control" onchange="previewImage()">
                    <img id="image-preview" src="#" alt="Image Preview" class="mt-2" style="display: none; max-height: 200px;"/>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function previewImage() {
        const image = document.querySelector('#image');
        const preview = document.querySelector('#image-preview');
        
        if (image.files && image.files[0]) {
            preview.style.display = 'block';
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(image.files[0]);
        } else {
            preview.style.display = 'none';
            preview.src = '#';
        }
    }
</script>
<?= $this->endSection() ?>