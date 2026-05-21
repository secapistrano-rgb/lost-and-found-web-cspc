<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixDateColumn extends Migration
{
    public function up()
    {
        // First drop the existing date column if it exists
        if ($this->db->fieldExists('date', 'items')) {
            $this->forge->dropColumn('items', 'date');
        }

        // Add the date column with proper settings
        $this->forge->addColumn('items', [
            'date' => [
                'type'       => 'DATE',
                'null'       => false,
                'default'    => date('Y-m-d'),
                'after'      => 'category'
            ]
        ]);

        // Update existing records to set the date to today
        $this->db->query("UPDATE items SET date = CURRENT_DATE");
    }

    public function down()
    {
        $this->forge->dropColumn('items', 'date');
    }
}
