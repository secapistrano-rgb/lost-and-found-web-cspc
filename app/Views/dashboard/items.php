<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
CSPC Lost & Found - Item Management
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .image-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }
    
    .image-modal-content {
        max-width: 90%;
        max-height: 90%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .modal-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .close-modal {
        position: absolute;
        top: 20px;
        right: 40px;
        font-size: 2rem;
        color: white;
        cursor: pointer;
    }

    .item-table {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        padding: 1.5rem;
    }
    
    .item-table th {
        background: var(--cspc-light);
        color: var(--cspc-dark);
        font-weight: 600;
    }
    
    .item-table td {
        vertical-align: middle;
    }
    
    .item-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .item-email {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        background: #e3f2fd;
        color: #1976d2;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Item Management</h1>
        <div class="d-flex align-items-center">
            <form action="<?= site_url('items') ?>" method="get" class="d-flex me-2">
                <input type="text" name="search" class="form-control" placeholder="Search items..." value="<?= esc($search ?? '') ?>">
                <button type="submit" class="btn btn-secondary ms-2">Search</button>
            </form>
            <a href="<?= site_url('items/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add New Item
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <form action="<?= site_url('items') ?>" method="get" id="filter-form">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= esc($category) ?>" <?= (($selectedCategory ?? '') == $category) ? 'selected' : '' ?>><?= esc($category) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" <?= (($selectedStatus ?? '') == 'pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="claimed" <?= (($selectedStatus ?? '') == 'claimed') ? 'selected' : '' ?>>Claimed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="condition" class="form-select">
                            <option value="">All Conditions</option>
                            <option value="lost" <?= (($selectedCondition ?? '') == 'lost') ? 'selected' : '' ?>>Lost</option>
                            <option value="found" <?= (($selectedCondition ?? '') == 'found') ? 'selected' : '' ?>>Found</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="item-table">
                <h3>All Items</h3>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Condition</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($allItems as $item): ?>
                        <tr>
                            <td>
                                <?php if ($item['image']): ?>
                                    <img src="<?= base_url('uploads/' . $item['image']) ?>" 
                                         alt="<?= esc($item['name']) ?>" 
                                         class="item-image" 
                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer;"
                                         onclick="openImageModal('<?= base_url('uploads/' . $item['image']) ?>', '<?= esc($item['name']) ?>')">
                                <?php else: ?>
                                    <div class="no-image">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($item['name']) ?></td>
                            <td><?= esc($item['category']) ?></td>
                            <td>
                                <?php
                                    $condition_badge = '';
                                    $condition = strtolower(trim($item['condition']));
                                    switch ($condition) {
                                        case 'lost':
                                            $condition_badge = 'bg-danger';
                                            break;
                                        case 'found':
                                            $condition_badge = 'bg-success';
                                            break;
                                    }
                                ?>
                                <span class="badge <?= $condition_badge ?>"><?= esc(ucfirst($item['condition'])) ?></span>
                            </td>
                            <td><?= esc(date('M d, Y', strtotime($item['found_date'] ?? $item['created_at']))) ?></td>
                            <td>
                                <?php
                                    $status_badge = '';
                                    $status = strtolower(trim($item['status']));
                                    switch ($status) {
                                        case 'pending':
                                            $status_badge = 'bg-warning text-white';
                                            break;
                                        case 'claimed':
                                            $status_badge = 'bg-success';
                                            break;
                                    }
                                ?>
                                <span class="badge <?= $status_badge ?>"><?= esc(ucfirst($item['status'])) ?></span>
                            </td>
                            <td>
                                <div class="item-actions">
                                    <a href="<?= site_url('items/view/' . $item['id']) ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= site_url('items/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $item['id'] ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="imageModal" class="image-modal">
    <span class="close-modal" onclick="closeImageModal()">&times;</span>
    <div class="image-modal-content">
        <h3 id="modalTitle"></h3>
        <img id="modalImage" class="modal-image" src="" alt="">
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function confirmDelete(itemId) {
        if (confirm('Are you sure you want to delete this item?')) {
            window.location.href = '<?= site_url('items/delete/') ?>' + itemId;
        }
    }

    function openImageModal(imageSrc, imageName) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle');
        
        modalImage.src = imageSrc;
        modalTitle.textContent = imageName;
        modal.style.display = 'flex';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('imageModal');
        if (event.target === modal) {
            closeImageModal();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (this.value === '') {
                    window.location.href = '<?= site_url('items') ?>';
                }
            });
        }

        const filterForm = document.getElementById('filter-form');
        if (filterForm) {
            const filterSelects = filterForm.querySelectorAll('select');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });
        }
    });
</script>
<?= $this->endSection() ?>
