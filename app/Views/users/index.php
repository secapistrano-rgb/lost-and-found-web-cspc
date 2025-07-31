<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
User Management
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
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">User Management</h1>
        <div class="d-flex align-items-center">
            <form action="<?= site_url('users') ?>" method="get" class="d-flex me-2">
                <input type="text" name="q" class="form-control" placeholder="Search users..." value="<?= esc($q ?? '') ?>">
                <button type="submit" class="btn btn-secondary ms-2">Search</button>
            </form>
            <a href="<?= site_url('users/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create New User
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="bi bi-key me-2"></i>ID</th>
                        <th><i class="bi bi-person me-2"></i>Name</th>
                        <th><i class="bi bi-envelope me-2"></i>Email</th>
                        <th><i class="bi bi-building me-2"></i>Department</th>
                        <th><i class="bi bi-gear me-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user['id']) ?></td>
                                <td><?= esc($user['name']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td><?= esc($user['department'] ?? '') ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('users/view/' . $user['id']) ?>" class="btn btn-action-view" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="<?= site_url('users/edit/' . $user['id']) ?>" class="btn btn-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <a href="<?= site_url('users/delete/' . $user['id']) ?>" class="btn btn-action-delete" onclick="return confirm('Are you sure you want to delete this user?');" title="Delete"><i class="bi bi-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (this.value === '') {
                    window.location.href = '<?= site_url('users') ?>';
                }
            });
        }
    });
</script>

<?= $this->endSection() ?>
