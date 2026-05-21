<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSuspensionFieldsToUsers extends Migration
{
    public function up()
    {
        // Add suspension-related columns to users table
        $this->forge->addColumn('users', [
            'suspended_until' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'after' => 'status'
            ],
            'suspension_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'suspended_until'
            ],
            'suspended_by' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'suspension_reason'
            ]
        ]);
    }

    public function down()
    {
        // Remove the added columns
        $this->forge->dropColumn('users', [
            'suspended_until',
            'suspension_reason', 
            'suspended_by'
        ]);
    }
}