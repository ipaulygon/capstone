<?php

use Illuminate\Database\Seeder;

class DiscountProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('discount_product')->insert([
            'discountId' => 1,
            'productId' => 2,
            'isActive' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
