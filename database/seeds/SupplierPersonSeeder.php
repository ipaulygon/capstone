<?php

use Illuminate\Database\Seeder;

class SupplierPersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('supplier_person')->insert([
            'spId' => '1',
            'spName' => 'Paul Cruz',
            'spContact' => '+63 905 4090 523',
            'isMain' => 1
        ]);
    }
}
