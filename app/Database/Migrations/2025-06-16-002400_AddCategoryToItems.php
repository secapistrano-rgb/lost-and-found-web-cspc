<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryToItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('items', [
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'after'      => 'name'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('items', 'category');
    }
}
