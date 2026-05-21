<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'email' => 'test@my.cspc.edu.ph',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'name' => 'Test User',
            'department' => 'CSPC Student',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Check if user already exists
        $builder = $this->db->table('users');
        $existingUser = $builder->where('email', $data['email'])->get()->getRow();
        
        if (!$existingUser) {
            $builder->insert($data);
            echo "Test user created: test@my.cspc.edu.ph with password: password123\n";
        } else {
            // Update existing user with password
            $builder->where('email', $data['email'])->update([
                'password' => $data['password'],
                'updated_at' => $data['updated_at']
            ]);
            echo "Test user updated: test@my.cspc.edu.ph with password: password123\n";
        }
    }
}