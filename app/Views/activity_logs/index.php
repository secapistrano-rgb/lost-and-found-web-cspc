<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Activity Logs
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .activity-logs-page {
        background: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .page-title {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .page-title p {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }
    
    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .card-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        color: #2d3748;
        border-radius: 16px 16px 0 0 !important;
        padding: 1.5rem;
    }
    
    .activity-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.5);
        position: relative;
    }
    
    .activity-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .activity-item:last-child {
        border-bottom: none;
        border-radius: 0 0 16px 16px;
    }
    
    .activity-unread {
        background-color:rgb(201, 218, 236) !important; /* Light gray background for unread */
    }
    
    .activity-read {
        background-color: #ffffff !important; /* White background for read */
    }
    
    .unread-badge {
        background-color: #dc3545;
        color: white;
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: bold;
        margin-left: 8px;
    }
    
    .mark-read-btn {
        background: #10b981;
        border: none;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .mark-read-btn:hover {
        background: #059669;
        transform: translateY(-1px);
    }
    
    .mark-read-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }
    
    .activity-logs-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .activity-item.selected {
        background-color: #e3f2fd !important;
        border-left: 4px solid #2196f3 !important;
        transform: translateX(2px);
    }
    
    .activity-checkbox:checked {
        background-color: #2196f3;
        border-color: #2196f3;
    }
        border-left: 4px solid #2196f3;
    }
    
    .bulk-actions-bar {
        border-top: 1px solid #dee2e6;
    }
    
    .activity-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-weight: 600;
        color: white;
        font-size: 0.875rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .btn-primary {
        background: #3b82f6;
        border: 1px solid #3b82f6;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-primary:hover {
        background: #2563eb;
        border-color: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    .btn-secondary {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-secondary:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #475569;
    }
    
    .activity-header {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 16px 16px 0 0;
        padding: 1.25rem 1.5rem;
    }
    
    .activity-title {
        color: #1e293b;
        font-weight: 600;
        font-size: 1.25rem;
    }
    
    .activity-badge {
        background: #3b82f6;
        color: white;
        font-weight: 500;
        padding: 0.375rem 0.75rem;
        border-radius: 12px;
        font-size: 0.875rem;
        border: none;
    }
    

    
    .btn-outline-secondary {
        background: transparent;
        border: 1px solid #e2e8f0;
        color: #64748b;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn-outline-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #475569;
        transform: translateY(-1px);
    }
    
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: white;
    }
    
    .input-group-text {
        background: rgba(248, 250, 252, 0.9);
        border: 1px solid #e2e8f0;
        color: #64748b;
        border-radius: 8px 0 0 8px;
    }
    

    
    .badge {
        border-radius: 6px;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
    }
    
    .pagination .page-link {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        color: #64748b;
        margin: 0 0.25rem;
        transition: all 0.2s ease;
    }
    
    .pagination .page-link:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #475569;
        transform: translateY(-1px);
    }
    
    .pagination .page-item.active .page-link {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }
    
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(20px);
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px 16px 0 0;
    }
    
    .bulk-actions {
        display: none;
        background: rgba(59, 130, 246, 0.1);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(59, 130, 246, 0.2);
        padding: 0.75rem 1.5rem;
        align-items: center;
        gap: 1rem;
    }
    
    .bulk-actions.show {
        display: flex;
    }
</style>

