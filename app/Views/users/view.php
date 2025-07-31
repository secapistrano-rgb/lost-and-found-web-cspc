<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
User Details - <?= esc($user['name']) ?>
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
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e9ecef;
        font-size: 3rem;
        color: #6c757d;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">User Details</h1>
        <a href="<?= site_url('users') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body profile-card">
                    <div class="profile-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h4 class="card-title mb-1"><?= esc($user['name']) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Personal Information</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Email:</strong> <?= esc($user['email']) ?></li>
                    <li class="list-group-item"><i class="bi bi-building me-2"></i><strong>Department:</strong> <?= esc($user['department']) ?></li>
                </ul>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-key-fill me-2"></i>Account Information</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Member Since:</strong> <?= isset($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : 'N/A' ?></li>
                </ul>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= site_url('users/edit/' . $user['id']) ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit User
                        </a>
                        <a href="<?= site_url('users/delete/' . $user['id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                            <i class="bi bi-trash"></i> Delete User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
