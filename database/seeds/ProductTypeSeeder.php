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
            'category' => 'Supplies',
            'isActive' => 1,
        ]);
        DB::table('product_type')->insert([
            'name' => 'Fuel',
            'category' => 'Supplies',
            'isActive' => 1,
        ]);
        DB::table('product_type')->insert([
            'name' => 'Fan Belt',
            'category' => 'Parts',
            'isActive' => 1,
        ]);
    }
}
