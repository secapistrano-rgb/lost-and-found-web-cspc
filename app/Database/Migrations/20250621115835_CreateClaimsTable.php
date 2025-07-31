<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClaimsTable extends Migration
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
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'claimant_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'claimant_contact' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'claimant_email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'claim_description' => [
                'type' => 'TEXT',
            ],
            'proof_of_ownership' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default' => 'pending',
            ],
            'resolution_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('item_id', 'items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('claims');
    }

    public function down()
    {
        $this->forge->dropTable('claims');
    }
}
