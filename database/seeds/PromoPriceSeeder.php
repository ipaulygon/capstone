<?php

use Illuminate\Database\Seeder;

class PromoPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('promo_price')->insert([
            'promoId' => 1,
    	    'price'	=> 800.00,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
