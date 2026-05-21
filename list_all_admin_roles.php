<?php

// Check all users
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
    
    echo "=== ALL Users in admin_role table ===\n\n";
    
    $stmt = $pdo->query("SELECT id, name, email, role FROM admin_role ORDER BY id");
    $roles = $stmt->fetchAll();
    
    echo "Total users: " . count($roles) . "\n\n";
    
    $roleCount = [];
    foreach ($roles as $role) {
        $roleValue = strtolower($role['role']);
        if (!isset($roleCount[$roleValue])) {
            $roleCount[$roleValue] = 0;
        }
        $roleCount[$roleValue]++;
        
        echo sprintf("%-4s | %-30s | %-35s | %s\n", 
            $role['id'], 
            substr($role['name'], 0, 30), 
            substr($role['email'], 0, 35), 
            $role['role']
        );
    }
    
    echo "\n=== Role Distribution ===\n\n";
    foreach ($roleCount as $role => $count) {
        echo sprintf("%-15s: %d users\n", ucfirst($role), $count);
    }
    
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
