<?php

use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('discount')->insert([
            'name' => 'Summer Sale',
            'rate' => 10.00,
            'isWhole' => 0,
            'isVatExempt' => 0,
            'isActive' => 1,
        ]);
        DB::table('discount')->insert([
            'name' => 'Senior Citizen',
            'rate' => 20.00,
            'isWhole' => 1,
            'isVatExempt' => 1,
            'isActive' => 1,
        ]);
    }
}
