<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImageToItems extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('image', 'items')) {
            $this->forge->addColumn('items', [
                'image' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'found_date'
                ]
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('image', 'items')) {
            $this->forge->dropColumn('items', 'image');
        }
    }
}
