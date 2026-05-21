<?php
// Simple database connection test without CodeIgniter bootstrap
$host = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'lost_and_found';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== Testing Admin Role Permissions ===\n\n";

// Get the admin user
$result = $conn->query("SELECT * FROM admin_role WHERE role LIKE '%admin%'");

while ($user = $result->fetch_assoc()) {
    echo "User: " . $user['name'] . "\n";
    echo "  Email: " . $user['email'] . "\n";
    echo "  Role (raw): '" . $user['role'] . "'\n";
    echo "  Role (trimmed): '" . trim($user['role']) . "'\n";
    echo "  Role (lowercase): '" . strtolower($user['role']) . "'\n";
    echo "  Role (normalized): '" . strtolower(str_replace(' ', '_', $user['role'])) . "'\n";
    echo "  Role length: " . strlen($user['role']) . " bytes\n";
    
    // Check for hidden characters
    $hex = '';
    for ($i = 0; $i < strlen($user['role']); $i++) {
        $hex .= dechex(ord($user['role'][$i])) . ' ';
    }
    echo "  Role (hex): " . $hex . "\n";
    echo "\n";
}

echo "\n=== Testing Permissions Array ===\n\n";

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
print_r($permissions['admin']);

echo "\nTesting access checks:\n";
$testRoles = ['admin', 'Admin', 'admin ', ' admin'];

foreach ($testRoles as $testRole) {
    $normalizedRole = strtolower(str_replace(' ', '_', trim($testRole)));
    $hasAccess = isset($permissions[$normalizedRole]);
    echo "  Role: '" . $testRole . "' -> Normalized: '" . $normalizedRole . "' -> Has access: " . ($hasAccess ? "YES ✓" : "NO ✗") . "\n";
}

$conn->close();
