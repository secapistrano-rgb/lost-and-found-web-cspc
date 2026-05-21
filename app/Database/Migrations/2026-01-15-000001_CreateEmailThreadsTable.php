<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailThreadsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'message_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Email Message-ID header for tracking replies'
            ],
            'thread_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Email thread ID for grouping conversations'
            ],
            'to_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Recipient email address'
            ],
            'from_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Sender email address'
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'entity_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'comment' => 'item, claim, match, etc.'
            ],
            'entity_id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'has_reply' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '1 if user has replied to this email'
            ],
            'last_reply_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('message_id');
        $this->forge->addKey('thread_id');
        $this->forge->addKey('to_email');
        $this->forge->addKey(['entity_type', 'entity_id']);
        
        $this->forge->createTable('email_threads');
    }

    public function down()
    {
        $this->forge->dropTable('email_threads');
    }
}
