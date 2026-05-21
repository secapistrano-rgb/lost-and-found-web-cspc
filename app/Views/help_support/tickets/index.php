<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Prevent all scrolling */
    body, html {
        overflow: hidden !important;
        height: 100vh !important;
        max-height: 100vh !important;
    }
    
    .tickets-page {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        height: 100vh;
        max-height: 100vh;
        overflow: hidden;
        padding: 2rem 0;
    }
    
    .page-header {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
        border-top: 4px solid #0d6efd;
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-subtitle {
        color: #6c757d;
        margin: 0.5rem 0 0 0;
        font-size: 1.1rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.75rem;
        box-shadow: 0 4px 25px rgba(0,0,0,0.08);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-accent);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 35px rgba(0,0,0,0.12);
    }
    
    .stat-card.total { --card-accent: #0d6efd; }
    .stat-card.open { --card-accent: #fd7e14; }
    .stat-card.progress { --card-accent: #0dcaf0; }
    .stat-card.resolved { --card-accent: #198754; }
    
    .stat-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .stat-info h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        color: #2c3e50;
    }
    
    .stat-info p {
        margin: 0.25rem 0 0 0;
        color: #6c757d;
        font-weight: 500;
        font-size: 0.95rem;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .stat-card.total .stat-icon { background: linear-gradient(135deg, #0d6efd, #0056b3); }
    .stat-card.open .stat-icon { background: linear-gradient(135deg, #fd7e14, #e55a00); }
    .stat-card.progress .stat-icon { background: linear-gradient(135deg, #0dcaf0, #0aa2c0); }
    .stat-card.resolved .stat-icon { background: linear-gradient(135deg, #198754, #146c43); }
    
    .tickets-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.08);
        border: none;
        overflow: hidden;
    }
    
    .tickets-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .tickets-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }
    
    .filter-controls {
        display: flex;
        gap: 0.75rem;
    }
    
    .filter-select {
        min-width: 140px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .filter-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .tickets-table {
        margin: 0;
    }
    
    .table-responsive {
        overflow: hidden !important;
        max-height: calc(100vh - 300px);
    }
    
    .tickets-table thead th {
        background: #f8f9fa;
        border: none;
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: #495057;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .tickets-table tbody td {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #f1f3f4;
        vertical-align: middle;
    }
    
    .tickets-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .tickets-table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.001);
    }
    
    .ticket-number {
        font-weight: 700;
        color: #0d6efd;
        font-size: 0.9rem;
    }
    
    .ticket-subject {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }
    
    .ticket-preview {
        color: #6c757d;
        font-size: 0.8rem;
        line-height: 1.4;
    }
    
    .user-info .user-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
    }
    
    .user-info .user-email {
        color: #6c757d;
        font-size: 0.8rem;
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .actions-dropdown .btn {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .actions-dropdown .btn:hover {
        background: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    /* Base button styling - Exact Feedback Management Style */
    .btn-circle {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 0.375rem;
        border: none;
        color: white;
        margin: 0 0.125rem;
        transition: all 0.3s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        text-decoration: none;
    }

    .btn-circle:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: scale(1.1) translateY(-4px);
        color: white;
        text-decoration: none;
    }

    .btn-circle:active {
        transform: scale(0.95);
    }

    .btn-circle svg {
        width: 0.75rem;
        height: 0.75rem;
        transition: transform 0.3s ease;
    }

    .btn-circle:hover svg {
        transform: rotate(12deg);
    }

    /* Legacy icon support */
    .btn-circle i {
        transition: transform 0.3s ease;
        color: white;
        font-size: 0.75rem;
        width: 12px;
        height: 12px;
    }

    .btn-circle:hover i {
        transform: rotate(12deg);
    }

    /* View Button - Bright Blue Gradient */
    .btn-view {
        background: linear-gradient(to right, #3b82f6, #2563eb);
        color: white;
    }

    .btn-view:hover {
        background: linear-gradient(to right, #60a5fa, #3b82f6);
        color: white;
    }

    /* Reply Button - Bright Green Gradient */
    .btn-reply {
        background: linear-gradient(to right, #10b981, #059669);
        color: white;
    }

    .btn-reply:hover {
        background: linear-gradient(to right, #34d399, #10b981);
        color: white;
    }

    /* Edit Button - Bright Yellow/Orange Gradient */
    .btn-edit {
        background: linear-gradient(to right, #f59e0b, #d97706);
        color: white;
    }

    .btn-edit:hover {
        background: linear-gradient(to right, #fbbf24, #f59e0b);
        color: white;
    }

    /* Delete Button - Bright Red Gradient */
    .btn-delete {
        background: linear-gradient(to right, #ef4444, #dc2626);
        color: white;
    }

    .btn-delete:hover {
        background: linear-gradient(to right, #f87171, #ef4444);
        color: white;
    }

    /* More Actions Button - Bright Gray Gradient */
    .btn-more {
        background: linear-gradient(to right, #6b7280, #4b5563);
        color: white;
    }

    .btn-more:hover {
        background: linear-gradient(to right, #9ca3af, #6b7280);
        color: white;
    }

    .btn-more::after {
        display: none;
    }

    /* Action buttons container */
    .action-buttons {
        align-items: center;
        justify-content: flex-start;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-state i {
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }
    
    .empty-state h5 {
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #adb5bd;
        margin: 0;
    }
    
    .refresh-btn {
        background: linear-gradient(135deg, #0d6efd, #0056b3);
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .refresh-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }
    
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-controls {
            flex-direction: column;
        }
        
        .filter-select {
            min-width: auto;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="tickets-page">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header rounded-2xl overflow-hidden">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">
                        <?= $title ?>
                    </h1>
                    <p class="page-subtitle">Manage and track all support tickets</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <a href="<?= site_url('help-support') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                    <!-- <button class="btn btn-primary refresh-btn" onclick="refreshTickets()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button> -->
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="tickets-card">
            <div class="tickets-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="tickets-title"></h5>
                    <div class="filter-controls">
                        <!-- Search Input -->
                        <div class="position-relative">
                            <div class="position-absolute top-50 start-0 translate-middle-y ps-3">
                                <i class="fas fa-search text-muted" id="searchIcon"></i>
                                <div class="spinner-border spinner-border-sm text-primary d-none" id="searchSpinner" role="status">
                                    <span class="visually-hidden">Searching...</span>
                                </div>
                            </div>
                            <input type="text" id="ticketSearch" placeholder="Search tickets..." 
                                   class="form-control ps-5 filter-select" 
                                   style="min-width: 250px; border-radius: 8px; transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#0d6efd'; this.style.boxShadow='0 0 0 0.2rem rgba(13, 110, 253, 0.25)'"
                                   onblur="this.style.borderColor='#dee2e6'; this.style.boxShadow='none'">
                        </div>
                        <select class="form-select filter-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                        <select class="form-select filter-select" id="priorityFilter">
                            <option value="">All Priority</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($tickets)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-4x"></i>
                        <h5>No support tickets found</h5>
                        <p>Support tickets will appear here when users submit them.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table tickets-table" id="ticketsTable">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Last Reply</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets as $ticket): ?>
                                        <tr data-status="<?= $ticket['status'] ?>" data-priority="<?= $ticket['priority'] ?>">
                                            <td>
                                                <div class="user-info">
                                                    <div class="user-name"><?= esc($ticket['user_name']) ?></div>
                                                    <div class="user-email"><?= esc($ticket['user_email']) ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?= ucwords(str_replace('_', ' ', $ticket['category'])) ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $priorityColors = [
                                                    'high' => 'danger',
                                                    'medium' => 'warning',
                                                    'low' => 'success'
                                                ];
                                                $priorityColor = $priorityColors[$ticket['priority']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $priorityColor ?>">
                                                    <?= ucfirst($ticket['priority']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $statusColors = [
                                                    'open' => 'warning',
                                                    'in_progress' => 'info',
                                                    'resolved' => 'success',
                                                    'closed' => 'secondary'
                                                ];
                                                $statusColor = $statusColors[$ticket['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $statusColor ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M j, Y g:i A', strtotime($ticket['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if ($ticket['last_response_at']): ?>
                                                    <small class="text-muted">
                                                        <?= date('M j, Y g:i A', strtotime($ticket['last_response_at'])) ?>
                                                    </small>
                                                <?php else: ?>
                                                    <small class="text-muted">No replies</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2 action-buttons">
                                                    <!-- View Button -->
                                                    <a href="<?= site_url('help-support/tickets/view/' . $ticket['id']) ?>" 
                                                       class="btn-circle btn-view" 
                                                       title="View Details">
                                                        <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    
                                                    <!-- Reply Button -->
                                                    <?php if ($ticket['status'] !== 'closed'): ?>
                                                        <a href="<?= site_url('help-support/tickets/reply/' . $ticket['id']) ?>" 
                                                           class="btn-circle btn-reply" 
                                                           title="Reply">
                                                            <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <!-- Edit Button -->
                                                    <a href="<?= site_url('help-support/tickets/edit/' . $ticket['id']) ?>" 
                                                       class="btn-circle btn-edit" 
                                                       title="Edit Ticket">
                                                        <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    
                                                    <!-- Delete Button -->
                                                    <button class="btn-circle btn-delete" 
                                                            title="Delete Ticket"
                                                            onclick="confirmDelete('<?= esc($ticket['id']) ?>')">
                                                        <svg class="w-3 h-3 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- More Actions Button -->
                                                    <?php if ($ticket['status'] !== 'closed'): ?>
                                                        <div class="dropdown">
                                                            <button class="btn-circle btn-more dropdown-toggle" 
                                                                    type="button" 
                                                                    data-bs-toggle="dropdown"
                                                                    title="More Actions">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <?php if ($ticket['status'] === 'open'): ?>
                                                                    <li>
                                                                        <a class="dropdown-item" 
                                                                           href="#" 
                                                                           onclick="updateStatus(<?= $ticket['id'] ?>, 'in_progress')">
                                                                            <i class="fas fa-play me-2"></i>Start Progress
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <?php if ($ticket['status'] === 'in_progress'): ?>
                                                                    <li>
                                                                        <a class="dropdown-item" 
                                                                           href="#" 
                                                                           onclick="updateStatus(<?= $ticket['id'] ?>, 'resolved')">
                                                                            <i class="fas fa-check me-2"></i>Mark Resolved
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <?php if ($ticket['status'] === 'resolved'): ?>
                                                                    <li>
                                                                        <a class="dropdown-item" 
                                                                           href="#" 
                                                                           onclick="updateStatus(<?= $ticket['id'] ?>, 'closed')">
                                                                            <i class="fas fa-times me-2"></i>Close Ticket
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    Showing <?= $pager->getFirstItem() ?> to <?= $pager->getLastItem() ?> of <?= $pager->getTotal() ?> results
                                </div>
                                <div>
                                    <?= $pager->links('tickets', 'default_full') ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filter functionality
const statusFilter = document.getElementById('statusFilter');
const priorityFilter = document.getElementById('priorityFilter');
const tableRows = document.querySelectorAll('#ticketsTable tbody tr');

function filterTable() {
    const statusValue = statusFilter.value;
    const priorityValue = priorityFilter.value;
    
    tableRows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        const rowPriority = row.getAttribute('data-priority');
        
        const statusMatch = !statusValue || rowStatus === statusValue;
        const priorityMatch = !priorityValue || rowPriority === priorityValue;
        
        if (statusMatch && priorityMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

statusFilter.addEventListener('change', filterTable);
priorityFilter.addEventListener('change', filterTable);

// Update ticket status
function updateStatus(ticketId, newStatus) {
    if (confirm(`Are you sure you want to update this ticket status to "${newStatus.replace('_', ' ')}"?`)) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/help-support/tickets/update-status/${ticketId}`;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        // Add status input
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = newStatus;
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Delete ticket confirmation
function confirmDelete(ticketId) {
    if (confirm('Are you sure you want to delete this ticket? This action cannot be undone and will also delete all associated responses.')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('help-support/tickets/delete/') ?>' + ticketId;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        // Add method override for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Refresh tickets
function refreshTickets() {
    window.location.reload();
}

// Auto-refresh every 30 seconds
setInterval(function() {
    // Only refresh if no modals are open
    if (!document.querySelector('.modal.show')) {
        const currentTime = new Date().getTime();
        const lastRefresh = localStorage.getItem('lastTicketRefresh') || 0;
        
        // Refresh if more than 30 seconds have passed
        if (currentTime - lastRefresh > 30000) {
            localStorage.setItem('lastTicketRefresh', currentTime);
            refreshTickets();
        }
    }
}, 30000);

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('ticketSearch');
    const searchIcon = document.getElementById('searchIcon');
    const searchSpinner = document.getElementById('searchSpinner');
    const ticketRows = document.querySelectorAll('#ticketsTable tbody tr');
    let searchTimeout;
    
    if (searchInput) {
        // Auto-search with debouncing
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Show spinner and hide search icon
            if (searchTerm.length > 0) {
                searchIcon.classList.add('d-none');
                searchSpinner.classList.remove('d-none');
            }
            
            // Debounce search for 300ms
            searchTimeout = setTimeout(() => {
                performSearch(searchTerm);
                
                // Hide spinner and show search icon
                searchSpinner.classList.add('d-none');
                searchIcon.classList.remove('d-none');
            }, 300);
        });
        
        // Immediate search on focus if there's already text
        searchInput.addEventListener('focus', function() {
            if (this.value.trim()) {
                performSearch(this.value.toLowerCase().trim());
            }
        });
    }
    
    function performSearch(searchTerm) {
        ticketRows.forEach(function(row) {
            // Get text content from various fields
            const ticketId = row.cells[0]?.textContent.toLowerCase() || '';
            const userInfo = row.cells[0]?.textContent.toLowerCase() || '';
            const category = row.cells[1]?.textContent.toLowerCase() || '';
            const priority = row.cells[2]?.textContent.toLowerCase() || '';
            const status = row.cells[3]?.textContent.toLowerCase() || '';
            const created = row.cells[4]?.textContent.toLowerCase() || '';
            const lastReply = row.cells[5]?.textContent.toLowerCase() || '';
            
            // Search in all visible text content
            const allText = row.textContent.toLowerCase();
            
            const matchesSearch = searchTerm === '' || allText.includes(searchTerm);
            
            if (matchesSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide "no tickets" message
        const visibleRows = Array.from(ticketRows).filter(row => row.style.display !== 'none');
        const ticketsTable = document.querySelector('#ticketsTable');
        
        if (visibleRows.length === 0 && searchTerm !== '') {
            if (ticketsTable) ticketsTable.style.display = 'none';
            if (!document.querySelector('.no-search-results')) {
                const noResultsDiv = document.createElement('div');
                noResultsDiv.className = 'no-search-results text-center py-5';
                noResultsDiv.innerHTML = `
                    <div class="mb-3">
                        <i class="fas fa-search text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted">No tickets found</h5>
                    <p class="text-muted">Try adjusting your search terms</p>
                `;
                document.querySelector('.tickets-card .card-body').appendChild(noResultsDiv);
            }
        } else {
            if (ticketsTable) ticketsTable.style.display = '';
            const noResultsDiv = document.querySelector('.no-search-results');
            if (noResultsDiv) noResultsDiv.remove();
        }
    }
});
</script>
<?= $this->endSection() ?>