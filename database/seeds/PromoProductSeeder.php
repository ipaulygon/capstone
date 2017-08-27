<?php

use Illuminate\Database\Seeder;

class PromoProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('promo_product')->insert([
            'promoId' => 1,
            'productId' => 2,
            'quantity' => 1,
            'freeQuantity' => 1,
            'isActive' => 1,
        ]);
    }
}
