<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUsersTableStructure extends Migration
{
    public function up()
    {
        // First, let's modify the existing users table structure
        
        // Add new columns
        $this->forge->addColumn('users', [
            'google_id' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'unique' => true,
                'after' => 'email'
            ],
            'display_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'google_id'
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'after' => 'display_name'
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
                'after' => 'first_name'
            ],
            'profile_picture_url' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'last_name'
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => 'active',
                'after' => 'department'
            ],
            'email_verified' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'status'
            ],
            'two_factor_enabled' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'email_verified'
            ],
            'last_login_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'after' => 'two_factor_enabled'
            ]
        ]);
        
        // Modify existing columns
        $this->forge->modifyColumn('users', [
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
                'unique' => true
            ],
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ]
        ]);
        
        // Note: For UUID support, we'll handle this in the application layer
        // since MySQL doesn't have native UUID support like PostgreSQL
        // The id field will remain as auto-increment for now, but we can add
        // a uuid field if needed in the future
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('users', [
            'google_id',
            'display_name', 
            'first_name',
            'last_name',
            'profile_picture_url',
            'status',
            'email_verified',
            'two_factor_enabled',
            'last_login_at'
        ]);
        
        // Revert email column changes
        $this->forge->modifyColumn('users', [
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => false
            ],
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ]
        ]);
    }
}