<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAttemptsToEmailLogs extends Migration
{
    public function up()
    {
        // Add attempts column to email_logs table
        $this->forge->addColumn('email_logs', [
            'attempts' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
                'null' => false,
                'after' => 'error_message'
            ]
        ]);
    }

    public function down()
    {
        // Remove attempts column from email_logs table
        $this->forge->dropColumn('email_logs', 'attempts');
    }
}