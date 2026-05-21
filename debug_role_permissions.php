<?php
// Simple debug script to check role permissions
echo "=== FILTER PERMISSIONS TEST ===\n\n";

// Test the role filter logic
$testRoles = ['admin', 'super_admin', 'staff', 'Admin', 'Super Admin'];
foreach ($testRoles as $role) {
    // Normalize role
    $normalizedRole = strtolower(str_replace(' ', '_', $role));
    
    $permissions = [
        'super_admin' => ['*'],
        'admin' => ['*'],
        'staff' => ['dashboard', 'claims', 'feedback', 'help-support', 'items']
    ];
    
    $hasWildcard = isset($permissions[$normalizedRole]) && in_array('*', $permissions[$normalizedRole]);
    $hasFeedback = isset($permissions[$normalizedRole]) && in_array('feedback', $permissions[$normalizedRole]);
    $hasAccess = $hasWildcard || $hasFeedback;
    
    echo "Original Role: '$role'\n";
    echo "Normalized Role: '$normalizedRole'\n";
    echo "Has Wildcard: " . ($hasWildcard ? 'YES' : 'NO') . "\n";
    echo "Has Feedback: " . ($hasFeedback ? 'YES' : 'NO') . "\n";
    echo "Can Access Feedback: " . ($hasAccess ? 'YES' : 'NO') . "\n";
    echo "---\n";
}
?>
