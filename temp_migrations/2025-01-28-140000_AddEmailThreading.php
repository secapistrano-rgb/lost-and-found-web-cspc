<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailThreading extends Migration
{
    public function up()
    {
        // Add threading columns to email_logs table
        $this->forge->addColumn('email_logs', [
            'thread_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'id'
            ],
            'parent_email_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'thread_id'
            ],
            'is_reply' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'parent_email_id'
            ],
            'reply_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'after' => 'is_reply'
            ]
        ]);

        // Create email_replies table for incoming replies
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'original_email_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'thread_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'from_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'from_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'message_content' => [
                'type' => 'TEXT',
            ],
            'raw_email' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'is_read' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('original_email_id');
        $this->forge->addKey('thread_id');
        $this->forge->createTable('email_replies');
    }

    public function down()
    {
        $this->forge->dropColumn('email_logs', ['thread_id', 'parent_email_id', 'is_reply', 'reply_count']);
        $this->forge->dropTable('email_replies');
    }
}