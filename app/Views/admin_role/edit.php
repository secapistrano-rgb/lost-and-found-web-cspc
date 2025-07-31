<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Edit Role
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Edit Role</h1>
        <a href="<?= site_url('admin_role') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Cancel
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= site_url('admin_role/update/' . $role['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="name" name="name" value="<?= esc($role['name']) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" value="<?= esc($role['email']) ?>" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                        <select class="form-control" id="role" name="role">
                            <option value="super admin" <?= $role['role'] == 'super admin' ? 'selected' : '' ?>>Super Admin</option>
                            <option value="council admin" <?= $role['role'] == 'council admin' ? 'selected' : '' ?>>Council Admin</option>
                            <option value="support staff" <?= $role['role'] == 'support staff' ? 'selected' : '' ?>>Support Staff</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Update Role
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
