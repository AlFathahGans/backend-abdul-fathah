<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLaundryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'item' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 5,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('laundry');
    }

    public function down()
    {
        $this->forge->dropTable('laundry');
    }
}
