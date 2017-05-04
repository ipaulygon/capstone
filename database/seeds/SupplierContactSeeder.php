<?php

use Illuminate\Database\Seeder;

class SupplierContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('supplier_contact')->insert([
            'scId' => '1',
            'scNo' => '09054090523',
        ]);
    }
}
