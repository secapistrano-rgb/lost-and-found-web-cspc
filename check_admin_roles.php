<?php

// Simple database check script
$host = 'localhost';
$db   = 'lost_and_found';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    echo "=== Checking Admin Roles in Database ===\n\n";
    
    $stmt = $pdo->query("SELECT id, name, email, role FROM admin_role ORDER BY role, name");
    $roles = $stmt->fetchAll();
    
    foreach ($roles as $role) {
        $roleValue = $role['role'];
        $roleLength = strlen($roleValue);
        $roleHex = bin2hex($roleValue);
        
        echo "ID: {$role['id']}\n";
        echo "Name: {$role['name']}\n";
        echo "Email: {$role['email']}\n";
        echo "Role: '{$roleValue}'\n";
        echo "Role Length: {$roleLength} characters\n";
        echo "Role (hex): {$roleHex}\n";
        echo "Role (lowercase): '" . strtolower($roleValue) . "'\n";
        echo "Role (normalized): '" . strtolower(str_replace(' ', '_', $roleValue)) . "'\n";
        echo str_repeat('=', 70) . "\n\n";
    }
    
    echo "\n=== Checking for 'Admin' Role Issues ===\n\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin_role WHERE role = 'Admin'");
    $result = $stmt->fetch();
    echo "'Admin' (capital A): {$result['count']} records\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin_role WHERE role = 'admin'");
    $result = $stmt->fetch();
    echo "'admin' (lowercase): {$result['count']} records\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin_role WHERE LOWER(role) = 'admin'");
    $result = $stmt->fetch();
    echo "'admin' (case-insensitive): {$result['count']} records\n";
    
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
