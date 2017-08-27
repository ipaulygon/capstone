<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

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
            'dateStart' => Carbon::now(),
            'dateEnd' => Carbon::now(),
            'stock' => null,
            'isActive' => 1,
        ]);
    }
}
