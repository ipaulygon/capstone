<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

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
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('discount_rate')->insert([
            'discountId' => 2,
            'rate' => 20,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
