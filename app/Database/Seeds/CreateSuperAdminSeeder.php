<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CreateSuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Super admin details
        $email = 'chfrondozo@my.cspc.edu.ph';
        $password = 'admin123'; // Change this to your desired password
        $name = 'CJ FRONDOZO';
        
        echo "Creating super admin account...\n";
        echo "Email: " . $email . "\n";
        echo "Password: " . $password . "\n\n";
        
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $userData = [
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name,
            'department' => 'Administration',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Check if user already exists
        $userBuilder = $this->db->table('users');
        $existingUser = $userBuilder->where('email', $userData['email'])->get()->getRow();
        
        if (!$existingUser) {
            $userId = $userBuilder->insert($userData, true);
            echo "✅ Super admin user created: {$email}\n";
        } else {
            // Update existing user with new password
            $userBuilder->where('email', $userData['email'])->update([
                'password' => $userData['password'],
                'department' => 'Administration',
                'status' => 'active',
                'updated_at' => $userData['updated_at']
            ]);
            $userId = $existingUser->id;
            echo "✅ Super admin user updated: {$email}\n";
        }
        
        // Add to admin_role table for super admin privileges
        $adminBuilder = $this->db->table('admin_role');
        $existingAdmin = $adminBuilder->where('email', $email)->get()->getRow();
        
        if (!$existingAdmin) {
            $adminData = [
                'email' => $email,
                'name' => $name,
                'role' => 'super_admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $adminBuilder->insert($adminData);
            echo "✅ Super admin role assigned to: {$email}\n";
        } else {
            $adminBuilder->where('email', $email)->update([
                'role' => 'super_admin',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            echo "✅ Super admin role updated for: {$email}\n";
        }
        
        echo "\n🎉 Super admin setup complete!\n";
        echo "Login credentials:\n";
        echo "Email: " . $email . "\n";
        echo "Password: " . $password . "\n";
    }
}