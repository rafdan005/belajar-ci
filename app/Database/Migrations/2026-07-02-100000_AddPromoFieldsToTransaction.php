<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPromoFieldsToTransaction extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transaction', [
            'biaya_jasa' => [
                'type'       => 'DOUBLE',
                'null'       => true,
                'after'      => 'ongkir',
            ],
            'voucher_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'biaya_jasa',
            ],
            'diskon_voucher' => [
                'type'       => 'DOUBLE',
                'null'       => true,
                'after'      => 'voucher_code',
            ],
            'free_mouse' => [
                'type'       => 'DOUBLE',
                'null'       => true,
                'after'      => 'diskon_voucher',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', [
            'biaya_jasa',
            'voucher_code',
            'diskon_voucher',
            'free_mouse',
        ]);
    }
}
