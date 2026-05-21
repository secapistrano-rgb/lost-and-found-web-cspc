<?php
// Debug script to check admin permissions
require 'vendor/autoload.php';

$config = include 'app/Config/Database.php';
$db = \Config\Database::connect();

echo "=== CHECKING ADMIN PERMISSIONS ===\n\n";

// Get all admin roles
$query = $db->query("SELECT id, name, email, role FROM admin_roles WHERE LOWER(role) = 'admin'");
$admins = $query->getResultArray();

echo "Found " . count($admins) . " admin user(s):\n\n";

foreach ($admins as $admin) {
    echo "ID: {$admin['id']}\n";
    echo "Name: {$admin['name']}\n";
    echo "Email: {$admin['email']}\n";
    echo "Role (DB): {$admin['role']}\n";
    
    // Normalize role like the system does
    $normalizedRole = str_replace(' ', '_', strtolower($admin['role']));
    echo "Role (Normalized): {$normalizedRole}\n";
    
    // Check permissions
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
    
    if (isset($permissions[$normalizedRole])) {
        echo "Has Permissions: " . implode(', ', $permissions[$normalizedRole]) . "\n";
        echo "✅ Can access feedback: " . (in_array('feedback', $permissions[$normalizedRole]) ? 'YES' : 'NO') . "\n";
        echo "✅ Can access help-support: " . (in_array('help-support', $permissions[$normalizedRole]) ? 'YES' : 'NO') . "\n";
    } else {
        echo "❌ No permissions found for role: {$normalizedRole}\n";
    }
    
    // Check sidebar visibility
    $showInSidebar = in_array($normalizedRole, ['super_admin', 'admin', 'staff']);
    echo "✅ Show in sidebar: " . ($showInSidebar ? 'YES' : 'NO') . "\n";
    
    echo "\n" . str_repeat('-', 50) . "\n\n";
}

// Check all roles in database
echo "\n=== ALL ROLES IN DATABASE ===\n\n";
$allRoles = $db->query("SELECT DISTINCT role FROM admin_roles")->getResultArray();
foreach ($allRoles as $r) {
    echo "- {$r['role']}\n";
}

echo "\n=== DONE ===\n";
