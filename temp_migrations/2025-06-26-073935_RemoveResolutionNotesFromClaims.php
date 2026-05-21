<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveResolutionNotesFromClaims extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('claims', 'resolution_notes');
    }

    public function down()
    {
        $fields = [
            'resolution_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];
        $this->forge->addColumn('claims', $fields);
    }
}
