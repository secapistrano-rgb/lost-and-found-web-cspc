<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Simple Debug -->
    <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">
        <strong>DEBUG:</strong> 
        Notifications variable exists: <?= isset($notifications) ? 'YES' : 'NO' ?> | 
        Count: <?= isset($notifications) ? count($notifications) : 'N/A' ?> | 
        Total: <?= isset($totalNotifications) ? $totalNotifications : 'N/A' ?>
    </div>
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-bell mr-2"></i>Admin Notification Management
            </h1>
            <p class="text-muted">Manage all user notifications across the system</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="refreshStats()">
                <i class="fas fa-sync-alt mr-1"></i>Refresh Stats
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Notifications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-notifications">
                                <?= $stats['total'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Read Notifications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="read-notifications">
                                <?= $stats['read'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Unread Notifications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="unread-notifications">
                                <?= $stats['unread'] ?? 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Active Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="active-users">
                                <?= count($users) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-2"></i>Filters
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('admin/notifications') ?>" id="filter-form">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="user_email" class="form-label">User Email</label>
                        <select class="form-control" id="user_email" name="user_email">
                            <option value="">All Users</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= esc($user['user_email']) ?>" 
                                    <?= ($filters['user_email'] ?? '') === $user['user_email'] ? 'selected' : '' ?>>
                                    <?= esc($user['user_email']) ?> (<?= $user['notification_count'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="">All Types</option>
                            <option value="item" <?= ($filters['type'] ?? '') === 'item' ? 'selected' : '' ?>>Item</option>
                            <option value="claim" <?= ($filters['type'] ?? '') === 'claim' ? 'selected' : '' ?>>Claim</option>
                            <option value="match" <?= ($filters['type'] ?? '') === 'match' ? 'selected' : '' ?>>Match</option>
                            <option value="system" <?= ($filters['type'] ?? '') === 'system' ? 'selected' : '' ?>>System</option>
                            <option value="announcement" <?= ($filters['type'] ?? '') === 'announcement' ? 'selected' : '' ?>>Announcement</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-control" id="priority" name="priority">
                            <option value="">All Priorities</option>
                            <option value="low" <?= ($filters['priority'] ?? '') === 'low' ? 'selected' : '' ?>>Low</option>
                            <option value="medium" <?= ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="high" <?= ($filters['priority'] ?? '') === 'high' ? 'selected' : '' ?>>High</option>
                            <option value="urgent" <?= ($filters['priority'] ?? '') === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="is_read" class="form-label">Status</label>
                        <select class="form-control" id="is_read" name="is_read">
                            <option value="">All Status</option>
                            <option value="0" <?= ($filters['is_read'] ?? '') === '0' ? 'selected' : '' ?>>Unread</option>
                            <option value="1" <?= ($filters['is_read'] ?? '') === '1' ? 'selected' : '' ?>>Read</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="date_from" class="form-label">Date Range</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                value="<?= $filters['date_from'] ?? '' ?>">
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                value="<?= $filters['date_to'] ?? '' ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-1"></i>Apply Filters
                        </button>
                        <a href="<?= base_url('admin/notifications') ?>" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i>Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>Notifications
                <span class="badge badge-secondary ml-2"><?= $pagination['total_records'] ?></span>
            </h6>
            <div>
                <button class="btn btn-sm btn-success" onclick="bulkMarkRead()" id="bulk-mark-read" disabled>
                    <i class="fas fa-check mr-1"></i>Mark Selected as Read
                </button>
                <button class="btn btn-sm btn-danger" onclick="bulkDelete()" id="bulk-delete" disabled>
                    <i class="fas fa-trash mr-1"></i>Delete Selected
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (empty($notifications)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">No notifications found</h5>
                    <p class="text-gray-400">Try adjusting your filters or check back later.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                                </th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notifications as $notification): ?>
                                <tr class="notification-row <?= $notification['is_read'] ? 'read' : 'unread' ?>">
                                    <td>
                                        <input type="checkbox" class="notification-checkbox" 
                                            value="<?= $notification['id'] ?>">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold"><?= esc($notification['user_email']) ?></div>
                                                <small class="text-muted">ID: <?= $notification['user_id'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= getNotificationTypeColor($notification['type']) ?>">
                                            <?= ucfirst($notification['type']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= getNotificationPriorityColor($notification['priority']) ?>">
                                            <?= ucfirst($notification['priority']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="notification-message">
                                            <strong><?= esc($notification['title']) ?></strong>
                                            <p class="mb-0 text-muted small"><?= esc($notification['message']) ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($notification['is_read']): ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check mr-1"></i>Read
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation mr-1"></i>Unread
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('M j, Y g:i A', strtotime($notification['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if (!$notification['is_read']): ?>
                                                <button class="btn btn-sm btn-outline-success" 
                                                    onclick="markAsRead(<?= $notification['id'] ?>)"
                                                    title="Mark as Read">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteNotification(<?= $notification['id'] ?>)"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing <?= (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 ?> to 
                                <?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total_records']) ?> 
                                of <?= $pagination['total_records'] ?> notifications
                            </div>
                            <nav>
                                <ul class="pagination mb-0">
                                    <?php if ($pagination['has_previous']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['previous_page']])) ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($pagination['has_next']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['next_page']])) ?>">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete User Notifications Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete All User Notifications</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete ALL notifications for this user?</p>
                <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                <div class="form-group">
                    <label for="delete-user-email">User Email:</label>
                    <select class="form-control" id="delete-user-email">
                        <option value="">Select a user...</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= esc($user['user_email']) ?>">
                                <?= esc($user['user_email']) ?> (<?= $user['notification_count'] ?> notifications)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteUserNotifications()">
                    Delete All Notifications
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.notification-row.read {
    opacity: 0.7;
}

.notification-row.unread {
    background-color: #f8f9fc;
}

.notification-message {
    max-width: 300px;
}

.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}

.badge {
    font-size: 0.75em;
}
</style>

<script>
let selectedNotifications = [];

function toggleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.notification-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateSelectedNotifications();
}

function updateSelectedNotifications() {
    const checkboxes = document.querySelectorAll('.notification-checkbox:checked');
    selectedNotifications = Array.from(checkboxes).map(cb => cb.value);
    
    const bulkMarkRead = document.getElementById('bulk-mark-read');
    const bulkDelete = document.getElementById('bulk-delete');
    
    if (selectedNotifications.length > 0) {
        bulkMarkRead.disabled = false;
        bulkDelete.disabled = false;
    } else {
        bulkMarkRead.disabled = true;
        bulkDelete.disabled = true;
    }
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.notification-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedNotifications);
    });
});

