<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NewSuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Super admin details
        $email = 'chfrondozo@my.cspc.edu.ph';
        $password = 'admin123'; // You can change this
        $name = 'CJ FRONDOZO';
        
        echo "Creating/Updating super admin account...\n";
        echo "Email: " . $email . "\n";
        echo "Password: " . $password . "\n\n";
        
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Data for users table
        $userData = [
            'email' => $email,
            'password' => $hashedPassword,
            'full_name' => $name, // Changed from 'name' to 'full_name'
            'department' => 'Administration',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Check if user already exists in users table
        $userBuilder = $this->db->table('users');
        $existingUser = $userBuilder->where('email', $userData['email'])->get()->getRow();
        
        if (!$existingUser) {
            $userId = $userBuilder->insert($userData, true);
            echo "✅ Super admin user created in users table: {$email}\n";
        } else {
            // Update existing user with new password
            $userBuilder->where('email', $userData['email'])->update([
                'password' => $userData['password'],
                'full_name' => $name, // Changed from 'name' to 'full_name'
                'department' => 'Administration',
                'status' => 'active',
                'updated_at' => $userData['updated_at']
            ]);
            $userId = $existingUser->id;
            echo "✅ Super admin user updated in users table: {$email}\n";
        }
        
        // Data for admin_role table (THIS IS CRUCIAL FOR LOGIN)
        $adminData = [
            'email' => $email,
            'name' => $name,
            'role' => 'super_admin',
            'password' => $hashedPassword, // This is the key fix!
            'profile_pic' => 'http://localhost:8080/images/logo.png',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Check if admin role already exists
        $adminBuilder = $this->db->table('admin_role');
        $existingAdmin = $adminBuilder->where('email', $email)->get()->getRow();
        
        if (!$existingAdmin) {
            $adminBuilder->insert($adminData);
            echo "✅ Super admin role created in admin_role table: {$email}\n";
        } else {
            // Update existing admin with password and role
            $adminBuilder->where('email', $email)->update([
                'name' => $name,
                'role' => 'super_admin',
                'password' => $hashedPassword, // This fixes the NULL password issue!
                'profile_pic' => 'http://localhost:8080/images/logo.png',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            echo "✅ Super admin role updated in admin_role table: {$email}\n";
        }
        
        echo "\n🎉 Super admin setup complete!\n";
        echo "Login credentials:\n";
        echo "Email: " . $email . "\n";
        echo "Password: " . $password . "\n";
        echo "\n⚠️  IMPORTANT: Please change this password after your first login!\n";
        echo "🌐 Login URL: http://localhost:8080/login\n";
    }
}