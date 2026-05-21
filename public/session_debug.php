<?php
// Real-time session debug for admin access issues

session_start();

echo "<h2>ADMIN SESSION DEBUG</h2>\n";
echo "<pre>\n";

echo "=== CURRENT SESSION DATA ===\n";
print_r($_SESSION);

echo "\n=== CHECKING ADMIN ROLE IN DATABASE ===\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=lost_and_found", 'root', 'root');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if session email exists
    $sessionEmail = $_SESSION['email'] ?? 'NOT SET';
    echo "Session Email: $sessionEmail\n";
    
    if ($sessionEmail !== 'NOT SET') {
        $stmt = $pdo->prepare("SELECT * FROM admin_role WHERE email = ?");
        $stmt->execute([$sessionEmail]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            echo "Database Role: {$admin['role']}\n";
            echo "Role (normalized): " . strtolower(str_replace(' ', '_', $admin['role'])) . "\n";
        } else {
            echo "❌ NO ADMIN FOUND IN DATABASE!\n";
        }
    }
} catch (Exception $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}

echo "\n=== PERMISSION TEST ===\n";

// Simulate the exact permission check from RoleFilter.php
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

$userRole = strtolower(str_replace(' ', '_', $admin['role'] ?? 'none'));
echo "Checking role: $userRole\n";
echo "Has feedback permission: " . (in_array('feedback', $permissions[$userRole] ?? []) ? 'YES' : 'NO') . "\n";
echo "Has help-support permission: " . (in_array('help-support', $permissions[$userRole] ?? []) ? 'YES' : 'NO') . "\n";

echo "\n=== DIRECT LINKS FOR TESTING ===\n";
echo '<a href="/feedback">Test Feedback Access</a><br>';
echo '<a href="/help-support">Test Help-Support Access</a><br>';
echo '<a href="/dashboard">Back to Dashboard</a><br>';

echo "</pre>";
?>