<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateExistingUserSeeder extends Seeder
{
    public function run()
    {
        // Update Sean's account with a password
        $builder = $this->db->table('users');
        
        $builder->where('email', 'secapistrano@my.cspc.edu.ph')
                ->update([
                    'password' => password_hash('password123', PASSWORD_DEFAULT),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        
        echo "Updated secapistrano@my.cspc.edu.ph with password: password123\n";
        
        // Update Christian's account with a password
        $builder->where('email', 'chrgonzaga@my.cspc.edu.ph')
                ->update([
                    'password' => password_hash('password123', PASSWORD_DEFAULT),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        
        echo "Updated chrgonzaga@my.cspc.edu.ph with password: password123\n";
    }
}