<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailToItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('items', [
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'description'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('items', 'email');
    }
}
