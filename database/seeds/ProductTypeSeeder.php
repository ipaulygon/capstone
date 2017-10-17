<?php

use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_type')->insert([
            'name' => 'Oil',
            'category' => 'category2',
            'isActive' => 1,
        ]);
        DB::table('product_type')->insert([
            'name' => 'Fuel',
            'category' => 'category2',
            'isActive' => 1,
        ]);
        DB::table('product_type')->insert([
            'name' => 'Fan Belt',
            'category' => 'category1',
            'isActive' => 1,
        ]);
        DB::table('product_type')->insert([
            'name' => 'Batteries/Electrical',
            'category' => 'category1',
            'isActive' => 1,
        ]);
        DB::table('product_type')->insert([
            'name' => 'Tires',
            'category' => 'category1',
            'isActive' => 1,
        ]);
        DB::table('product_type')->insert([
            'name' => 'Brakes',
            'category' => 'category1',
            'isActive' => 1,
        ]);
    }
}
