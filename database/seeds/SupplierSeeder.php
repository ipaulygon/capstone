<?php

use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('supplier')->insert([
            'name' => 'Kwikparts',
            'address' => 'San Juan City',
            'isActive' => 1,
        ]);
    }
}
