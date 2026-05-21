<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Login Routes
$routes->get('login', 'Login::index');
$routes->post('login/authenticate', 'Login::authenticate');
$routes->get('logout', 'Login::logout');
$routes->get('check-session', 'Login::checkSession'); // Session validation endpoint
$routes->get('loading', 'Loading::index');
$routes->get('profile-fix-instructions', function() {
    return view('profile_fix_instructions');
});

// Email Test Routes (for debugging)
$routes->get('emailtest', 'EmailTest::index');
$routes->get('emailtest/testEmail', 'EmailTest::testEmail');
$routes->get('emailtest/testNotificationService', 'EmailTest::testNotificationService');

$routes->get('/', 'Login::index');                   // Default route to login
$routes->get('dashboard', 'Dashboard::index');    // Dashboard route
$routes->get('dashboard/items', 'ItemController::index'); // Item management route

// Item Management Routes
$routes->get('items', 'ItemController::index');              // List all items
$routes->get('items/create', 'ItemController::create');      // Show form to create new item
$routes->post('items/store', 'ItemController::store');       // Handle new item submission
// Item Management Routes
$routes->get('items/edit/(:any)', 'ItemController::edit/$1');    // Show form to edit item by ID
$routes->post('items/update/(:any)', 'ItemController::update/$1'); // Handle item update submission
// Remove this duplicate line: $routes->get('items/update/(:any)', 'ItemController::update/$1');
$routes->match(['GET', 'POST', 'DELETE'], 'items/delete/(:any)', 'ItemController::delete/$1'); // Delete item by ID
$routes->get('items/view/(:any)', 'ItemController::view/$1');          // View single item details by ID

// Claims Management Routes
$routes->get('claims', 'ClaimController::index', ['as' => 'claims.index']);              // List all claims
$routes->get('claims/create', 'ClaimController::create', ['as' => 'claims.create']);      // Show form to create new claim
$routes->match(['GET', 'POST'], 'claims/store', 'ClaimController::store', ['as' => 'claims.store']);       // Handle new claim submission
$routes->get('claims/edit/(:any)', 'ClaimController::edit/$1', ['as' => 'claims.edit']);    // Show form to edit claim by ID
$routes->post('claims/update/(:any)', 'ClaimController::update/$1', ['as' => 'claims.update']); // Handle claim update submission
$routes->match(['GET', 'POST', 'DELETE'], 'claims/delete/(:any)', 'ClaimController::delete/$1', ['as' => 'claims.delete']); // Delete claim by ID
$routes->get('claims/view/(:any)', 'ClaimController::view/$1', ['as' => 'claims.view']);          // View single claim details by ID

// Match Management Routes
$routes->get('match', 'MatchController::index', ['as' => 'match.index']);                    // List all matches
$routes->get('matches', 'MatchController::index', ['as' => 'matches.index']);                 // List all matches (plural)
$routes->get('match/view/(:any)', 'MatchController::view/$1', ['as' => 'match.view']);        // View single match details by ID
$routes->get('matches/view/(:any)', 'MatchController::view/$1', ['as' => 'matches.view']);     // View single match details by ID (plural)
$routes->get('match/edit/(:any)', 'MatchController::edit/$1', ['as' => 'match.edit']);        // Show form to edit match by ID
$routes->get('matches/edit/(:any)', 'MatchController::edit/$1', ['as' => 'matches.edit']);     // Show form to edit match by ID (plural)
$routes->post('match/update/(:any)', 'MatchController::update/$1', ['as' => 'match.update']); // Handle match update submission
$routes->post('matches/update/(:any)', 'MatchController::update/$1', ['as' => 'matches.update']); // Handle match update submission (plural)
$routes->match(['GET', 'POST', 'DELETE'], 'match/delete/(:any)', 'MatchController::delete/$1', ['as' => 'match.delete']);  // Delete match by ID
$routes->match(['GET', 'POST', 'DELETE'], 'matches/delete/(:any)', 'MatchController::delete/$1', ['as' => 'matches.delete']); // Delete match by ID (plural)
$routes->post('match/approve/(:any)', 'MatchController::approve/$1', ['as' => 'match.approve']); // Approve match
$routes->post('matches/approve/(:any)', 'MatchController::approve/$1', ['as' => 'matches.approve']); // Approve match (plural)
$routes->post('match/reject/(:any)', 'MatchController::reject/$1', ['as' => 'match.reject']);   // Reject match
$routes->post('matches/reject/(:any)', 'MatchController::reject/$1', ['as' => 'matches.reject']); // Reject match (plural)
$routes->post('match/resolve/(:any)', 'MatchController::resolve/$1', ['as' => 'match.resolve']); // Resolve match
$routes->post('matches/resolve/(:any)', 'MatchController::resolve/$1', ['as' => 'matches.resolve']); // Resolve match (plural)
$routes->post('match/bulk-action', 'MatchController::bulkAction', ['as' => 'match.bulk_action']); // Bulk actions
$routes->post('matches/bulk-action', 'MatchController::bulkAction', ['as' => 'matches.bulk_action']); // Bulk actions (plural)
$routes->get('match/export', 'MatchController::export', ['as' => 'match.export']);             // Export matches
$routes->get('matches/export', 'MatchController::export', ['as' => 'matches.export']);         // Export matches (plural)

