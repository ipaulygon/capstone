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
            'isActive' => 1,
        ]);
        DB::table('product_type')->insert([
            'name' => 'Fuel',
            'isActive' => 1,
        ]);
        DB::table('product_type')->insert([
            'name' => 'Fan Belt',
            'isActive' => 1,
        ]);
    }
}
