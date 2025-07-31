<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Role Management
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
        <h1 class="mb-0">Role Management</h1>
        <div class="d-flex align-items-center">
            <form action="<?= site_url('admin_role') ?>" method="get" class="d-flex me-2">
                <input type="text" name="q" class="form-control" placeholder="Search roles..." value="<?= esc($q ?? '') ?>">
                <button type="submit" class="btn btn-secondary ms-2">Search</button>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th><i class="bi bi-person-badge"></i> Admin</th>
                        <th><i class="bi bi-shield-check"></i> Role</th>
                        <th><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($roles)):
                        foreach ($roles as $role):
                    ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php
                                            $imageUrl = !empty($role['profile_pic']) 
                                                ? $role['profile_pic'] 
                                                : get_gravatar($role['email']);
                                        ?>
                                        <img src="<?= $imageUrl ?>" class="rounded-circle me-3" alt="<?= esc($role['name']) ?>" width="40" height="40" referrerpolicy="no-referrer">
                                        <div>
                                            <h6 class="mb-0"><?= esc($role['name']) ?></h6>
                                            <small class="text-muted"><?= esc($role['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= esc(ucwords($role['role'])) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= site_url('admin_role/view/' . $role['id']) ?>" class="btn btn-action-view" title="View"><i class="bi bi-eye"></i></a>
                                        <a href="<?= site_url('admin_role/edit/' . $role['id']) ?>" class="btn btn-action-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <a href="<?= site_url('admin_role/delete/' . $role['id']) ?>" class="btn btn-action-delete" onclick="return confirm('Are you sure you want to delete this role?');" title="Delete"><i class="bi bi-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                        endforeach;
                    else:
                    ?>
                        <tr>
                            <td colspan="3" class="text-center">No roles found.</td>
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
                    window.location.href = '<?= site_url('admin_role') ?>';
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
