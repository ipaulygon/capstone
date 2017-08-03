<?php

use Illuminate\Database\Seeder;

class ProductUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('product_unit')->insert([
            'name' => 'mm',
            'description' => 'Millimeter',
            'category' => 1,
            'isActive' => 1
        ]);
        DB::table('product_unit')->insert([
            'name' => 'cm',
            'description' => 'Centimeters',
            'category' => 1,
            'isActive' => 1
        ]);
        DB::table('product_unit')->insert([
            'name' => 'm',
            'description' => 'Meters',
            'category' => 1,
            'isActive' => 1
        ]);
        DB::table('product_unit')->insert([
            'name' => 'mL',
            'description' => 'Milliliter',
            'category' => 2,
            'isActive' => 1
        ]);
        DB::table('product_unit')->insert([
            'name' => 'L',
            'description' => 'L',
            'category' => 2,
            'isActive' => 1
        ]);
        DB::table('product_unit')->insert([
            'name' => 'Ribs',
            'description' => 'Ribs',
            'category' => 1,
            'isActive' => 1
        ]);
    }
}
