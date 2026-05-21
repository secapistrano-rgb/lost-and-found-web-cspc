<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordToUsers extends Migration
{
    public function up()
    {
        // Add password field
        $this->forge->addColumn('users', [
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'email'
            ]
        ]);
        
        // Remove Google-related fields
        $this->forge->dropColumn('users', ['google_id', 'profileImage']);
    }

    public function down()
    {
        // Remove password field
        $this->forge->dropColumn('users', 'password');
        
        // Add back Google-related fields
        $this->forge->addColumn('users', [
            'google_id' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'unique' => true,
                'after' => 'email'
            ],
            'profileImage' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'name'
            ]
        ]);
    }
}