// Email Management Routes
$routes->get('emails', 'EmailController::index', ['as' => 'emails.index']);                    // Email dashboard
$routes->get('emails/compose', 'EmailController::compose', ['as' => 'emails.compose']);         // Compose new email
$routes->post('emails/send', 'EmailController::send', ['as' => 'emails.send']);                 // Send email
$routes->get('emails/templates', 'EmailController::templates', ['as' => 'emails.templates']);   // Email templates
$routes->get('emails/templates/create', 'EmailController::createTemplate', ['as' => 'emails.templates.create']); // Create template
$routes->post('emails/templates/store', 'EmailController::storeTemplate', ['as' => 'emails.templates.store']); // Store template
$routes->get('emails/templates/edit/(:num)', 'EmailController::editTemplate/$1', ['as' => 'emails.templates.edit']); // Edit template
$routes->match(['POST', 'PUT'], 'emails/templates/update/(:num)', 'EmailController::updateTemplate/$1', ['as' => 'emails.templates.update']); // Update template
$routes->get('emails/templates/update/(:num)', 'EmailController::editTemplate/$1'); // Redirect GET requests to edit page
$routes->delete('emails/templates/delete/(:num)', 'EmailController::deleteTemplate/$1', ['as' => 'emails.templates.delete']); // Delete template
$routes->get('emails/logs', 'EmailController::logs', ['as' => 'emails.logs']);                  // Email logs
$routes->get('emails/view/(:num)', 'EmailController::view/$1', ['as' => 'emails.view']);              // View email details
$routes->get('emails/details/(:num)', 'EmailController::getLogDetails/$1');
$routes->get('emails/get-log-details/(:num)', 'EmailController::getLogDetails/$1');
$routes->post('emails/resend/(:num)', 'EmailController::resendEmail/$1', ['as' => 'emails.resend']); // Resend failed email
$routes->delete('emails/delete/(:num)', 'EmailController::delete/$1', ['as' => 'emails.delete']); // Delete email
$routes->delete('emails/delete-log/(:num)', 'EmailController::deleteEmailLog/$1', ['as' => 'emails.delete_log']); // Delete email log
$routes->get('emails/template/(:num)', 'EmailController::getTemplate/$1', ['as' => 'emails.get_template']); // Get template via AJAX
$routes->get('emails/create-template', 'EmailController::createTemplate', ['as' => 'emails.create_template']);

// User Management Routes
$routes->get('users', 'UserController::index', ['as' => 'users.index']);              // List all users
$routes->get('users/create', 'UserController::create', ['as' => 'users.create']);      // Show form to create new user
$routes->match(['GET', 'POST'], 'users/store', 'UserController::store', ['as' => 'users.store']);       // Handle new user submission
$routes->get('users/edit/(:num)', 'UserController::edit/$1', ['as' => 'users.edit']);    // Show form to edit user by ID
$routes->post('users/update/(:num)', 'UserController::update/$1', ['as' => 'users.update']); // Handle user update submission
$routes->get('users/delete/(:num)', 'UserController::delete/$1', ['as' => 'users.delete']); // Delete user by ID
$routes->get('users/view/(:num)', 'UserController::view/$1', ['as' => 'users.view']);          // View single user details by ID
$routes->post('users/suspend/(:num)', 'UserController::suspend/$1', ['as' => 'users.suspend']);         // Suspend user
$routes->get('users/unsuspend/(:num)', 'UserController::unsuspend/$1', ['as' => 'users.unsuspend']); // Unsuspend user by ID

// Admin Role Management Routes
$routes->get('admin_role', 'AdminRole::index');
$routes->get('admin_role/create', 'AdminRole::create');
$routes->post('admin_role/store', 'AdminRole::store');
$routes->get('admin_role/edit/(:num)', 'AdminRole::edit/$1');
$routes->post('admin_role/update/(:num)', 'AdminRole::update/$1');
$routes->get('admin_role/delete/(:num)', 'AdminRole::delete/$1');
$routes->get('admin_role/view/(:num)', 'AdminRole::view/$1');

