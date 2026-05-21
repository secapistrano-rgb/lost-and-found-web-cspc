<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Super admin account - please update with your real details
        echo "\nPlease enter your CSPC email address: ";
        $email = trim(fgets(STDIN));
        
        echo "Please enter your desired password: ";
        $password = trim(fgets(STDIN));
        
        echo "Please enter your full name: ";
        $name = trim(fgets(STDIN));
        
        // Validate CSPC email
        if (!str_ends_with($email, '@my.cspc.edu.ph')) {
            echo "Error: Please use a valid @my.cspc.edu.ph email address.\n";
            return;
        }
        
        if (empty($password) || strlen($password) < 6) {
            echo "Error: Password must be at least 6 characters long.\n";
            return;
        }
        
        $data = [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'name' => $name,
            'department' => 'Administration',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Check if user already exists
        $builder = $this->db->table('users');
        $existingUser = $builder->where('email', $data['email'])->get()->getRow();
        
        if (!$existingUser) {
            $userId = $builder->insert($data, true);
            echo "Super admin created: {$email}\n";
        } else {
            // Update existing user with password and admin status
            $builder->where('email', $data['email'])->update([
                'password' => $data['password'],
                'department' => 'Administration',
                'status' => 'active',
                'updated_at' => $data['updated_at']
            ]);
            $userId = $existingUser->id;
            echo "Super admin updated: {$email}\n";
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
            echo "Super admin role assigned to: {$email}\n";
        } else {
            $adminBuilder->where('email', $email)->update([
                'role' => 'super_admin',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            echo "Super admin role updated for: {$email}\n";
        }
    }
}