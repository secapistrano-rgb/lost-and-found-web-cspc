<?php
// Quick test script to verify admin access to feedback and help-support

$host = 'localhost';
$dbname = 'lost_and_found';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ADMIN FEEDBACK ACCESS TEST ===\n\n";
    
    // Get admin user
    $adminEmail = 'secapistrano@my.cspc.edu.ph';
    $stmt = $pdo->prepare("SELECT * FROM admin_role WHERE email = ?");
    $stmt->execute([$adminEmail]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        echo "❌ Admin user not found!\n";
        exit;
    }
    
    echo "👤 Admin found: {$admin['email']}\n";
    echo "🏷️  Role: '{$admin['role']}'\n\n";
    
    // Test permission logic (matching RoleFilter.php)
    $normalizedRole = strtolower(str_replace(' ', '_', trim($admin['role'])));
    
    $permissions = [
        'super_admin' => ['*'],
        'admin' => [
            'dashboard', 'items', 'claims', 'emails', 'users', 'admin_role', 
            'reports', 'feedback', 'help-support', 'settings', 'notifications',
            'analytics', 'export', 'import', 'logs'
        ],
        'staff' => [
            'dashboard', 'claims', 'feedback', 'help-support', 'items'
        ]
    ];
    
    echo "🔍 Normalized role: '$normalizedRole'\n";
    echo "📋 Role permissions: " . json_encode($permissions[$normalizedRole] ?? []) . "\n\n";
    
    // Test specific routes
    $testRoutes = ['feedback', 'help-support', 'feedback/view', 'help-support/faqs'];
    
    foreach ($testRoutes as $route) {
        $segments = explode('/', trim($route, '/'));
        $firstSegment = $segments[0] ?? '';
        
        // Check permission
        $hasAccess = false;
        if (isset($permissions[$normalizedRole])) {
            if (in_array('*', $permissions[$normalizedRole]) || 
                in_array($firstSegment, $permissions[$normalizedRole])) {
                $hasAccess = true;
            }
        }
        
        $status = $hasAccess ? '✅ ALLOWED' : '❌ DENIED';
        echo "🌐 Route: /$route -> $status\n";
    }
    
    echo "\n=== SUMMARY ===\n";
    if (isset($permissions[$normalizedRole]) && 
        (in_array('*', $permissions[$normalizedRole]) || 
         (in_array('feedback', $permissions[$normalizedRole]) && in_array('help-support', $permissions[$normalizedRole])))) {
        echo "✅ Admin should have access to Feedback and Help-Support\n";
        echo "\n💡 If still not working, try:\n";
        echo "   1. Logout completely\n";
        echo "   2. Clear browser cache (Ctrl+Shift+Delete)\n";
        echo "   3. Login again\n";
        echo "   4. Check browser console for errors (F12)\n";
    } else {
        echo "❌ Admin does NOT have proper permissions\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}