<div class="activity-logs-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2>
                        <i class="bi bi-activity me-2"></i>Activity Logs
                        <span id="unreadCount" class="unread-badge" style="display: none;">0</span>
                    </h2>
                    <p>Monitor and track all system activities and user actions</p>
                </div>
            <!-- Search Toolbar -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="<?= base_url('activity-logs') ?>" class="row g-3 align-items-center">
                        <div class="col-12 col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search" id="searchIcon"></i>
                                    <div class="spinner-border spinner-border-sm text-primary d-none" id="searchSpinner" role="status">
                                        <span class="visually-hidden">Searching...</span>
                                    </div>
                                </span>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search activity logs (auto-search enabled)..." 
                                       value="<?= esc($filters['search'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <select name="module" id="moduleFilter" class="form-select">
                                <option value="">📁 All Modules</option>                                
                                <option value="Auth" <?= (isset($filters['module']) && $filters['module'] === 'Auth') ? 'selected' : '' ?>>🔐 Authentication</option>
                                <option value="Item" <?= (isset($filters['module']) && $filters['module'] === 'Item') ? 'selected' : '' ?>>📦 Item Management</option>
                                <option value="Claim" <?= (isset($filters['module']) && $filters['module'] === 'Claim') ? 'selected' : '' ?>>✋ Claim Management</option>
                                <option value="Match" <?= (isset($filters['module']) && $filters['module'] === 'Match') ? 'selected' : '' ?>>🔗 Match Management</option>
                                <option value="User" <?= (isset($filters['module']) && $filters['module'] === 'User') ? 'selected' : '' ?>>👥 User Management</option>
                                <option value="Role" <?= (isset($filters['module']) && $filters['module'] === 'Role') ? 'selected' : '' ?>>🎭 Role Management</option>
                                <option value="Help & Support" <?= (isset($filters['module']) && $filters['module'] === 'Help & Support') ? 'selected' : '' ?>>❓ Help & Support</option>
                                <option value="Feedback Management" <?= (isset($filters['module']) && $filters['module'] === 'Feedback Management') ? 'selected' : '' ?>>📝 Feedback Management</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <a href="<?= base_url('activity-logs') ?>" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-clockwise"></i>
                                <span class="d-none d-sm-inline ms-1">Refresh</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Activity Logs Container -->
            <div class="card shadow-sm">
                <!-- Header -->
                <div class="card-header activity-header">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0 activity-title"><i class=""></i></h4>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center justify-content-md-end gap-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="selectAllActivities">
                                    <label class="form-check-label small" for="selectAllActivities">
                                        Select All
                                    </label>
                                </div>
                                <div class="dropdown">
                                    <select class="form-select form-select-sm" id="sortDropdown" onchange="sortActivities()" style="width: auto;">
                                        <option value="">Sort by...</option>
                                        <option value="date_desc">Date (Newest First)</option>
                                        <option value="date_asc">Date (Oldest First)</option>
                                        <option value="user_asc">User (A-Z)</option>
                                        <option value="user_desc">User (Z-A)</option>
                                        <option value="severity_high">Severity (High to Low)</option>
                                        <option value="severity_low">Severity (Low to High)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <div class="bulk-actions-bar bg-light border-bottom p-2" id="bulkActionsBar" style="display: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3">
                                <span id="selectedCount">0</span> selected
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-success btn-sm" onclick="markSelectedAsRead()" title="Mark Selected as Read">
                                <i class="bi bi-check-circle"></i>
                                <span class="d-none d-sm-inline ms-1">Mark Selected as Read</span>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteSelected()" title="Delete Selected">
                                <i class="bi bi-trash"></i>
                                <span class="d-none d-sm-inline ms-1">Delete Selected</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Activity List -->
                <div class="card-body p-0" style="max-height: 70vh; overflow-y: auto;">
                    <?php if (empty($activityLogs)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                            <h5 class="text-muted">No activities found</h5>
                            <p class="text-muted">Your activity inbox is empty or no activities match your filters.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($activityLogs as $index => $log): ?>
                            <?php 
            $isRead = isset($log['read_status']) && $log['read_status'] == 1;
            $bgClass = $isRead ? 'bg-white' : 'bg-light';
            $readClass = $isRead ? 'activity-read' : 'activity-unread';
            // Ensure truly unique ID - use actual ID if it exists and is not 0, otherwise use index
            $uniqueId = (isset($log['id']) && $log['id'] !== 0 && $log['id'] !== '0') ? $log['id'] : 'idx_' . $index;
            ?>
                            <div class="border-bottom p-3 activity-item clickable-row <?= $bgClass ?> <?= $readClass ?>" 
                                 data-id="<?= $uniqueId ?>" 
                                 data-read-status="<?= $isRead ? '1' : '0' ?>"
                                 data-date="<?= $log['created_at'] ?>"
                                 data-user="<?= esc($log['user_email'] ?? 'System') ?>"
                                 data-severity="<?= strtolower($log['severity'] ?? 'low') ?>"
                                 onclick="showActivityDetails(<?= $uniqueId ?>)" 
                                 style="cursor: pointer; transition: all 0.2s ease; <?= $isRead ? 'opacity: 0.7;' : '' ?>">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <input type="checkbox" class="form-check-input activity-checkbox" 
                                               value="<?= $uniqueId ?>">
                                    </div>
                                    <div class="col-auto d-none d-md-block">
                                        <?php 
                                        $avatarColors = ['#3b82f6', '#10b981', '#06b6d4', '#f59e0b', '#ef4444'];
                                        $colorIndex = ord(substr($log['user_email'] ?? 'S', 0, 1)) % count($avatarColors);
                                        $initials = strtoupper(substr($log['user_email'] ?? 'System', 0, 2));
                                        ?>
                                        <div class="activity-avatar" 
                                             style="background-color: <?= $avatarColors[$colorIndex] ?>;">
                                            <?= $initials ?>
                                        </div>
                                    </div>
                                    
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-12 col-md-8">
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="fw-medium text-dark"><?= esc($log['user_email'] ?? 'System') ?></span>
                                                    <?php if (!empty($log['user_role'])): ?>
                                                        <span class="text-muted small d-none d-sm-inline ms-1">(<?= esc($log['user_role']) ?>)</span>
                                                    <?php endif; ?>
                                                    <span class="ms-2">
                                                        <?php 
                                                        $moduleIcons = [
                                                            'User' => '👥',
                                                            'Item' => '📦', 
                                                            'Match' => '🔗',
                                                            'Claim' => '✋',
                                                            'Auth' => '🔐'
                                                        ];
                                                        echo $moduleIcons[$log['module']] ?? '📋';
                                                        ?>
                                                        <span class="d-none d-sm-inline small text-muted"><?= esc($log['module']) ?></span>
                                                    </span>
                                                </div>
                                                
                                                <div class="mb-1">
                                                    <span class="fw-medium text-primary"><?= esc($log['action']) ?></span>
                                                    <?php if (!empty($log['severity'])): ?>
                                                        <span class="ms-2">
                                                            <?php 
                                                            $severityIcons = [
                                                                'high' => '🔴',
                                                                'medium' => '🟡', 
                                                                'low' => '🟢'
                                                            ];
                                                            echo $severityIcons[strtolower($log['severity'])] ?? '⚪';
                                                            ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="text-muted small">
                                                    <span class="d-none d-md-inline"><?= esc(substr($log['description'], 0, 100)) ?><?= strlen($log['description']) > 100 ? '...' : '' ?></span>
                                                    <span class="d-md-none"><?= esc(substr($log['description'], 0, 50)) ?><?= strlen($log['description']) > 50 ? '...' : '' ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-md-4 mt-2 mt-md-0">
                                                <div class="d-flex align-items-center justify-content-between justify-content-md-end">
                                                    <div class="text-muted small">
                                                        <?php 
                                                        $time = strtotime($log['created_at']);
                                                        $now = time();
                                                        $diff = $now - $time;
                                                        
                                                        if ($diff < 3600) {
                                                            echo ceil($diff / 60) . 'm ago';
                                                        } elseif ($diff < 86400) {
                                                            echo ceil($diff / 3600) . 'h ago';
                                                        } elseif ($diff < 604800) {
                                                            echo ceil($diff / 86400) . 'd ago';
                                                        } else {
                                                            echo date('M j', $time);
                                                        }
                                                        ?>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Simple Pagination -->
            <?php if ($pagination['total_pages'] > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['has_previous']): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= base_url('activity-logs?' . http_build_query(array_merge($filters ?? [], ['page' => $pagination['previous_page']]))) ?>">
                                    Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="page-item active">
                            <span class="page-link">
                                Page <?= $pagination['current_page'] ?> of <?= $pagination['total_pages'] ?>
                            </span>
                        </li>
                        
                        <?php if ($pagination['has_next']): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= base_url('activity-logs?' . http_build_query(array_merge($filters ?? [], ['page' => $pagination['next_page']]))) ?>">
                                    Next
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

 <!-- Activity Details Modal -->
 <div class="modal fade" id="activityDetailsModal" tabindex="-1" aria-labelledby="activityDetailsModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="activityDetailsModalLabel">
                     <i class="bi bi-info-circle me-2"></i>Activity Log Details
                 </h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body" id="activityDetailsContent">
                 <div class="text-center py-4">
                     <div class="spinner-border text-primary" role="status">
                         <span class="visually-hidden">Loading...</span>
                     </div>
                     <p class="mt-2 text-muted">Loading activity details...</p>
                 </div>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
             </div>
         </div>
     </div>
 </div>
 
 <script>
 // Automatic filtering functionality
 function applyFilters() {
     const form = document.querySelector('form[action*="activity-logs"]');
     if (form) {
         form.submit();
     }
 }

 // Debounce function for automatic search
 function debounce(func, wait) {
     let timeout;
     return function executedFunction(...args) {
         const later = () => {
             clearTimeout(timeout);
             func(...args);
         };
         clearTimeout(timeout);
         timeout = setTimeout(later, wait);
     };
 }

 // Show/hide search loading indicator
 function showSearchLoading() {
     const searchIcon = document.getElementById('searchIcon');
     const searchSpinner = document.getElementById('searchSpinner');
     if (searchIcon && searchSpinner) {
         searchIcon.classList.add('d-none');
         searchSpinner.classList.remove('d-none');
     }
 }

 function hideSearchLoading() {
     const searchIcon = document.getElementById('searchIcon');
     const searchSpinner = document.getElementById('searchSpinner');
     if (searchIcon && searchSpinner) {
         searchIcon.classList.remove('d-none');
         searchSpinner.classList.add('d-none');
     }
 }

 // Automatic search functionality
 const debouncedSearch = debounce(() => {
     showSearchLoading();
     applyFilters();
 }, 500); // 500ms delay

 // Initialize automatic search when page loads
 document.addEventListener('DOMContentLoaded', function() {
     const searchInput = document.querySelector('input[name="search"]');
     const moduleFilter = document.querySelector('select[name="module"]');
     
     if (searchInput) {
         searchInput.addEventListener('input', function() {
             // Show loading immediately when user starts typing
             if (this.value.length > 0) {
                 showSearchLoading();
             } else {
                 hideSearchLoading();
             }
             debouncedSearch();
         });
     }
     
     if (moduleFilter) {
         moduleFilter.addEventListener('change', function() {
             showSearchLoading();
             applyFilters();
         });
     }
     
     // Initialize checkbox functionality
     initializeCheckboxes();
     
     // Add hover effects to clickable rows
     document.querySelectorAll('.clickable-row').forEach(row => {
         row.addEventListener('mouseenter', function() {
             this.style.backgroundColor = '#f8f9fa';
             this.style.transform = 'translateY(-1px)';
             this.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
         });
         
         row.addEventListener('mouseleave', function() {
             this.style.backgroundColor = '';
             this.style.transform = '';
             this.style.boxShadow = '';
         });
     });
 });
 
 function initializeCheckboxes() {
     // Clear any existing event listeners by re-querying elements
     const activityCheckboxes = document.querySelectorAll('.activity-checkbox');
     
     console.log('Initializing checkboxes, found:', activityCheckboxes.length);
     
     activityCheckboxes.forEach((checkbox, index) => {
         // Remove any existing event listeners
         checkbox.removeEventListener('change', handleCheckboxChange);
         checkbox.addEventListener('change', handleCheckboxChange);
         
         // Prevent the checkbox click from bubbling to the parent activity item
         checkbox.addEventListener('click', function(event) {
             event.stopPropagation();
         });
         
         // Also prevent parent clicks from affecting the checkbox
         const parentItem = checkbox.closest('.activity-item');
         if (parentItem) {
             parentItem.addEventListener('click', function(event) {
                 // Only prevent if click is not on the checkbox itself
                 if (event.target !== checkbox && !checkbox.contains(event.target)) {
                     // Allow the original onclick to work for non-checkbox clicks
                 }
             });
         }
     });
     
     // Initialize select all checkbox
     const selectAllCheckbox = document.getElementById('selectAllActivities');
     if (selectAllCheckbox) {
         selectAllCheckbox.removeEventListener('change', toggleSelectAll);
         selectAllCheckbox.addEventListener('change', toggleSelectAll);
         
         // Also add click event for better handling
         selectAllCheckbox.removeEventListener('click', function(e) { e.stopPropagation(); });
         selectAllCheckbox.addEventListener('click', function(e) { e.stopPropagation(); });
     }
     
     // Clear any existing selections
     selectedActivities.clear();
     updateBulkActions();
 }

 // Activity selection functionality
 let selectedActivities = new Set();
 
 function handleCheckboxChange(event) {
     const checkbox = event ? event.target : this;
     const activityId = checkbox.value.toString();
     console.log('Checkbox changed:', activityId, 'checked:', checkbox.checked);
     toggleActivitySelection(activityId);
 }
 
 function toggleSelectAll() {
     const selectAllCheckbox = document.getElementById('selectAllActivities');
     const activityCheckboxes = document.querySelectorAll('.activity-checkbox');
     const isChecked = selectAllCheckbox.checked;

     console.log('Toggle Select All:', isChecked, 'Found checkboxes:', activityCheckboxes.length);

     // Always clear the set first to start fresh
     selectedActivities.clear();
     
     // Debug: show all checkbox values first
     const allValues = Array.from(activityCheckboxes).map(cb => cb.value);
     console.log('All checkbox values:', allValues);
     
     activityCheckboxes.forEach((checkbox, index) => {
         // Set the checked state directly
         checkbox.checked = isChecked;
         
         const activityItem = checkbox.closest('.activity-item');
         const activityId = checkbox.value.toString();
         
         console.log(`Processing checkbox ${index}: value="${activityId}", checked=${isChecked}`);
         
         if (isChecked) {
             selectedActivities.add(activityId);
             if (activityItem) {
                 activityItem.classList.add('selected');
             }
         } else {
             if (activityItem) {
                 activityItem.classList.remove('selected');
             }
         }
     });
     
     console.log('Selected activities count:', selectedActivities.size);
     console.log('Selected activities:', Array.from(selectedActivities));
     updateBulkActions();
 }
 
 function updateBulkActions() {
     const bulkActionsBar = document.getElementById('bulkActionsBar');
     const selectedCount = document.getElementById('selectedCount');
     
     if (selectedActivities.size > 0) {
         bulkActionsBar.style.display = 'block';
         selectedCount.textContent = selectedActivities.size;
     } else {
         bulkActionsBar.style.display = 'none';
     }
 }
 
 function updateActivityCountBadge() {
     const activityBadge = document.querySelector('.activity-badge');
     const activityItems = document.querySelectorAll('.activity-item');
     const currentCount = activityItems.length;
     
     if (activityBadge) {
         activityBadge.textContent = `${currentCount} activities`;
     }
 }
 
 function toggleActivitySelection(activityId) {
     const checkbox = document.querySelector(`.activity-checkbox[value="${activityId}"]`);
     const activityItem = checkbox.closest('.activity-item');
     const activityIdStr = activityId.toString();
     
     if (checkbox.checked) {
         selectedActivities.add(activityIdStr);
         activityItem.classList.add('selected');
     } else {
         selectedActivities.delete(activityIdStr);
         activityItem.classList.remove('selected');
     }
     
     // Update select all checkbox state
     const selectAllCheckbox = document.getElementById('selectAllActivities');
     const allCheckboxes = document.querySelectorAll('.activity-checkbox');
     const checkedCheckboxes = document.querySelectorAll('.activity-checkbox:checked');
     
     if (checkedCheckboxes.length === allCheckboxes.length && allCheckboxes.length > 0) {
         selectAllCheckbox.checked = true;
         selectAllCheckbox.indeterminate = false;
     } else if (checkedCheckboxes.length === 0) {
         selectAllCheckbox.checked = false;
         selectAllCheckbox.indeterminate = false;
     } else {
         selectAllCheckbox.checked = false;
         selectAllCheckbox.indeterminate = true;
     }
     
     updateBulkActions();
 }
 
 function refreshLogs() {
     window.location.reload();
 }
 
 function deleteSelected() {
     if (selectedActivities.size === 0) {
         showToast('No activities selected', 'warning');
         return;
     }
     
     if (!confirm(`Are you sure you want to delete ${selectedActivities.size} activity log(s)? This action cannot be undone.`)) {
         return;
     }
     
     // Filter out non-database IDs (those with 'idx_' prefix) since they don't exist in database
     const activityIds = Array.from(selectedActivities).filter(id => {
         return !id.toString().startsWith('idx_');
     });
     
     if (activityIds.length === 0) {
         showToast('No valid activities selected for deletion', 'warning');
         return;
     }
     
     fetch('<?= base_url('activity-logs/delete-multiple') ?>', {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json',
             'X-Requested-With': 'XMLHttpRequest'
         },
         body: JSON.stringify({ activity_ids: activityIds })
     })
     .then(response => response.json())
     .then(data => {
         console.log('Delete response:', data);
         if (data.success) {
             // Remove from UI - all selected activities (including non-database ones)
             const allSelectedIds = Array.from(selectedActivities);
             allSelectedIds.forEach(id => {
                 const activityItem = document.querySelector(`[data-id="${id}"]`);
                 if (activityItem) {
                     activityItem.remove();
                 }
             });
             
             // Update activity count badge
             updateActivityCountBadge();
             
             // Clear selection
             selectedActivities.clear();
             document.getElementById('selectAllActivities').checked = false;
             document.querySelectorAll('.activity-checkbox').forEach(cb => cb.checked = false);
             updateBulkActions();
             updateUnreadCount();
             
             const totalSelected = allSelectedIds.length;
             const dbDeleted = activityIds.length;
             if (dbDeleted === totalSelected) {
                 showToast(`${totalSelected} activities deleted successfully`, 'success');
             } else {
                 showToast(`${dbDeleted} activities deleted successfully (${totalSelected - dbDeleted} were temporary activities)`, 'success');
             }
         } else {
             showToast(data.message || 'Failed to delete selected activities', 'error');
         }
     })
     .catch(error => {
         console.error('Error:', error);
         showToast('An error occurred while deleting activities', 'error');
     });
 }
 
 function markAsRead() {
     if (selectedActivities.size === 0) return;
     
     selectedActivities.forEach(id => {
         const activityItem = document.querySelector(`[data-id="${id}"]`);
         if (activityItem) {
             activityItem.classList.remove('unread');
             activityItem.classList.add('read');
         }
     });
     
     selectedActivities.clear();
     document.getElementById('selectAllActivities').checked = false;
     document.querySelectorAll('.activity-checkbox').forEach(cb => cb.checked = false);
     updateBulkActions();
     updateUnreadCount();
 }
 
 // Sort Activities Function
 function sortActivities() {
     const sortValue = document.getElementById('sortDropdown').value;
     if (!sortValue) return;
     
     // Get all activity items directly
     const activities = Array.from(document.querySelectorAll('.activity-item'));
     
     if (activities.length === 0) {
         console.log('No activities found to sort');
         return;
     }
     
     // Get the parent container of the first activity
     const container = activities[0].parentNode;
     
     activities.sort((a, b) => {
         switch (sortValue) {
             case 'date_desc':
                 return new Date(b.dataset.date) - new Date(a.dataset.date);
             case 'date_asc':
                 return new Date(a.dataset.date) - new Date(b.dataset.date);
             case 'user_asc':
                 return (a.dataset.user || '').localeCompare(b.dataset.user || '');
             case 'user_desc':
                 return (b.dataset.user || '').localeCompare(a.dataset.user || '');
             case 'severity_high':
                 const severityOrder = { 'high': 3, 'medium': 2, 'low': 1 };
                 return (severityOrder[b.dataset.severity] || 1) - (severityOrder[a.dataset.severity] || 1);
             case 'severity_low':
                 const severityOrderLow = { 'high': 3, 'medium': 2, 'low': 1 };
                 return (severityOrderLow[a.dataset.severity] || 1) - (severityOrderLow[b.dataset.severity] || 1);
             default:
                 return 0;
         }
     });
     
     // Reorder the activities in the DOM
     activities.forEach(activity => {
         container.appendChild(activity);
     });
     
     // Clear selections after sorting
     if (typeof selectedActivities !== 'undefined') {
         selectedActivities.clear();
         const selectAllCheckbox = document.getElementById('selectAllActivities');
         if (selectAllCheckbox) selectAllCheckbox.checked = false;
         document.querySelectorAll('.activity-checkbox').forEach(cb => cb.checked = false);
         if (typeof updateBulkActions === 'function') updateBulkActions();
     }
 }

 // Activity Details Modal Function
 function showActivityDetails(activityId) {
     const modal = new bootstrap.Modal(document.getElementById('activityDetailsModal'));
     const modalContent = document.getElementById('activityDetailsContent');
     
     // Show loading state
     modalContent.innerHTML = `
         <div class="text-center py-4">
             <div class="spinner-border text-primary" role="status">
                 <span class="visually-hidden">Loading...</span>
             </div>
             <p class="mt-2 text-muted">Loading activity details...</p>
         </div>
     `;
     
     modal.show();
     
     // Fetch activity details via AJAX
     fetch(`<?= base_url('activity-logs/details/') ?>${activityId}`)
         .then(response => response.json())
         .then(data => {
             if (data.success) {
                 const activity = data.data;
                 modalContent.innerHTML = generateActivityDetailsHTML(activity);
                 
                 // Auto-mark as read when viewing details
                 const activityItem = document.querySelector(`[data-id="${activityId}"]`);
                 if (activityItem && activityItem.getAttribute('data-read-status') === '0') {
                     markSingleAsReadSilent(activityId);
                 }
             } else {
                 modalContent.innerHTML = `
                     <div class="alert alert-danger">
                         <i class="bi bi-exclamation-triangle me-2"></i>
                         Error loading activity details: ${data.message || 'Unknown error'}
                     </div>
                 `;
             }
         })
         .catch(error => {
             console.error('Error fetching activity details:', error);
             modalContent.innerHTML = `
                 <div class="alert alert-danger">
                     <i class="bi bi-exclamation-triangle me-2"></i>
                     Failed to load activity details. Please try again.
                 </div>
             `;
         });
 }
 
 function generateActivityDetailsHTML(activity) {
     const moduleIcons = {
         'User': '👥',
         'Item': '📦', 
         'Match': '🔗',
         'Claim': '✋',
         'Auth': '🔐'
     };
     
     const severityIcons = {
         'high': '🔴',
         'medium': '🟡', 
         'low': '🟢'
     };
     
     const severityColors = {
         'high': 'danger',
         'medium': 'warning', 
         'low': 'success'
     };
     
     let detailsHTML = `
         <div class="row">
             <div class="col-12">
                 <div class="card border-0 bg-light mb-3">
                     <div class="card-body">
                         <div class="row">
                             <div class="col-md-6">
                                 <h6 class="text-muted mb-2">Basic Information</h6>
                                 <p class="mb-1"><strong>ID:</strong> ${activity.id}</p>
                                 <p class="mb-1"><strong>User:</strong> ${activity.user_email || 'System'}</p>
                                 ${activity.user_role ? `<p class="mb-1"><strong>Role:</strong> ${activity.user_role}</p>` : ''}
                                 <p class="mb-1"><strong>Module:</strong> ${moduleIcons[activity.module] || '📋'} ${activity.module}</p>
                                 <p class="mb-0"><strong>Action:</strong> <span class="badge bg-primary">${activity.action}</span></p>
                             </div>
                             <div class="col-md-6">
                                 <h6 class="text-muted mb-2">Metadata</h6>
                                 ${activity.severity ? `<p class="mb-1"><strong>Severity:</strong> <span class="badge bg-${severityColors[activity.severity.toLowerCase()] || 'secondary'}">${severityIcons[activity.severity.toLowerCase()] || '⚪'} ${activity.severity}</span></p>` : ''}
                                 <p class="mb-1"><strong>IP Address:</strong> ${activity.ip_address || 'N/A'}</p>
                                 <p class="mb-1"><strong>User Agent:</strong> <small class="text-muted">${activity.user_agent || 'N/A'}</small></p>
                                 <p class="mb-0"><strong>Created:</strong> ${new Date(activity.created_at).toLocaleString()}</p>
                             </div>
                         </div>
                     </div>
                 </div>
                 
                 <div class="mb-3">
                     <h6 class="text-muted mb-2">Description</h6>
                     <div class="card border-0 bg-light">
                         <div class="card-body">
                             <p class="mb-0">${activity.description}</p>
                         </div>
                     </div>
                 </div>
     `;
     
     // Add additional data if available
     if (activity.additional_data && Object.keys(activity.additional_data).length > 0) {
         detailsHTML += `
                 <div class="mb-3">
                     <h6 class="text-muted mb-2">Additional Data</h6>
                     <div class="card border-0 bg-light">
                         <div class="card-body">
                             <pre class="mb-0 small"><code>${JSON.stringify(activity.additional_data, null, 2)}</code></pre>
                         </div>
                     </div>
                 </div>
         `;
     }
     
     detailsHTML += `
             </div>
         </div>
     `;
     
     return detailsHTML;
 }
 
 // Mark as read functions
 function markSingleAsRead(activityId) {
     if (!confirm('Mark this activity as read?')) {
         return;
     }
     
     fetch('<?= base_url('activity-logs/mark-single-read') ?>', {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json',
             'X-Requested-With': 'XMLHttpRequest'
         },
         body: JSON.stringify({ activity_id: activityId })
     })
     .then(response => response.json())
     .then(data => {
         if (data.success) {
             // Update the UI
             const activityItem = document.querySelector(`[data-id="${activityId}"]`);
             if (activityItem) {
                 activityItem.classList.remove('activity-unread', 'bg-light');
                 activityItem.classList.add('activity-read', 'bg-white');
                 activityItem.setAttribute('data-read-status', '1');
                 activityItem.style.opacity = '0.7';
                 
                 // Remove the mark as read button
                      const markReadBtn = activityItem.querySelector('.mark-read-btn');
                      if (markReadBtn) {
                          markReadBtn.remove();
                      }
             }
             
             updateUnreadCount();
             // Update main navigation badge immediately
             if (typeof updateActivityCount === 'function') {
                 updateActivityCount();
             }
             showToast('Activity marked as read successfully', 'success');
         } else {
             showToast(data.message || 'Failed to mark activity as read', 'error');
         }
     })
     .catch(error => {
         console.error('Error:', error);
         showToast('An error occurred while marking activity as read', 'error');
     });
 }

 function markAllAsRead() {
     if (!confirm('Mark all activities as read?')) {
         return;
     }
     
     fetch('<?= base_url('activity-logs/mark-all-read') ?>', {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json',
             'X-Requested-With': 'XMLHttpRequest'
         }
     })
     .then(response => response.json())
     .then(data => {
         if (data.success) {
             // Refresh the page to show updated status
             location.reload();
         } else {
             showToast(data.message || 'Failed to mark all activities as read', 'error');
         }
     })
     .catch(error => {
         console.error('Error:', error);
         showToast('An error occurred while marking activities as read', 'error');
     });
 }

 // Silent version for auto-marking as read (no confirmation, no toast)
  function markSingleAsReadSilent(activityId) {
     fetch(`<?= base_url('activity-logs/mark-single-as-read/') ?>${activityId}`, {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json',
             'X-Requested-With': 'XMLHttpRequest'
         }
     })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              // Update the UI silently
               const activityItem = document.querySelector(`[data-id="${activityId}"]`);
               if (activityItem) {
                   activityItem.classList.remove('activity-unread', 'bg-light');
                   activityItem.classList.add('activity-read', 'bg-white');
                   activityItem.setAttribute('data-read-status', '1');
                   activityItem.style.opacity = '0.7';
                  
                  // Remove the mark as read button
                   const markReadBtn = activityItem.querySelector('.mark-read-btn');
                   if (markReadBtn) {
                       markReadBtn.remove();
                   }
               }
               updateUnreadCount();
           }
       })
       .catch(error => {
           console.error('Error auto-marking as read:', error);
       });
  }

  function markSelectedAsRead() {
      if (selectedActivities.size === 0) {
          showToast('No activities selected', 'warning');
          return;
      }
      
      if (!confirm(`Mark ${selectedActivities.size} selected activities as read?`)) {
          return;
      }
      
      // Filter out non-database IDs (those with 'idx_' prefix) since they don't exist in database
      const activityIds = Array.from(selectedActivities).filter(id => {
          return !id.toString().startsWith('idx_');
      });
      
      if (activityIds.length === 0) {
          showToast('No valid activities selected for marking as read', 'warning');
          return;
      }
      
      fetch('<?= base_url('activity-logs/mark-all-read') ?>', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ activity_ids: activityIds })
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              // Update UI for all selected activities (including non-database ones)
              const allSelectedIds = Array.from(selectedActivities);
              allSelectedIds.forEach(id => {
                  const activityItem = document.querySelector(`[data-id="${id}"]`);
                  if (activityItem) {
                      activityItem.classList.remove('activity-unread', 'bg-light');
                      activityItem.classList.add('activity-read', 'bg-white');
                      activityItem.setAttribute('data-read-status', '1');
                      activityItem.style.opacity = '0.7';
                      
                      // Remove the mark as read button
                      const markReadBtn = activityItem.querySelector('.mark-read-btn');
                      if (markReadBtn) {
                          markReadBtn.remove();
                      }
                  }
              });
              
              // Clear selection
               selectedActivities.clear();
               document.getElementById('selectAllActivities').checked = false;
               document.querySelectorAll('.activity-checkbox').forEach(cb => cb.checked = false);
               updateBulkActions();
               updateUnreadCount();
               // Update main navigation badge immediately
               if (typeof updateActivityCount === 'function') {
                   updateActivityCount();
               }
               
               const totalSelected = allSelectedIds.length;
               const dbUpdated = activityIds.length;
               if (dbUpdated === totalSelected) {
                   showToast(`${totalSelected} activities marked as read`, 'success');
               } else {
                   showToast(`${dbUpdated} activities marked as read (${totalSelected - dbUpdated} were temporary activities)`, 'success');
               }
          } else {
              showToast(data.message || 'Failed to mark selected activities as read', 'error');
          }
      })
      .catch(error => {
          console.error('Error:', error);
          showToast('An error occurred while marking activities as read', 'error');
      });
  }

  function markAllAsRead() {
      const unreadActivities = document.querySelectorAll('.activity-item[data-read-status="0"]');
      
      if (unreadActivities.length === 0) {
          showToast('No unread activities to mark', 'info');
          return;
      }
      
      if (!confirm(`Mark all ${unreadActivities.length} unread activities as read?`)) {
          return;
      }
      
      const activityIds = Array.from(unreadActivities).map(item => item.getAttribute('data-id'));
      
      fetch('<?= base_url('activity-logs/mark-all-read') ?>', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ activity_ids: activityIds })
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              // Update UI for all unread activities
              unreadActivities.forEach(activityItem => {
                  activityItem.classList.remove('activity-unread', 'bg-light');
                  activityItem.classList.add('activity-read', 'bg-white');
                  activityItem.setAttribute('data-read-status', '1');
                  activityItem.style.opacity = '0.7';
              });
              
              updateUnreadCount();
              // Update main navigation badge immediately
              if (typeof updateActivityCount === 'function') {
                  updateActivityCount();
              }
              showToast(`${activityIds.length} activities marked as read`, 'success');
          } else {
              showToast(data.message || 'Failed to mark all activities as read', 'error');
          }
      })
      .catch(error => {
          console.error('Error:', error);
          showToast('An error occurred while marking activities as read', 'error');
      });
  }

  function updateUnreadCount() {
       const unreadActivities = document.querySelectorAll('.activity-item[data-read-status="0"]');
       const count = unreadActivities.length;
       const unreadCountElement = document.getElementById('unreadCount');
       
       if (count > 0) {
           unreadCountElement.textContent = count;
           unreadCountElement.style.display = 'inline-block';
       } else {
           unreadCountElement.style.display = 'none';
       }
   }

   function showToast(message, type = 'info') {
       // Create toast element
       const toast = document.createElement('div');
       toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
       toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
       toast.innerHTML = `
           ${message}
           <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
       `;
       
       document.body.appendChild(toast);
       
       // Auto remove after 3 seconds
       setTimeout(() => {
           if (toast.parentNode) {
               toast.parentNode.removeChild(toast);
           }
       }, 3000);
   }

   // Initialize unread count on page load
   document.addEventListener('DOMContentLoaded', function() {
       updateUnreadCount();
   });
</script>

<?= $this->endSection() ?>