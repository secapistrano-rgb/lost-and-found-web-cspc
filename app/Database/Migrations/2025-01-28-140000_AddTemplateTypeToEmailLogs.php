<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTemplateTypeToEmailLogs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('email_logs', [
            'template_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'body'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('email_logs', 'template_type');
    }
}