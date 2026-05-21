<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-bell me-2"></i>Notifications
                            <?php if ($unread_count > 0): ?>
                                <span class="badge bg-danger ms-2"><?= $unread_count ?></span>
                            <?php endif; ?>
                        </h4>
                        <div class="btn-group">
                            <?php if ($unread_count > 0): ?>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="markAllReadBtn">
                                    <i class="fas fa-check-double me-1"></i>Mark All Read
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="refreshBtn">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                    
                    <!-- Admin Notification Filters -->
                    <?php if (session()->get('role') === 'admin'): ?>
                        <div class="notification-filters">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="notificationTypeFilter">
                                        <option value="">All Notifications</option>
                                        <option value="admin">Admin Only</option>
                                        <option value="user">User Only</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="moduleFilter">
                                        <option value="">All Modules</option>
                                        <option value="items">Items</option>
                                        <option value="claims">Claims</option>
                                        <option value="matches">Matches</option>
                                        <option value="feedback">Feedback</option>
                                        <option value="support">Support</option>
                                        <option value="users">Users</option>
                                        <option value="reports">Reports</option>
                                        <option value="system">System</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select form-select-sm" id="priorityFilter">
                                        <option value="">All Priorities</option>
                                        <option value="urgent">Urgent</option>
                                        <option value="high">High</option>
                                        <option value="medium">Medium</option>
                                        <option value="low">Low</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary btn-sm w-100" id="applyFiltersBtn">
                                        <i class="fas fa-filter me-1"></i>Apply Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (empty($notifications)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications yet</h5>
                            <p class="text-muted">You'll see notifications here when there are updates about your items, matches, or system announcements.</p>
                        </div>
                    <?php else: ?>
                        <div class="notification-list">
                            <?php foreach ($notifications as $notification): ?>
                                <div class="notification-page-item <?= $notification['is_read'] ? 'read' : 'unread' ?>" data-id="<?= $notification['id'] ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon me-3">
                                            <i class="<?= getNotificationIcon($notification['type']) ?> text-<?= getNotificationColor($notification['type']) ?>"></i>
                                        </div>
                                        <div class="notification-content flex-grow-1">
                                            <h6 class="notification-title mb-2"><?= esc($notification['title']) ?></h6>
                                            <p class="notification-message mb-2"><?= nl2br(esc($notification['message'])) ?></p>
                                            <div class="notification-meta">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?= timeAgo($notification['created_at']) ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="notification-actions">
                                            <?php if (!$notification['is_read']): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary mark-read-btn" data-id="<?= $notification['id'] ?>">
                                                    <i class="fas fa-check"></i> Mark Read
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $notification['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Pagination Controls -->
                    <?php if (!empty($notifications) && isset($pagination) && $pagination['total_pages'] > 1): ?>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="pagination-info">
                                <small class="text-muted">
                                    Showing <?= count($notifications) ?> notifications
                                </small>
                            </div>
                            <div class="pagination-controls">
                                <!-- Simple Pagination -->
                                <nav class="mt-4">
                                    <ul class="pagination justify-content-center">
                                        <?php if ($pagination['has_previous']): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= base_url('notifications?' . http_build_query(array_merge($filters ?? [], ['page' => $pagination['previous_page']]))) ?>">
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
                                                <a class="page-link" href="<?= base_url('notifications?' . http_build_query(array_merge($filters ?? [], ['page' => $pagination['next_page']]))) ?>">
                                                    Next
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-page-item {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.notification-page-item,
.notification-item {
    background-color: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
}

.notification-page-item:last-child {
    border-bottom: none;
}

.notification-page-item.unread,
.notification-item.unread {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
    box-shadow: 0 1px 3px rgba(0, 123, 255, 0.1);
}

.notification-page-item.read,
.notification-item.read {
    background-color: #ffffff !important;
    border-left: 4px solid #e9ecef !important;
    opacity: 0.7;
    color: #6c757d;
}

.notification-page-item:hover,
.notification-item:hover {
    background-color: #f1f3f4;
}

.notification-page-item.read:hover,
.notification-item.read:hover {
    background-color: #f8f9fa !important;
}

.notification-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-radius: 50%;
}

.notification-title {
    font-weight: 600;
    color: #333;
    transition: color 0.3s ease, font-weight 0.3s ease;
}

.notification-message {
    color: #666;
    line-height: 1.4;
    transition: color 0.3s ease;
}

.notification-page-item.read .notification-title,
.notification-item.read .notification-title {
    color: #9ca3af !important;
    font-weight: 500;
}

.notification-page-item.read .notification-message,
.notification-item.read .notification-message {
    color: #9ca3af !important;
}

.notification-actions {
    display: flex;
    flex-direction: row;
    gap: 0.5rem;
    align-items: center;
    flex-shrink: 0;
    width: 180px;
    justify-content: flex-end;
    margin-left: auto;
    margin-right: 0.75rem;
    position: absolute;
    right: 0;
}

.notification-actions .btn {
    position: relative;
    z-index: 10;
    pointer-events: auto;
    cursor: pointer;
    border: 1px solid;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.2;
}

.notification-actions .btn:hover {
    transform: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.notification-actions .mark-read-btn {
    background-color: #fff;
    border: 2px solid #007bff;
    color: #007bff;
    border-radius: 4px;
    padding: 0.375rem 0.75rem;
    font-weight: 500;
}

.notification-actions .mark-read-btn:hover {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}

.notification-actions .delete-btn {
    background-color: #fff;
    border: 2px solid #dc3545;
    color: #dc3545;
    border-radius: 8px;
    padding: 0.375rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-actions .delete-btn:hover {
    background-color: #dc3545;
    color: #fff;
    border-color: #dc3545;
}

.notification-meta {
    margin-top: 0.5rem;
}

.notification-page-item.processing,
.notification-item.processing {
    opacity: 0.7;
}

.notification-page-item.processing .mark-read-btn,
.notification-item.processing .mark-read-btn {
    pointer-events: none;
}

/* Pagination Styling - Same as Activity Logs */
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up event listeners...');
    
    // Use event delegation for dynamically loaded content
    // Debug: Check what buttons exist on page load
    console.log('=== PAGE LOADED - CHECKING BUTTONS ===');
    const allMarkReadBtns = document.querySelectorAll('.mark-read-btn');
    const allDeleteBtns = document.querySelectorAll('.delete-btn');
    console.log('Mark Read buttons found:', allMarkReadBtns.length);
    console.log('Delete buttons found:', allDeleteBtns.length);
    allMarkReadBtns.forEach((btn, index) => {
        console.log(`Mark Read Button ${index}:`, btn, 'data-id:', btn.dataset.id);
        console.log(`Button ${index} computed style:`, window.getComputedStyle(btn));
        console.log(`Button ${index} pointer-events:`, window.getComputedStyle(btn).pointerEvents);
        console.log(`Button ${index} z-index:`, window.getComputedStyle(btn).zIndex);
    });
    
    // Add a test function to manually trigger button clicks
    window.testMarkReadButton = function(index = 0) {
        const buttons = document.querySelectorAll('.mark-read-btn');
        if (buttons[index]) {
            console.log('Testing button click for button:', index);
            buttons[index].click();
        } else {
            console.log('No button found at index:', index);
        }
    };
    
    console.log('Test function added. You can call testMarkReadButton(0) in console to test the first button.');
    
    // Add a test function to manually apply read class
    window.testReadClass = function(index = 0) {
        const items = document.querySelectorAll('.notification-page-item');
        if (items[index]) {
            console.log('Testing read class on item:', index);
            console.log('Before - classes:', items[index].className);
            console.log('Before - background:', window.getComputedStyle(items[index]).backgroundColor);
            
            items[index].classList.remove('unread');
            items[index].classList.add('read');
            
            console.log('After - classes:', items[index].className);
            console.log('After - background:', window.getComputedStyle(items[index]).backgroundColor);
            console.log('After - border:', window.getComputedStyle(items[index]).borderLeft);
        } else {
            console.log('No notification item found at index:', index);
        }
    };
    
    console.log('CSS test function added. You can call testReadClass(0) in console to test CSS styling.');

    // Add global click debugging
    document.addEventListener('click', function(e) {
        console.log('=== GLOBAL CLICK EVENT DETECTED ===');
        console.log('Click detected on:', e.target);
        console.log('Target classes:', e.target.className);
        console.log('Target tagName:', e.target.tagName);
        console.log('Target innerHTML:', e.target.innerHTML);
        console.log('Event timestamp:', Date.now());
        console.log('Mouse position:', e.clientX, e.clientY);
        
        // Check if click is anywhere near a mark-read-btn
        const allMarkReadBtns = document.querySelectorAll('.mark-read-btn');
        console.log('Total mark-read buttons on page:', allMarkReadBtns.length);
        
        // Check if the clicked element or any parent has mark-read-btn class
        let element = e.target;
        let depth = 0;
        while (element && depth < 10) {
            console.log(`Element at depth ${depth}:`, element.tagName, element.className);
            if (element.classList && element.classList.contains('mark-read-btn')) {
                console.log('*** FOUND MARK-READ-BTN IN PARENT CHAIN ***');
                break;
            }
            element = element.parentElement;
            depth++;
        }
        
        // Check if click is in notification area
        const notificationItem = e.target.closest('.notification-page-item');
        if (notificationItem) {
            console.log('=== CLICK IN NOTIFICATION AREA ===');
            console.log('Notification item:', notificationItem);
            console.log('Notification data-id:', notificationItem.dataset.id);
        }

        // Handle mark as read button clicks
        const markReadBtn = e.target.closest('.mark-read-btn');
        if (markReadBtn) {
            console.log('=== MARK AS READ BUTTON CLICKED ===');
            console.log('Target element:', e.target);
            console.log('Closest mark-read-btn:', markReadBtn);
            console.log('Button classes:', markReadBtn.className);
            console.log('Button disabled state:', markReadBtn.disabled);
            
            // Only prevent default behavior to stop page refresh
            e.preventDefault();
            
            const notificationId = markReadBtn.dataset.id;
            console.log('Mark as read button clicked for notification:', notificationId);
            console.log('Button element:', markReadBtn);
            console.log('Button dataset:', markReadBtn.dataset);
            console.log('Notification ID type:', typeof notificationId);
            console.log('Notification ID value:', notificationId);
            console.log('About to call markAsRead function...');
            
            // IMMEDIATE VISUAL FEEDBACK - Apply changes instantly
            const notificationItem = markReadBtn.closest('.notification-page-item') || markReadBtn.closest('[data-id]');
            if (notificationItem) {
                console.log('Applying immediate visual feedback...');
                
                // Remove unread class and add read class immediately
                notificationItem.classList.remove('unread');
                notificationItem.classList.add('read');
                
                // Apply visual styles immediately
                notificationItem.style.backgroundColor = '#ffffff';
                notificationItem.style.borderLeft = '4px solid #e9ecef';
                notificationItem.style.opacity = '0.7';
                notificationItem.style.color = '#6c757d';
                notificationItem.style.transition = 'all 0.3s ease';
                
                // Update child elements immediately
                const title = notificationItem.querySelector('.notification-title');
                const message = notificationItem.querySelector('.notification-message');
                if (title) {
                    title.style.color = '#9ca3af';
                    title.style.fontWeight = '500';
                }
                if (message) {
                    message.style.color = '#9ca3af';
                }
                
                // Hide the mark read button immediately
                markReadBtn.style.display = 'none';
                
                console.log('Immediate visual feedback applied!');
            }
            
            // Call the function - this will handle the AJAX request
            try {
                markAsRead(notificationId, markReadBtn);
                console.log('markAsRead function called successfully');
            } catch (error) {
                console.error('Error calling markAsRead:', error);
                
                // Revert visual changes if there's an error
                if (notificationItem) {
                    notificationItem.classList.remove('read');
                    notificationItem.classList.add('unread');
                    notificationItem.style.backgroundColor = '';
                    notificationItem.style.borderLeft = '';
                    notificationItem.style.opacity = '';
                    notificationItem.style.color = '';
                    markReadBtn.style.display = '';
                }
            }
            return;
        }
        
        // Handle delete button clicks
        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            e.preventDefault();
            e.stopPropagation();
            const notificationId = deleteBtn.dataset.id;
            console.log('Delete button clicked for notification:', notificationId);
            console.log('Showing confirmation dialog...');
            const userConfirmed = confirm('Are you sure you want to delete this notification?');
            console.log('User confirmation result:', userConfirmed);
            if (userConfirmed) {
                console.log('User confirmed, proceeding with deletion');
                deleteNotification(notificationId);
            } else {
                console.log('User cancelled deletion');
            }
            return;
        }
    });

    // Mark all notifications as read
    const markAllReadBtn = document.getElementById('markAllReadBtn');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAllAsRead();
        });
    }

    // Refresh notifications
    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function(e) {
            e.preventDefault();
            location.reload();
        });
    }

    // Admin filter functionality
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            applyNotificationFilters();
        });
    }

    function markAsRead(notificationId, buttonElement) {
        console.log('markAsRead called with ID:', notificationId);
        console.log('markAsRead called with button element:', buttonElement);
        
        if (!notificationId) {
            console.error('No notification ID provided');
            return;
        }
        
        // Find the notification item
        const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
        if (!notificationItem) {
            console.error('Notification item not found for ID:', notificationId);
            return;
        }
        
        // Prevent duplicate requests
        if (notificationItem.classList.contains('processing')) {
            console.log('Already processing this notification');
            return;
        }
        
        // Update UI immediately for instant feedback
        notificationItem.classList.add('processing');
        
        // Use the passed button element instead of searching for it
        const markReadBtn = buttonElement || notificationItem.querySelector('.mark-read-btn');
        
        console.log('Found mark read button:', markReadBtn);
        console.log('Button current innerHTML:', markReadBtn ? markReadBtn.innerHTML : 'No button found');
        
        if (markReadBtn) {
            markReadBtn.disabled = true;
            markReadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            console.log('Button innerHTML after update:', markReadBtn.innerHTML);
            
            // Force a style update to ensure the spinner shows
            markReadBtn.style.opacity = '0.7';
            markReadBtn.style.cursor = 'not-allowed';
        } else {
            console.error('Mark read button not found in notification item');
        }
        
        // Make the AJAX request
        const url = `<?= site_url('notifications/mark-read') ?>/${notificationId}`;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('Response status:', response.status, 'OK:', response.ok);
            console.log('Response headers:', response.headers);
            
            // First get the response as text to see what we're actually getting
            return response.text().then(text => {
                console.log('Raw response text:', text);
                
                // Try to parse as JSON
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed JSON data:', data);
                    return { success: response.ok, data: data, rawText: text };
                } catch (e) {
                    console.log('JSON parse error:', e);
                    // If it's not JSON, treat it as success if status is OK
                    return { success: response.ok, data: { success: response.ok }, rawText: text };
                }
            });
        })
        .then(result => {
            console.log('Final result:', result);
            
            if (result.success && result.data.success) {
                console.log('Success! Updating UI...');
                console.log('Classes before update:', notificationItem.className);
                console.log('Computed styles before update:', window.getComputedStyle(notificationItem));
                
                // Remove unread styling and add read styling
                notificationItem.classList.remove('unread', 'processing');
                notificationItem.classList.add('read');
                
                console.log('Classes after update:', notificationItem.className);
                console.log('Has read class:', notificationItem.classList.contains('read'));
                console.log('Has unread class:', notificationItem.classList.contains('unread'));
                
                // Force immediate visual update with multiple techniques
                notificationItem.style.display = 'none';
                notificationItem.offsetHeight; // Force reflow
                notificationItem.style.display = '';
                
                // Force style recalculation
                window.getComputedStyle(notificationItem).getPropertyValue('background-color');
                
                // Apply styles directly as backup
                notificationItem.style.backgroundColor = '#ffffff';
                notificationItem.style.borderLeft = '4px solid #e9ecef';
                notificationItem.style.opacity = '0.7';
                notificationItem.style.color = '#6c757d';
                
                // Also update child elements
                const title = notificationItem.querySelector('.notification-title');
                const message = notificationItem.querySelector('.notification-message');
                if (title) {
                    title.style.color = '#9ca3af';
                    title.style.fontWeight = '500';
                }
                if (message) {
                    message.style.color = '#9ca3af';
                }
                
                console.log('Computed styles after update:', window.getComputedStyle(notificationItem));
                console.log('Background color after update:', window.getComputedStyle(notificationItem).backgroundColor);
                console.log('Border left after update:', window.getComputedStyle(notificationItem).borderLeft);
                
                // Hide the mark read button
                if (markReadBtn) {
                    markReadBtn.style.display = 'none';
                }
                
                // Update notification badge count
                if (result.data.unread_count !== undefined) {
                    updateBadgeCount(result.data.unread_count);
                } else {
                    // Fallback: decrease count by 1
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        const currentCount = parseInt(badge.textContent) || 0;
                        updateBadgeCount(Math.max(0, currentCount - 1));
                    }
                }
                
                console.log('Notification marked as read successfully');
            } else {
                throw new Error(result.data.message || result.data.error || 'Failed to mark notification as read');
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
            
            // Revert UI changes on error
            notificationItem.classList.remove('processing');
            if (markReadBtn) {
                markReadBtn.disabled = false;
                markReadBtn.innerHTML = '<i class="fas fa-check"></i> Mark Read';
            }
            
            alert('Failed to mark notification as read. Please try again.');
        });
    }

    function markAllAsRead() {
        console.log('markAllAsRead function called');
        
        const unreadItems = document.querySelectorAll('.notification-page-item.unread');
        console.log('Found unread items:', unreadItems.length);
        
        if (unreadItems.length === 0) {
            console.log('No unread notifications to mark');
            return;
        }
        
        // Check if already processing
        if (document.body.classList.contains('marking-all-read')) {
            console.log('Already processing mark all as read');
            return;
        }
        
        // Mark as processing
        document.body.classList.add('marking-all-read');
        const markAllBtn = document.getElementById('markAllReadBtn');
        if (markAllBtn) {
            markAllBtn.disabled = true;
            markAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        }
        
        // Send request to server first, then update UI
        fetch('<?= site_url('notifications/mark-all-read') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Mark all as read response:', data);
            if (data.success) {
                // Update UI only after successful server response
                unreadItems.forEach(item => {
                    item.classList.remove('unread');
                    item.classList.add('read');
                    const markReadBtn = item.querySelector('.mark-read-btn');
                    if (markReadBtn) {
                        markReadBtn.style.display = 'none';
                    }
                });
                
                // Update badge to 0
                updateBadgeCount(0);
                
                // Hide mark all read button if no more unread notifications
                if (markAllBtn) {
                    markAllBtn.style.display = 'none';
                }
            } else {
                throw new Error(data.message || 'Failed to mark all notifications as read');
            }
        })
        .catch(error => {
            console.error('Error marking all notifications as read:', error);
            alert('Failed to mark all notifications as read. Please try again.');
        })
        .finally(() => {
            // Remove processing state
            document.body.classList.remove('marking-all-read');
            if (markAllBtn) {
                markAllBtn.disabled = false;
                markAllBtn.innerHTML = '<i class="fas fa-check-double"></i> Mark All Read';
            }
        });
    }

    function deleteNotification(notificationId) {
        console.log('=== DELETE NOTIFICATION DEBUG START ===');
        console.log('Notification ID:', notificationId);
        
        const deleteUrl = `<?= site_url('notifications/delete') ?>/${notificationId}`;
        console.log('Delete URL:', deleteUrl);
        
        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('=== FETCH RESPONSE ===');
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            console.log('Response headers:', response.headers);
            console.log('Response URL:', response.url);
            
            // Clone response to read it twice
            const responseClone = response.clone();
            
            if (!response.ok) {
                // Try to get error details from response
                return responseClone.text().then(text => {
                    console.error('Error response text:', text);
                    throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
                });
            }
            
            // Try to parse as JSON
            return response.json().catch(error => {
                console.error('Failed to parse JSON response');
                return responseClone.text().then(text => {
                    console.error('Raw response text:', text);
                    throw new Error(`Invalid JSON response: ${text}`);
                });
            });
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                console.log('Delete successful, removing notification from DOM');
                
                // Find the notification container (not the button)
                const notificationItem = document.querySelector(`.notification-page-item[data-id="${notificationId}"]`);
                console.log('Looking for notification with ID:', notificationId);
                console.log('Found notification item:', notificationItem);
                
                if (notificationItem) {
                    console.log('Removing notification from DOM...');
                    notificationItem.style.transition = 'opacity 0.3s ease';
                    notificationItem.style.opacity = '0';
                    
                    setTimeout(() => {
                        notificationItem.remove();
                        console.log('Notification removed from DOM');
                        
                        // Check if no notifications left
                        const remainingNotifications = document.querySelectorAll('.notification-page-item');
                        console.log('Remaining notifications:', remainingNotifications.length);
                        
                        if (remainingNotifications.length === 0) {
                            console.log('No notifications left, showing empty state');
                            location.reload();
                        }
                    }, 300);
                } else {
                    console.error('Could not find notification item with ID:', notificationId);
                    console.log('Available notifications:');
                    document.querySelectorAll('.notification-page-item').forEach(el => {
                        console.log('- ID:', el.getAttribute('data-id'), 'Element:', el);
                    });
                }
                
                // Update badge count
                updateBadgeCount(data.unread_count);
                console.log('Badge count updated to:', data.unread_count);
            } else {
                console.log('Delete failed:', data.error || data.message);
                alert('Failed to delete notification: ' + (data.error || data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('=== DELETE ERROR ===');
            console.error('Error details:', error);
            console.error('Error message:', error.message);
            console.error('Error stack:', error.stack);
            alert('An error occurred: ' + error.message);
        });
        
        console.log('=== DELETE NOTIFICATION DEBUG END ===');
    }

    function updateBadgeCount(count) {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }

    function applyNotificationFilters() {
        const typeFilter = document.getElementById('notificationTypeFilter').value;
        const moduleFilter = document.getElementById('moduleFilter').value;
        const priorityFilter = document.getElementById('priorityFilter').value;
        
        // Build query parameters
        const params = new URLSearchParams();
        if (typeFilter === 'admin') {
            params.append('admin_only', 'true');
        }
        if (moduleFilter) {
            params.append('module', moduleFilter);
        }
        if (priorityFilter) {
            params.append('priority', priorityFilter);
        }
        
        // Show loading state
        const notificationList = document.querySelector('.notification-list');
        if (notificationList) {
            notificationList.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i><p class="text-muted mt-2">Loading filtered notifications...</p></div>';
        }
        
        // Fetch filtered notifications
        fetch(`<?= site_url('notifications/get') ?>?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderNotifications(data.notifications);
            } else {
                alert('Failed to load filtered notifications');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while filtering notifications');
        });
    }

    function renderNotifications(notifications) {
        const notificationList = document.querySelector('.notification-list');
        const cardBody = document.querySelector('.card-body');
        
        if (notifications.length === 0) {
            cardBody.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No notifications found</h5>
                    <p class="text-muted">No notifications match your current filters.</p>
                </div>
            `;
            return;
        }
        
        let html = '<div class="notification-list">';
        notifications.forEach(notification => {
            html += `
                <div class="notification-page-item ${notification.is_read ? 'read' : 'unread'}" data-id="${notification.id}">
                    <div class="d-flex align-items-center">
                        <div class="notification-icon me-3">
                            <i class="${notification.icon} text-${notification.color}"></i>
                        </div>
                        <div class="notification-content flex-grow-1">
                            <h6 class="notification-title mb-2">${escapeHtml(notification.title)}</h6>
                            <p class="notification-message mb-2">${escapeHtml(notification.message).replace(/\n/g, '<br>')}</p>
                            <div class="notification-meta">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    ${notification.time_ago}
                                    <span class="badge bg-${notification.color} ms-2">${notification.priority}</span>
                                </small>
                            </div>
                        </div>
                        <div class="notification-actions">
                            ${!notification.is_read ? `<button type="button" class="btn btn-sm btn-outline-primary mark-read-btn" data-id="${notification.id}"><i class="fas fa-check"></i> Mark Read</button>` : ''}
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="${notification.id}"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        
        cardBody.innerHTML = html;
        
        // Re-bind event listeners
        bindNotificationEvents();
    }

    function bindNotificationEvents() {
        console.log('Event delegation is active - no rebinding needed');
        // This function is kept for compatibility but does nothing
        // since we're using event delegation which automatically handles
        // dynamically added elements
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Event delegation handles all button clicks properly
    // No need for additional event listeners that might interfere
});
</script>
<?= $this->endSection() ?>

<?php
// Helper functions for the view
function getNotificationIcon($type) {
    $icons = [
        'welcome' => 'fas fa-hand-wave',
        'item' => 'fas fa-box',
        'claim' => 'fas fa-hand-paper',
        'match' => 'fas fa-link',
        'system' => 'fas fa-cog',
        'announcement' => 'fas fa-bullhorn',
        'feedback' => 'fas fa-comment',
        'support' => 'fas fa-life-ring',
        'user' => 'fas fa-user',
        'report' => 'fas fa-chart-bar',
        'claim_approved' => 'fas fa-check-circle',
        'item_reported' => 'fas fa-box',
        'email_reply' => 'fas fa-reply'
    ];
    return $icons[$type] ?? 'fas fa-bell';
}

function getNotificationColor($type) {
    $colors = [
        'welcome' => 'primary',
        'item' => 'primary',
        'claim' => 'warning',
        'match' => 'success',
        'system' => 'info',
        'announcement' => 'danger',
        'feedback' => 'info',
        'support' => 'warning',
        'user' => 'primary',
        'report' => 'secondary',
        'claim_approved' => 'success',
        'item_reported' => 'warning',
        'email_reply' => 'info'
    ];
    return $colors[$type] ?? 'secondary';
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);

    if ($time < 60) {
        return 'just now';
    } elseif ($time < 3600) {
        $minutes = floor($time / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($time < 86400) {
        $hours = floor($time / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($time < 2592000) {
        $days = floor($time / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } elseif ($time < 31536000) {
        $months = floor($time / 2592000);
        return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
    } else {
        $years = floor($time / 31536000);
        return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
    }
}
?>