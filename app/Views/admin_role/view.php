<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Role Details - <?= esc($role['name']) ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .profile-card {
        text-align: center;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 1rem;
        overflow: hidden; /* This is crucial for making the image circular */
        border: 3px solid #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        background-color: #e9ecef; /* Fallback color */
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* This scales and centers the image within the container */
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Role Details</h1>
        <a href="<?= site_url('admin_role') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body profile-card">
                    <div class="profile-avatar">
                        <?php
                            $imageUrl = !empty($role['profile_pic']) 
                                ? $role['profile_pic'] 
                                : get_gravatar($role['email']);
                        ?>
                        <img src="<?= $imageUrl ?>" alt="<?= esc($role['name']) ?>" referrerpolicy="no-referrer">
                    </div>
                    <h4 class="card-title mb-1"><?= esc(ucwords($role['role'])) ?></h4>
                    <p class="text-muted mb-0"><?= esc($role['name']) ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Role Information</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Name:</strong> <?= esc($role['name']) ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?= esc($role['email']) ?></li>
                    <li class="list-group-item"><strong>Role:</strong> <?= esc(ucwords($role['role'])) ?></li>
                </ul>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= site_url('admin_role/edit/' . $role['id']) ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Role
                        </a>
                        <a href="<?= site_url('admin_role/delete/' . $role['id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">
                            <i class="bi bi-trash"></i> Delete Role
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