function bulkMarkRead() {
    if (selectedNotifications.length === 0) {
        alert('Please select notifications to mark as read.');
        return;
    }
    
    if (!confirm(`Mark ${selectedNotifications.length} notification(s) as read?`)) {
        return;
    }
    
    fetch('<?= base_url('admin/notifications/bulk-mark-read') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            notification_ids: selectedNotifications
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.error || 'Failed to mark notifications as read');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while marking notifications as read');
    });
}

function bulkDelete() {
    if (selectedNotifications.length === 0) {
        alert('Please select notifications to delete.');
        return;
    }
    
    if (!confirm(`Delete ${selectedNotifications.length} notification(s)? This action cannot be undone.`)) {
        return;
    }
    
    fetch('<?= base_url('admin/notifications/bulk-delete') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            notification_ids: selectedNotifications
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.error || 'Failed to delete notifications');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while deleting notifications');
    });
}

function markAsRead(notificationId) {
    fetch(`<?= base_url('notifications') ?>/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showAlert('error', data.error || 'Failed to mark notification as read');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while marking notification as read');
    });
}

function deleteNotification(notificationId) {
    if (!confirm('Delete this notification? This action cannot be undone.')) {
        return;
    }
    
    fetch(`<?= base_url('notifications') ?>/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showAlert('error', data.error || 'Failed to delete notification');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while deleting notification');
    });
}

function confirmDeleteUserNotifications() {
    const userEmail = document.getElementById('delete-user-email').value;
    
    if (!userEmail) {
        alert('Please select a user.');
        return;
    }
    
    if (!confirm(`Delete ALL notifications for ${userEmail}? This action cannot be undone.`)) {
        return;
    }
    
    fetch('<?= base_url('admin/notifications/delete-user-notifications') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            user_email: userEmail
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            $('#deleteUserModal').modal('hide');
            location.reload();
        } else {
            showAlert('error', data.error || 'Failed to delete user notifications');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while deleting user notifications');
    });
}

function refreshStats() {
    fetch('<?= base_url('admin/notifications/stats') ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('total-notifications').textContent = data.stats.total || 0;
            document.getElementById('read-notifications').textContent = data.stats.read || 0;
            document.getElementById('unread-notifications').textContent = data.stats.unread || 0;
            showAlert('success', 'Statistics refreshed');
        } else {
            showAlert('error', 'Failed to refresh statistics');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while refreshing statistics');
    });
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>

<?php
function getNotificationTypeColor($type) {
    switch ($type) {
        case 'success': return 'success';
        case 'warning': return 'warning';
        case 'error': return 'danger';
        case 'info':
        default: return 'info';
    }
}

function getNotificationPriorityColor($priority) {
    switch ($priority) {
        case 'urgent': return 'danger';
        case 'high': return 'warning';
        case 'medium': return 'info';
        case 'low':
        default: return 'secondary';
    }
}
?>

<?= $this->endSection() ?>