// Reports & Analytics Routes
$routes->get('reports', 'ReportsController::index', ['as' => 'reports.index']);              // Main reports dashboard
$routes->get('reports/items', 'ReportsController::itemsReport', ['as' => 'reports.items']);  // Items report
$routes->get('reports/claims', 'ReportsController::claimsReport', ['as' => 'reports.claims']); // Claims report
$routes->get('reports/analytics', 'ReportsController::analytics', ['as' => 'reports.analytics']); // Advanced analytics
$routes->get('reports/export', 'ReportsController::export', ['as' => 'reports.export']);     // Export functionality

// Activity Logs Routes
$routes->get('activity-logs', 'ActivityLogController::index', ['as' => 'activity_logs.index']);              // List all activity logs
$routes->get('activity-logs/count', 'ActivityLogController::count', ['as' => 'activity_logs.count']);        // Get activity logs count for notification badge
$routes->get('activity-logs/details/(:num)', 'ActivityLogController::details/$1', ['as' => 'activity_logs.details']); // Get log details via AJAX
$routes->get('activity-logs/export', 'ActivityLogController::export', ['as' => 'activity_logs.export']);     // Export activity logs to CSV
$routes->get('activity-logs/stats', 'ActivityLogController::stats', ['as' => 'activity_logs.stats']);        // Get activity statistics
$routes->post('activity-logs/cleanup', 'ActivityLogController::cleanup', ['as' => 'activity_logs.cleanup']); // Cleanup old logs
$routes->post('activity-logs/mark-as-read', 'ActivityLogController::markAsRead', ['as' => 'activity_logs.mark_as_read']); // Mark multiple logs as read
$routes->post('activity-logs/mark-all-read', 'ActivityLogController::markAsRead', ['as' => 'activity_logs.mark_all_read']); // Mark all selected logs as read (alternative route)
$routes->post('activity-logs/mark-single-as-read/(:num)', 'ActivityLogController::markSingleAsRead/$1', ['as' => 'activity_logs.mark_single_as_read']); // Mark single log as read
$routes->post('activity-logs/delete-multiple', 'ActivityLogController::deleteMultiple', ['as' => 'activity_logs.delete_multiple']); // Delete multiple logs (admin only)
$routes->get('activity-logs/test-delete', 'ActivityLogController::testDelete');

// Notification Management Routes (with authentication)
$routes->group('notifications', ['filter' => 'auth'], function($routes) {
    $routes->get('', 'NotificationController::index', ['as' => 'notifications.index']);              // List all notifications
    $routes->get('get', 'NotificationController::getNotifications', ['as' => 'notifications.get']); // Get notifications via AJAX
    $routes->get('getUnreadCount', 'NotificationController::getUnreadCount', ['as' => 'notifications.unread_count']); // Get unread count via AJAX
    $routes->post('mark-read/(:num)', 'NotificationController::markAsRead/$1', ['as' => 'notifications.mark_read']); // Mark single notification as read
    $routes->post('mark-all-read', 'NotificationController::markAllAsRead', ['as' => 'notifications.mark_all_read']); // Mark all notifications as read
    $routes->delete('delete/(:num)', 'NotificationController::delete/$1', ['as' => 'notifications.delete']); // Delete single notification
    $routes->post('delete-all', 'NotificationController::deleteAll', ['as' => 'notifications.delete_all']); // Delete all notifications
    
    // Admin Notification Routes
    $routes->get('admin/stats', 'NotificationController::getAdminStats', ['as' => 'notifications.admin_stats']); // Get admin notification statistics
    $routes->post('admin/mark-all-read', 'NotificationController::markAllAdminAsRead', ['as' => 'notifications.admin_mark_all_read']); // Mark all admin notifications as read
    $routes->get('admin/urgent', 'NotificationController::getUrgentNotifications', ['as' => 'notifications.admin_urgent']); // Get urgent admin notifications
});

// Admin Notification Routes (with role-based access control)
$routes->group('admin/notifications', ['filter' => 'auth'], function($routes) {
    $routes->get('', 'NotificationController::adminIndex', ['as' => 'admin.notifications.index']);              // Admin notification management page
    $routes->get('stats', 'NotificationController::getAllStats', ['as' => 'admin.notifications.stats']);      // Get admin notification statistics
    $routes->post('bulk-mark-read', 'NotificationController::adminBulkMarkRead', ['as' => 'admin.notifications.bulk_mark_read']); // Bulk mark notifications as read
    $routes->post('bulk-delete', 'NotificationController::adminBulkDelete', ['as' => 'admin.notifications.bulk_delete']); // Bulk delete notifications
    $routes->post('delete-user-notifications', 'NotificationController::adminDeleteUserNotifications', ['as' => 'admin.notifications.delete_user']); // Delete all notifications for a user
});

