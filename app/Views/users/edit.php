<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Edit User
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Edit User</h1>
        <a href="<?= site_url('users') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i>Cancel
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= site_url('users/update/' . $user['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="name" name="name" value="<?= esc($user['name']) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email']) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="department" class="form-label">Department</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                        <input type="text" class="form-control" id="department" name="department" value="<?= esc($user['department']) ?>" required>
                    </div>
                </div>


                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Update
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>