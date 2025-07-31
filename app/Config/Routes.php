<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Login Routes
$routes->get('login', 'Login::index');
$routes->get('login/auth', 'Login::auth');
$routes->get('login/callback', 'Login::callback');
$routes->get('logout', 'Login::logout');

$routes->get('/', 'Dashboard::index');                // Default route to dashboard
$routes->get('dashboard', 'Dashboard::index');    // Dashboard route
$routes->get('dashboard/items', 'ItemController::index'); // Item management route

// Item Management Routes
$routes->get('items', 'ItemController::index');              // List all items
$routes->get('items/create', 'ItemController::create');      // Show form to create new item
$routes->post('items/store', 'ItemController::store');       // Handle new item submission
$routes->get('items/edit/(:num)', 'ItemController::edit/$1');    // Show form to edit item by ID
$routes->post('items/update/(:num)', 'ItemController::update/$1'); // Handle item update submission
$routes->get('items/update/(:num)', 'ItemController::update/$1'); // Handle item update submission
$routes->get('items/delete/(:num)', 'ItemController::delete/$1'); // Delete item by ID
$routes->get('items/view/(:num)', 'ItemController::view/$1');          // View single item details by ID

// Claims Management Routes
$routes->get('claims', 'ClaimController::index', ['as' => 'claims.index']);              // List all claims
$routes->get('claims/create', 'ClaimController::create', ['as' => 'claims.create']);      // Show form to create new claim
$routes->match(['get', 'post'], 'claims/store', 'ClaimController::store', ['as' => 'claims.store']);       // Handle new claim submission
$routes->get('claims/edit/(:num)', 'ClaimController::edit/$1', ['as' => 'claims.edit']);    // Show form to edit claim by ID
$routes->post('claims/update/(:num)', 'ClaimController::update/$1', ['as' => 'claims.update']); // Handle claim update submission
$routes->get('claims/delete/(:num)', 'ClaimController::delete/$1', ['as' => 'claims.delete']); // Delete claim by ID
$routes->get('claims/view/(:num)', 'ClaimController::view/$1', ['as' => 'claims.view']);          // View single claim details by ID

// User Management Routes
$routes->get('users', 'UserController::index', ['as' => 'users.index']);              // List all users
$routes->get('users/create', 'UserController::create', ['as' => 'users.create']);      // Show form to create new user
$routes->match(['get', 'post'], 'users/store', 'UserController::store', ['as' => 'users.store']);       // Handle new user submission
$routes->get('users/edit/(:num)', 'UserController::edit/$1', ['as' => 'users.edit']);    // Show form to edit user by ID
$routes->post('users/update/(:num)', 'UserController::update/$1', ['as' => 'users.update']); // Handle user update submission
$routes->get('users/delete/(:num)', 'UserController::delete/$1', ['as' => 'users.delete']); // Delete user by ID
$routes->get('users/view/(:num)', 'UserController::view/$1', ['as' => 'users.view']);          // View single user details by ID

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