// Feedback Management Routes
$routes->get('feedback', 'FeedbackController::index', ['as' => 'feedback.index']);              // List all feedback
$routes->get('feedback/view/(:any)', 'FeedbackController::view/$1', ['as' => 'feedback.view']);          // View single feedback details by ID
$routes->get('feedback/respond/(:any)', 'FeedbackController::respond/$1', ['as' => 'feedback.respond']);    // Show form to respond to feedback
$routes->post('feedback/update_response/(:any)', 'FeedbackController::updateResponse/$1', ['as' => 'feedback.update_response']); // Handle feedback response submission (underscore)
$routes->post('feedback/update-response/(:any)', 'FeedbackController::updateResponse/$1', ['as' => 'feedback.update-response']); // Handle feedback response submission (dash)
$routes->post('feedback/store_response/(:any)', 'FeedbackController::updateResponse/$1', ['as' => 'feedback.store_response']); // Handle feedback response submission (alternative route)
$routes->post('feedback/update_status/(:any)', 'FeedbackController::updateStatus/$1', ['as' => 'feedback.update_status']); // Update feedback status
$routes->delete('feedback/delete/(:any)', 'FeedbackController::delete/$1', ['as' => 'feedback.delete']); // Delete feedback by ID
$routes->get('feedback/analytics', 'FeedbackController::analytics', ['as' => 'feedback.analytics']); // Feedback analytics dashboard
// Help & Support Management Routes
$routes->get('help-support', 'HelpSupportController::index', ['as' => 'help_support.index']);              // Help & Support dashboard
$routes->get('help-support/search', 'HelpSupportController::search', ['as' => 'help_support.search']);      // Search help content

// FAQ Management Routes
$routes->get('help-support/faqs', 'HelpSupportController::faqs', ['as' => 'help_support.faqs']);                   // List all FAQs
$routes->get('help-support/faqs/create', 'HelpSupportController::createFaq', ['as' => 'help_support.faqs.create']);   // Show form to create new FAQ
$routes->post('help-support/faqs/create', 'HelpSupportController::createFaq', ['as' => 'help_support.faqs.store']);   // Handle new FAQ submission
$routes->get('help-support/faqs/view/(:num)', 'HelpSupportController::viewFaq/$1', ['as' => 'help_support.faqs.view']); // View single FAQ details
$routes->get('help-support/faqs/edit/(:num)', 'HelpSupportController::editFaq/$1', ['as' => 'help_support.faqs.edit']); // Show form to edit FAQ
$routes->post('help-support/faqs/edit/(:num)', 'HelpSupportController::editFaq/$1', ['as' => 'help_support.faqs.update']); // Handle FAQ update
$routes->delete('help-support/faqs/delete/(:num)', 'HelpSupportController::deleteFaq/$1', ['as' => 'help_support.faqs.delete']); // Delete FAQ

// Announcement Management Routes
$routes->get('help-support/announcements', 'HelpSupportController::announcements', ['as' => 'help_support.announcements']); // List all announcements
$routes->get('help-support/announcements/create', 'HelpSupportController::createAnnouncement', ['as' => 'help_support.announcements.create']); // Show form to create new announcement
$routes->post('help-support/announcements/create', 'HelpSupportController::createAnnouncement', ['as' => 'help_support.announcements.store']); // Handle new announcement submission
$routes->get('help-support/announcements/view/(:num)', 'HelpSupportController::viewAnnouncement/$1', ['as' => 'help_support.announcements.view']); // View single announcement details
$routes->get('help-support/announcements/edit/(:num)', 'HelpSupportController::editAnnouncement/$1', ['as' => 'help_support.announcements.edit']); // Show form to edit announcement
$routes->post('help-support/announcements/edit/(:num)', 'HelpSupportController::editAnnouncement/$1', ['as' => 'help_support.announcements.update']); // Handle announcement update
$routes->delete('help-support/announcements/delete/(:num)', 'HelpSupportController::deleteAnnouncement/$1', ['as' => 'help_support.announcements.delete']); // Delete announcement

