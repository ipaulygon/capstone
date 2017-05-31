<?php

use Illuminate\Database\Seeder;

class DiscountRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('discount_rate')->insert([
            'discountId' => 1,
            'rate' => 10,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('discount_rate')->insert([
            'discountId' => 2,
            'rate' => 20,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
