<?php

use Illuminate\Database\Seeder;

class ProductVarianceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_variance')->insert([
            'name' => '500 mL',
            'size' => '500',
            'units' => '4',
            'isActive' => 1
        ]);
        DB::table('product_variance')->insert([
            'name' => '6PK1110',
            'size' => '1110,6',
            'units' => '1,6',
            'isActive' => 1
        ]);
        DB::table('product_variance')->insert([
            'name' => '4500 watts',
            'size' => '4500',
            'units' => '8',
            'isActive' => 1
        ]);
    }
}