// Support Ticket Management Routes
$routes->get('help-support/tickets', 'HelpSupportController::tickets', ['as' => 'help_support.tickets']);    // List all support tickets
$routes->get('help-support/tickets/create', 'HelpSupportController::createTicket', ['as' => 'help_support.tickets.create']); // Show form to create new ticket
$routes->post('help-support/tickets/create', 'HelpSupportController::createTicket', ['as' => 'help_support.tickets.store']); // Handle new ticket submission
$routes->get('help-support/tickets/view/(:any)', 'HelpSupportController::viewTicket/$1', ['as' => 'help_support.tickets.view']); // View single ticket details
$routes->get('help-support/tickets/edit/(:any)', 'HelpSupportController::editTicket/$1', ['as' => 'help_support.tickets.edit']); // Show form to edit ticket
$routes->post('help-support/tickets/edit/(:any)', 'HelpSupportController::editTicket/$1', ['as' => 'help_support.tickets.update']); // Handle ticket update
$routes->match(['GET', 'POST', 'DELETE'], 'help-support/tickets/delete/(:any)', 'HelpSupportController::deleteTicket/$1', ['as' => 'help_support.tickets.delete']); // Delete ticket
$routes->get('help-support/tickets/reply/(:any)', 'HelpSupportController::replyTicket/$1', ['as' => 'help_support.tickets.reply']); // Show reply form
$routes->post('help-support/tickets/reply/(:any)', 'HelpSupportController::replyTicket/$1', ['as' => 'help_support.tickets.store_reply']); // Handle ticket reply
$routes->post('help-support/tickets/update-status/(:any)', 'HelpSupportController::updateTicketStatus/$1', ['as' => 'help_support.tickets.update_status']); // Update ticket status

// Help & Support Analytics Routes
$routes->get('help-support/analytics', 'HelpSupportController::analytics', ['as' => 'help_support.analytics']); // Analytics dashboard

// API Routes for Mobile App
$routes->get('api/help-support/faqs', 'HelpSupportController::apiFaqs', ['as' => 'api.help_support.faqs']);           // Get FAQs for mobile
$routes->get('api/help-support/announcements', 'HelpSupportController::apiAnnouncements', ['as' => 'api.help_support.announcements']); // Get announcements for mobile
$routes->post('api/help-support/tickets', 'HelpSupportController::apiSubmitTicket', ['as' => 'api.help_support.submit_ticket']); // Submit ticket from mobile
$routes->post('api/help-support/faq-view/(:num)', 'HelpSupportController::apiTrackFaqView/$1', ['as' => 'api.help_support.track_faq_view']); // Track FAQ view

// Authentication API Routes
$routes->group('api/auth', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->post('login', 'AuthController::login', ['as' => 'api.auth.login']);
    $routes->get('user', 'AuthController::getUser', ['as' => 'api.auth.user']);
    $routes->post('logout', 'AuthController::logout', ['as' => 'api.auth.logout']);
    $routes->post('refresh', 'AuthController::refreshToken', ['as' => 'api.auth.refresh']);
});

// User Management API Routes
$routes->group('api/users', ['namespace' => 'App\Controllers\Api'], function($routes) {
    $routes->get('/', 'UserApiController::index', ['as' => 'api.users.index']);                    // GET /api/users - Get all users
    $routes->get('search', 'UserApiController::search', ['as' => 'api.users.search']);             // GET /api/users/search?q={query} - Search users
    $routes->get('(:num)', 'UserApiController::show/$1', ['as' => 'api.users.show']);              // GET /api/users/{id} - Get specific user
    $routes->post('/', 'UserApiController::create', ['as' => 'api.users.create']);                 // POST /api/users - Create new user
    $routes->put('(:num)', 'UserApiController::update/$1', ['as' => 'api.users.update']);          // PUT /api/users/{id} - Update user
    $routes->delete('(:num)', 'UserApiController::delete/$1', ['as' => 'api.users.delete']);       // DELETE /api/users/{id} - Delete user
});

// Profile Management Routes
$routes->get('profile', 'Profile::index', ['as' => 'profile.index']);
$routes->post('profile/change-password', 'Profile::changePassword', ['as' => 'profile.change_password']);
$routes->get('reports/search', 'ReportsController::search');

// Email Reply Detection Routes (Gmail IMAP Polling)
$routes->get('email-poller/check', 'EmailPollerController::check');    // Check Gmail for new replies
$routes->get('email-poller/test', 'EmailPollerController::test');      // Test notification creation
$routes->get('email-poller/status', 'EmailPollerController::status');  // Check IMAP configuration status
$routes->get('email-poller/debug', 'EmailPollerController::debug');    // Debug - list inbox emails
