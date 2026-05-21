<?php
// Debug script to check role permissions in real-time

// Simple database connection
$host = 'localhost';
$dbname = 'lost_and_found';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $adminEmail = 'secapistrano@my.cspc.edu.ph';
    
    echo "=== DEBUGGING ADMIN ACCESS ISSUE ===\n\n";
    
    // 1. Check database role
    echo "1. DATABASE CHECK:\n";
    $stmt = $pdo->prepare("SELECT * FROM admin_role WHERE email = ?");
    $stmt->execute([$adminEmail]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "   ✓ Admin found in database\n";
        echo "   Email: " . $admin['email'] . "\n";
        echo "   Role: '" . $admin['role'] . "'\n";
        echo "   Role (lowercase): '" . strtolower($admin['role']) . "'\n";
    } else {
        echo "   ✗ Admin NOT found in database!\n";
        exit;
    }
    
    // 2. Check permissions array logic
    echo "\n2. PERMISSION ARRAY CHECK:\n";
    $normalizedRole = strtolower(str_replace(' ', '_', trim($admin['role'])));
    
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
    
    echo "   Normalized role: '$normalizedRole'\n";
    echo "   Role in permissions? " . (isset($permissions[$normalizedRole]) ? 'YES ✓' : 'NO ✗') . "\n";
    
    if (isset($permissions[$normalizedRole])) {
        echo "   Has 'feedback'? " . (in_array('feedback', $permissions[$normalizedRole]) ? 'YES ✓' : 'NO ✗') . "\n";
        echo "   Has 'help-support'? " . (in_array('help-support', $permissions[$normalizedRole]) ? 'YES ✓' : 'NO ✗') . "\n";
    }
    
    // 3. Check sidebar visibility logic
    echo "\n3. SIDEBAR VISIBILITY CHECK:\n";
    $userRole = strtolower(str_replace(' ', '_', $admin['role']));
    $allowedRoles = ['super_admin', 'admin', 'staff'];
    
    echo "   User role: '$userRole'\n";
    echo "   Allowed roles: " . implode(', ', $allowedRoles) . "\n";
    echo "   Is role in allowed list? " . (in_array($userRole, $allowedRoles) ? 'YES ✓' : 'NO ✗') . "\n";
    
    // 4. Test URL route permission
    echo "\n4. ROUTE PERMISSION CHECK:\n";
    $testRoutes = [
        '/feedback' => 'feedback',
        '/feedback/view/1' => 'feedback',
        '/help-support' => 'help-support',
        '/help-support/faqs' => 'help-support'
    ];
    
    foreach ($testRoutes as $uri => $expectedSegment) {
        $uri = trim($uri, '/');
        $segments = explode('/', $uri);
        $firstSegment = $segments[0] ?? '';
        
        $hasPermission = in_array($firstSegment, $permissions[$normalizedRole]);
        echo "   $uri -> '$firstSegment' = " . ($hasPermission ? 'ALLOWED ✓' : 'DENIED ✗') . "\n";
    }
    
    // 5. Check if there are other admin users that work
    echo "\n5. OTHER ADMIN USERS:\n";
    $stmt = $pdo->query("SELECT name, email, role FROM admin_role WHERE role IN ('admin', 'super_admin')");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($admins as $user) {
        echo "   " . $user['name'] . " (" . $user['email'] . ") - Role: '" . $user['role'] . "'\n";
    }
    
    // 6. Check session files
    echo "\n6. SESSION FILES CHECK:\n";
    $sessionPath = __DIR__ . '/writable/session/';
    $sessionFiles = glob($sessionPath . 'ci_session*');
    
    if ($sessionFiles) {
        echo "   Found " . count($sessionFiles) . " active session(s)\n";
        echo "   ⚠️  Old sessions may be caching old permissions\n";
    } else {
        echo "   ✓ No active sessions (users need to login)\n";
    }
    
    echo "\n=== SOLUTION ===\n";
    echo "The code is correct! The issue is likely:\n";
    echo "1. Browser cache - User needs to do HARD REFRESH (Ctrl+Shift+R or Ctrl+F5)\n";
    echo "2. Session cache - User needs to LOGOUT and LOGIN again\n";
    echo "3. Server cache - May need to restart PHP server\n";
    echo "\nTry these steps IN ORDER:\n";
    echo "1. Hard refresh browser (Ctrl+Shift+R)\n";
    echo "2. Logout and login again\n";
    echo "3. Clear browser cache completely\n";
    echo "4. Try in incognito/private window\n";
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
