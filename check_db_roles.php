<?php
// Direct database check
$host = 'localhost';
$db   = 'lost_and_found';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== ADMIN ROLES IN DATABASE ===\n\n";
    
    $stmt = $pdo->query("SELECT id, email, role FROM admin_role");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($roles as $role) {
        echo "ID: {$role['id']}\n";
        echo "Email: {$role['email']}\n";
        echo "Role: '{$role['role']}'\n";
        echo "Normalized: '" . strtolower(str_replace(' ', '_', $role['role'])) . "'\n";
        echo "---\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
