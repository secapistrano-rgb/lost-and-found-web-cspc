/**
 * Notification System JavaScript
 * Handles notification button functionality, AJAX calls, and real-time updates
 */

class NotificationManager {
    constructor() {
        this.notificationButton = document.querySelector('.notification-btn');
        this.notificationBadge = document.querySelector('.notification-badge');
        this.notificationDropdown = null;
        this.refreshInterval = null;
        this.isDropdownOpen = false;
        
        this.init();
    }

    init() {
        this.createNotificationDropdown();
        this.bindEvents();
        this.loadNotifications();
        this.startAutoRefresh();
    }

    createNotificationDropdown() {
        // Create dropdown container
        const dropdown = document.createElement('div');
        dropdown.className = 'notification-dropdown';
        dropdown.innerHTML = `
            <div class="notification-header">
                <h4>Notifications</h4>
                <button class="mark-all-read-btn" title="Mark all as read">
                    <i class="fas fa-check-double"></i>
                </button>
            </div>
            <div class="notification-list">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Loading notifications...</span>
                </div>
            </div>
            <div class="notification-footer">
                <a href="${this.getBaseUrl()}notifications" class="view-all-btn">View All Notifications</a>
            </div>
        `;

        // Insert dropdown after notification button
        this.notificationButton.parentNode.insertBefore(dropdown, this.notificationButton.nextSibling);
        this.notificationDropdown = dropdown;

        // Add CSS styles
        this.addNotificationStyles();
    }

    addNotificationStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .notification-dropdown {
                position: absolute;
                top: 100%;
                right: 0;
                width: 350px;
                max-height: 400px;
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                display: none;
                overflow: hidden;
            }

