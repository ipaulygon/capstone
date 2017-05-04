<?php

use Illuminate\Database\Seeder;

class ProductBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_brand')->insert([
            'name' => 'Petron',
            'isActive' => 1,
        ]);
        DB::table('product_brand')->insert([
            'name' => 'Shell',
            'isActive' => 1,
        ]);
        DB::table('product_brand')->insert([
            'name' => 'Bando',
            'isActive' => 1,
        ]);
        DB::table('product_brand')->insert([
            'name' => 'Gates',
            'isActive' => 1,
        ]);
    }
}
