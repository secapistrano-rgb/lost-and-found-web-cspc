<?php

// Create an admin user for testing
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
    
    echo "=== Creating Admin User ===\n\n";
    
    // Admin user details
    $name = "Test Admin";
    $email = "admin@test.com";
    $password = password_hash("Admin@123", PASSWORD_DEFAULT);
    $role = "admin";
    
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM admin_role WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        echo "User already exists with email: $email\n";
        echo "Updating role to 'admin'...\n\n";
        
        $stmt = $pdo->prepare("UPDATE admin_role SET role = ?, name = ? WHERE email = ?");
        $stmt->execute([$role, $name, $email]);
        
        echo "✓ User updated successfully!\n";
    } else {
        echo "Creating new admin user...\n\n";
        
        $stmt = $pdo->prepare("
            INSERT INTO admin_role (name, email, password, role, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$name, $email, $password, $role]);
        
        echo "✓ Admin user created successfully!\n";
    }
    
    echo "\n=== Admin User Details ===\n\n";
    echo "Name: $name\n";
    echo "Email: $email\n";
    echo "Password: Admin@123\n";
    echo "Role: $role\n";
    
    echo "\n=== Testing Login ===\n";
    echo "1. Go to: http://localhost:8080/login\n";
    echo "2. Email: $email\n";
    echo "3. Password: Admin@123\n";
    echo "4. Try accessing Feedback and Help & Support from sidebar\n";
    
    echo "\n=== Current Users ===\n\n";
    $stmt = $pdo->query("SELECT id, name, email, role FROM admin_role ORDER BY role, name");
    $users = $stmt->fetchAll();
    
    foreach ($users as $u) {
        echo sprintf("%-4s | %-20s | %-30s | %s\n", 
            $u['id'], 
            substr($u['name'], 0, 20), 
            substr($u['email'], 0, 30), 
            $u['role']
        );
    }
    
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
