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
            'isActive' => 1
        ]);
        DB::table('product_unit')->insert([
            'name' => 'cm',
            'description' => 'Centimeters',
            'isActive' => 1
        ]);
        DB::table('product_unit')->insert([
            'name' => 'm',
            'description' => 'Meters',
            'isActive' => 1
        ]);
        DB::table('product_unit')->insert([
            'name' => 'mL',
            'description' => 'Milliliter',
            'isActive' => 1
        ]);
        DB::table('product_unit')->insert([
            'name' => 'L',
            'description' => 'L',
            'isActive' => 1
        ]);
        DB::table('product_unit')->insert([
            'name' => 'Ribs',
            'description' => 'Ribs',
            'isActive' => 1
        ]);
    }
}