            .notification-dropdown.show {
                display: block;
                animation: slideDown 0.2s ease-out;
            }

            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .notification-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px;
                border-bottom: 1px solid #eee;
                background: #f8f9fa;
            }

            .notification-header h4 {
                margin: 0;
                font-size: 16px;
                color: #333;
            }

            .mark-all-read-btn {
                background: none;
                border: none;
                color: #007bff;
                cursor: pointer;
                padding: 5px;
                border-radius: 4px;
                transition: background-color 0.2s;
            }

            .mark-all-read-btn:hover {
                background-color: #e9ecef;
            }

            .notification-list {
                max-height: 300px;
                overflow-y: auto;
            }

            .notification-item {
                padding: 12px 15px;
                border-bottom: 1px solid #f0f0f0;
                cursor: pointer;
                transition: background-color 0.2s;
                position: relative;
            }

            .notification-item:hover {
                background-color: #f8f9fa;
            }

            .notification-item.unread {
                background-color: #f0f8ff;
                border-left: 3px solid #007bff;
            }

            .notification-item.unread::before {
                content: '';
                position: absolute;
                top: 50%;
                right: 15px;
                width: 8px;
                height: 8px;
                background: #007bff;
                border-radius: 50%;
                transform: translateY(-50%);
            }

            .notification-title {
                font-weight: 600;
                color: #333;
                margin-bottom: 4px;
                font-size: 14px;
            }

            .notification-message {
                color: #666;
                font-size: 13px;
                line-height: 1.4;
                margin-bottom: 6px;
            }

            .notification-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 11px;
                color: #999;
            }

            .notification-type {
                padding: 2px 6px;
                border-radius: 10px;
                font-size: 10px;
                font-weight: 500;
                text-transform: uppercase;
            }

            .notification-type.item { background: #e3f2fd; color: #1976d2; }
            .notification-type.claim { background: #f3e5f5; color: #7b1fa2; }
            .notification-type.match { background: #e8f5e8; color: #388e3c; }
            .notification-type.announcement { background: #fff3e0; color: #f57c00; }
            .notification-type.system { background: #e1f5fe; color: #0277bd; }
            .notification-type.feedback { background: #e8f5e8; color: #2e7d32; }
            .notification-type.support { background: #fff8e1; color: #f57c00; }
            .notification-type.user { background: #e3f2fd; color: #1976d2; }
            .notification-type.report { background: #f3e5f5; color: #7b1fa2; }
            .notification-type.welcome { background: #e8eaf6; color: #3f51b5; }

            .notification-footer {
                padding: 10px 15px;
                text-align: center;
                border-top: 1px solid #eee;
                background: #f8f9fa;
            }

            .view-all-btn {
                color: #007bff;
                text-decoration: none;
                font-size: 13px;
                font-weight: 500;
            }

            .view-all-btn:hover {
                text-decoration: underline;
            }

            .loading-spinner {
                text-align: center;
                padding: 30px;
                color: #666;
            }

            .loading-spinner i {
                margin-right: 8px;
            }

            .no-notifications {
                text-align: center;
                padding: 30px;
                color: #999;
            }

            .notification-badge {
                position: absolute;
                top: -5px;
                right: -5px;
                background: #dc3545;
                color: white;
                border-radius: 50%;
                width: 18px;
                height: 18px;
                font-size: 11px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
            }

            .notification-badge.hidden {
                display: none;
            }

            .notification-btn {
                position: relative;
            }
        `;
        document.head.appendChild(style);
    }

    bindEvents() {
        // Toggle dropdown on button click
        this.notificationButton.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggleDropdown();
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.notificationDropdown.contains(e.target) && !this.notificationButton.contains(e.target)) {
                this.closeDropdown();
            }
        });

        // Mark all as read button
        const markAllReadBtn = this.notificationDropdown.querySelector('.mark-all-read-btn');
        markAllReadBtn.addEventListener('click', () => {
            this.markAllAsRead();
        });

        // Handle notification item clicks
        this.notificationDropdown.addEventListener('click', (e) => {
            const notificationItem = e.target.closest('.notification-item');
            if (notificationItem) {
                this.handleNotificationClick(notificationItem);
            }
        });
    }

    toggleDropdown() {
        if (this.isDropdownOpen) {
            this.closeDropdown();
        } else {
            this.openDropdown();
        }
    }

    openDropdown() {
        this.notificationDropdown.classList.add('show');
        this.isDropdownOpen = true;
        this.loadNotifications();
    }

    closeDropdown() {
        this.notificationDropdown.classList.remove('show');
        this.isDropdownOpen = false;
    }

    async loadNotifications() {
        try {
            const response = await fetch(`${this.getBaseUrl()}notifications/get`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Failed to load notifications');
            }

            const data = await response.json();
            this.renderNotifications(data.notifications || []);
            this.updateBadge(data.unread_count || 0);
        } catch (error) {
            console.error('Error loading notifications:', error);
            this.showError('Failed to load notifications');
        }
    }

    renderNotifications(notifications) {
        const notificationList = this.notificationDropdown.querySelector('.notification-list');
        
        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="no-notifications">
                    <i class="fas fa-bell-slash" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                    No notifications yet
                </div>
            `;
            return;
        }

        const notificationsHtml = notifications.map(notification => {
            const timeAgo = this.getTimeAgo(notification.created_at);
            const isUnread = notification.is_read === '0' || notification.is_read === 0;
            
            return `
                <div class="notification-item ${isUnread ? 'unread' : ''}" 
                     data-id="${notification.id}" 
                     data-url="${notification.action_url || '#'}">
                    <div class="notification-title">${this.escapeHtml(notification.title)}</div>
                    <div class="notification-message">${this.formatNotificationMessage(notification.message)}</div>
                    <div class="notification-meta">
                        <span class="notification-type ${notification.type}">${notification.type}</span>
                        <span class="notification-time">${timeAgo}</span>
                    </div>
                </div>
            `;
        }).join('');

        notificationList.innerHTML = notificationsHtml;
    }

    async handleNotificationClick(notificationItem) {
        const notificationId = notificationItem.dataset.id;
        const actionUrl = notificationItem.dataset.url;

        // Mark as read if unread
        if (notificationItem.classList.contains('unread')) {
            await this.markAsRead(notificationId);
            notificationItem.classList.remove('unread');
            this.updateBadgeCount(-1);
        }

        // Navigate to action URL if available
        if (actionUrl && actionUrl !== '#') {
            window.location.href = actionUrl;
        }

        this.closeDropdown();
    }

    async markAsRead(notificationId) {
        try {
            const response = await fetch(`${this.getBaseUrl()}notifications/mark-read/${notificationId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Failed to mark notification as read');
            }

            return await response.json();
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            const response = await fetch(`${this.getBaseUrl()}notifications/mark-all-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Failed to mark all notifications as read');
            }

            // Update UI
            const unreadItems = this.notificationDropdown.querySelectorAll('.notification-item.unread');
            unreadItems.forEach(item => item.classList.remove('unread'));
            this.updateBadge(0);

            return await response.json();
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }

    updateBadge(count) {
        if (count > 0) {
            this.notificationBadge.textContent = count > 99 ? '99+' : count;
            this.notificationBadge.classList.remove('hidden');
        } else {
            this.notificationBadge.classList.add('hidden');
        }
    }

    updateBadgeCount(delta) {
        const currentCount = parseInt(this.notificationBadge.textContent) || 0;
        const newCount = Math.max(0, currentCount + delta);
        this.updateBadge(newCount);
    }

    startAutoRefresh() {
        // Refresh notifications every 30 seconds
        this.refreshInterval = setInterval(() => {
            if (!this.isDropdownOpen) {
                this.loadUnreadCount();
            }
        }, 30000);
    }

    async loadUnreadCount() {
        try {
            const response = await fetch(`${this.getBaseUrl()}notifications/getUnreadCount`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                const data = await response.json();
                this.updateBadge(data.count || 0);
            }
        } catch (error) {
            console.error('Error loading unread count:', error);
        }
    }

    showError(message) {
        const notificationList = this.notificationDropdown.querySelector('.notification-list');
        notificationList.innerHTML = `
            <div class="no-notifications" style="color: #dc3545;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                ${message}
            </div>
        `;
    }

    getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
        if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`;
        
        return date.toLocaleDateString();
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    formatNotificationMessage(message) {
        // First escape HTML to prevent XSS
        const escapedMessage = this.escapeHtml(message);
        // Then replace escaped newlines with <br> tags
        return escapedMessage.replace(/\n/g, '<br>');
    }

    getBaseUrl() {
        // Get base URL from the current page
        const baseElement = document.querySelector('base');
        if (baseElement) {
            return baseElement.href;
        }
        
        // For CodeIgniter 4 applications, the base URL should be the origin + '/'
        // This ensures proper routing regardless of the current page
        return window.location.origin + '/';
    }

    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        
        if (this.notificationDropdown) {
            this.notificationDropdown.remove();
        }
    }
}

// Initialize notification manager when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if notification button exists
    const notificationBtn = document.querySelector('.notification-btn');
    if (notificationBtn) {
        window.notificationManager = new NotificationManager();
    }
});

// Global function to create new notifications (can be called from other scripts)
window.createNotification = function(title, message, type = 'info') {
    if (window.notificationManager) {
        // Refresh notifications to show new ones
        window.notificationManager.loadNotifications();
    }
};