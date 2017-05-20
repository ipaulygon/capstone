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
            'isActive' => 1,
        ]);
    }
}
