<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordToAdminRole extends Migration
{
    public function up()
    {
        $this->forge->addColumn('admin_role', [
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'role'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('admin_role', 'password');
    }
}