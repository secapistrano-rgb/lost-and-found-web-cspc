<?php

echo "=== Testing Admin Role Permissions ===\n\n";

// Exact permissions from RoleFilter.php
$permissions = [
    'super_admin' => ['*'],
    'admin' => [
        'dashboard', 'items', 'claims', 'emails', 'users', 'admin_role', 'reports', 
        'feedback', 'help-support'
    ],
    'staff' => [
        'dashboard', 'claims', 'feedback', 'help-support', 'items'
    ]
];

echo "Admin role permissions:\n";
foreach ($permissions['admin'] as $perm) {
    echo "  - $perm\n";
}

echo "\n=== Testing Specific Routes ===\n\n";

echo "Testing if 'feedback' is in admin permissions: ";
echo in_array('feedback', $permissions['admin']) ? "YES ✓\n" : "NO ✗\n";

echo "Testing if 'help-support' is in admin permissions: ";
echo in_array('help-support', $permissions['admin']) ? "YES ✓\n" : "NO ✗\n";

echo "\n=== Testing URI Segment Matching (RoleFilter Logic) ===\n\n";

function testPermission($uri, $userRole, $permissions) {
    // This is the exact logic from RoleFilter::hasPermission()
    
    // Super admin has access to everything
    if ($userRole === 'super_admin') {
        return true;
    }
    
    // Remove leading slash and get first segment
    $uri = trim($uri, '/');
    $segments = explode('/', $uri);
    $firstSegment = $segments[0] ?? '';
    
    // Allow access to login and logout
    if (in_array($firstSegment, ['login', 'logout', ''])) {
        return true;
    }
    
    // Check if user role has permission for this route
    if (!isset($permissions[$userRole])) {
        return false;
    }
    
    return in_array($firstSegment, $permissions[$userRole]);
}

$testUris = [
    '/feedback',
    '/feedback/view/1',
    '/feedback/respond/5',
    '/help-support',
    '/help-support/search',
    '/help-support/faqs',
];

foreach ($testUris as $uri) {
    $hasPermission = testPermission($uri, 'admin', $permissions);
    
    echo "URI: $uri\n";
    
    $uriTrimmed = trim($uri, '/');
    $segments = explode('/', $uriTrimmed);
    $firstSegment = $segments[0] ?? '';
    echo "  First segment: '$firstSegment'\n";
    echo "  Admin has permission: " . ($hasPermission ? "YES ✓" : "NO ✗") . "\n";
    echo str_repeat('-', 60) . "\n";
}

echo "\n=== Testing Staff Role for Comparison ===\n\n";

foreach ($testUris as $uri) {
    $hasPermission = testPermission($uri, 'staff', $permissions);
    
    echo "URI: $uri\n";
    echo "  Staff has permission: " . ($hasPermission ? "YES ✓" : "NO ✗") . "\n";
    echo str_repeat('-', 60) . "\n";
}

echo "\nTest completed!\n";
