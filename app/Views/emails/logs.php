<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    /* Compact Design System */
    .compact-header {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(20px);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .compact-title {
        font-size: 1.75rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.25rem;
    }
    
    .compact-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }
    

    
    /* Compact Glass Card */
    .glass-compact {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    /* Compact Filters */
    .filters-compact {
        background: rgba(248, 250, 252, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .filter-group {
        margin-bottom: 0.75rem;
    }
    
    .filter-group:last-child {
        margin-bottom: 0;
    }
    
    .filter-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-control-compact {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        border: 1px solid rgba(209, 213, 219, 0.8);
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
    }
    
    .form-control-compact:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: white;
    }
    
    /* Compact Buttons */
    .btn-compact {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-compact.primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }
    
    .btn-compact.primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    .btn-compact.secondary {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #374151;
    }
    
    .btn-compact.secondary:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.2));
        transform: translateY(-1px);
        color: #1f2937;
    }
    
    /* Compact Table */
    .table-compact {
        font-size: 0.875rem;
        margin-bottom: 0;
    }
    
    .table-compact thead th {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        color: #374151;
        font-weight: 600;
        padding: 0.75rem 0.5rem;
        border: none;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    
    .table-compact tbody tr {
        transition: all 0.3s ease;
        border: none;
    }
    
    .table-compact tbody tr:hover {
        background: rgba(59, 130, 246, 0.05);
        transform: scale(1.005);
    }
    
    .table-compact tbody td {
        padding: 0.75rem 0.5rem;
        border: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        vertical-align: middle;
    }
    
    /* Compact Badges */
    .badge-compact {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.625rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .badge-compact.sent {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .badge-compact.failed {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .badge-compact.pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    

    
    /* Glass Button */
    .glass-btn {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        color: #374151;
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }
    
    .glass-btn:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.2));
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: #1f2937;
        text-decoration: none;
    }
</style>

<div class="container-fluid py-3">
    <!-- Compact Header -->
    <div class="compact-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="compact-title">Email Logs</h1>
                <p class="compact-subtitle mb-0">Monitor and manage email delivery history</p>
            </div>
            <a href="<?= site_url('emails') ?>" class="glass-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Email Management
            </a>
        </div>
    </div>



    <!-- Compact Email History -->
    <div class="glass-compact">
        <div class="p-3" style="background: linear-gradient(135deg, #f8fafc, #e2e8f0); border-bottom: 1px solid rgba(0, 0, 0, 0.05);">
            <h6 class="mb-0" style="font-weight: 700; color: #374151; font-size: 1rem;">Email History</h6>
        </div>
        <div class="p-3">
            <!-- Compact Search and Filter -->
            <div class="filters-compact">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="filter-group">
                            <div class="filter-label">Search</div>
                            <input type="text" class="form-control-compact" id="searchInput" 
                                   placeholder="Search by recipient, subject, or sender..." 
                                   value="<?= esc($filters['search'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="filter-group">
                            <div class="filter-label">Status</div>
                            <select class="form-control-compact" id="statusFilter">
                                <option value="all" <?= (!isset($filters['status']) || $filters['status'] === 'all') ? 'selected' : '' ?>>All Status</option>
                                <option value="sent" <?= (isset($filters['status']) && $filters['status'] === 'sent') ? 'selected' : '' ?>>Sent</option>
                                <option value="failed" <?= (isset($filters['status']) && $filters['status'] === 'failed') ? 'selected' : '' ?>>Failed</option>
                                <option value="pending" <?= (isset($filters['status']) && $filters['status'] === 'pending') ? 'selected' : '' ?>>Pending Send</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="filter-group">
                            <div class="filter-label">From Date</div>
                            <input type="date" class="form-control-compact" id="dateFromFilter" 
                                   value="<?= esc($filters['date_from'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="filter-group">
                            <div class="filter-label">To Date</div>
                            <input type="date" class="form-control-compact" id="dateToFilter" 
                                   value="<?= esc($filters['date_to'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="filter-group">
                            <div class="filter-label">&nbsp;</div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn-compact primary" onclick="applyFilters()">
                                    <i class="fas fa-filter"></i>
                                    Apply
                                </button>
                                <button type="button" class="btn-compact secondary" onclick="clearFilters()">
                                    <i class="fas fa-times"></i>
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table-compact" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Recipient</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Sent By</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?= $log['id'] ?></td>
                                    <td><?= esc($log['recipient_email']) ?></td>
                                    <td><?= esc($log['subject']) ?></td>
                                    <td>
                                        <?php
                                        $statusBadge = '';
                                        switch ($log['status']) {
                                            case 'sent':
                                                $statusBadge = 'sent';
                                                break;
                                            case 'failed':
                                                $statusBadge = 'failed';
                                                break;
                                            case 'pending':
                                                $statusBadge = 'pending';
                                                break;
                                            default:
                                                $statusBadge = 'pending';
                                        }
                                        ?>
                                        <span class="badge-compact <?= $statusBadge ?>">
                                            <i class="fas fa-<?= $log['status'] === 'sent' ? 'check' : ($log['status'] === 'failed' ? 'times' : 'clock') ?>"></i>
                                            <?= ucfirst($log['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($log['sent_by_name'] ?? 'System') ?></td>
                                    <td><?= date('M d, Y H:i', strtotime($log['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center" style="padding: 2rem; color: #6b7280; font-style: italic;">
                                    <i class="fas fa-inbox fa-2x mb-2" style="color: #d1d5db;"></i><br>
                                    No email logs found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- Notification Container -->
<div id="notificationContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

<script>
// Modern Notification System
function showNotification(message, type = 'info', duration = 4000) {
    const container = document.getElementById('notificationContainer');
    const notification = document.createElement('div');
    
    const colors = {
        success: 'linear-gradient(135deg, #10b981, #059669)',
        error: 'linear-gradient(135deg, #ef4444, #dc2626)',
        warning: 'linear-gradient(135deg, #f59e0b, #d97706)',
        info: 'linear-gradient(135deg, #3b82f6, #1d4ed8)'
    };
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    
    notification.style.cssText = `
        background: ${colors[type]};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        font-size: 0.875rem;
        transform: translateX(100%);
        transition: all 0.3s ease;
        cursor: pointer;
        min-width: 300px;
    `;
    
    notification.innerHTML = `
        <i class="${icons[type]}"></i>
        <span>${message}</span>
        <i class="fas fa-times ml-auto" style="opacity: 0.7; font-size: 0.75rem;"></i>
    `;
    
    container.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove
    const removeNotification = () => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    };
    
    notification.addEventListener('click', removeNotification);
    setTimeout(removeNotification, duration);
}

function getStatusClass(status) {
    switch (status) {
        case 'sent': return 'sent';
        case 'failed': return 'failed';
        case 'pending': return 'pending';
        default: return 'pending';
    }
}







// Enhanced Search and Filter Functions
function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const dateFrom = document.getElementById('dateFromFilter').value;
    const dateTo = document.getElementById('dateToFilter').value;
    
    // Build query parameters
    const params = new URLSearchParams();
    if (searchTerm) params.append('search', searchTerm);
    if (statusFilter && statusFilter !== 'all') params.append('status', statusFilter);
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    
    // Show loading notification
    showNotification('Applying filters...', 'info', 2000);
    
    // Reload page with filters
    const currentUrl = window.location.pathname;
    const newUrl = params.toString() ? `${currentUrl}?${params.toString()}` : currentUrl;
    
    setTimeout(() => {
        window.location.href = newUrl;
    }, 500);
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('dateFromFilter').value = '';
    document.getElementById('dateToFilter').value = '';
    
    showNotification('Clearing filters...', 'info', 2000);
    
    // Reload page without filters
    setTimeout(() => {
        window.location.href = window.location.pathname;
    }, 500);
}

// Enhanced event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Real-time search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    }
    
    // Auto-apply filters when status changes
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            if (this.value !== 'all') {
                applyFilters();
            }
        });
    }
    
    // Add loading states to buttons
    const applyBtn = document.querySelector('.btn-compact.primary');
    const clearBtn = document.querySelector('.btn-compact.secondary');
    
    if (applyBtn) {
        applyBtn.addEventListener('click', function() {
            this.style.opacity = '0.7';
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Applying...';
        });
    }
    
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            this.style.opacity = '0.7';
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Clearing...';
        });
    }
});
</script>
<?= $this->endSection() ?>