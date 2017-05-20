<?php

use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('promo')->insert([
            'name' => 'Summer Promo',
            'price' => 800.00,
            'dateStart' => date('Y-m-d'),
            'dateEnd' => date('Y-m-d'),
            'stock' => null,
            'isActive' => 1,
        ]);
    }
}
