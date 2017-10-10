<?php

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service')->insert([
            'name' => 'Change Oil',
            'price' => 300.25,
            'size' => 'Sedan',
            'categoryId' => 1,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1
        ]);
        DB::table('service')->insert([
            'name' => 'Change Oil',
            'price' => 500,
            'size' => 'Large',
            'categoryId' => 1,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1
        ]);
        DB::table('service')->insert([
            'name' => 'Adjust Timing',
            'price' => 600,
            'size' => 'Large',
            'categoryId' => 1,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1
        ]);
        DB::table('service')->insert([
            'name' => 'Replace Bell Crank',
            'price' => 400.50,
            'size' => 'Sedan',
            'categoryId' => 2,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1
        ]);
        DB::table('service')->insert([
            'name' => 'Replace Shock Absorber Bushing',
            'price' => 600.25,
            'size' => 'Sedan',
            'categoryId' => 2,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1
        ]);
        DB::table('service')->insert([
            'name' => 'Replace Compressor',
            'price' => 400.25,
            'size' => 'Sedan',
            'categoryId' => 3,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1
        ]);
        DB::table('service')->insert([
            'name' => 'Replace Compressor',
            'price' => 525.25,
            'size' => 'Large',
            'categoryId' => 3,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1
        ]);
    }
}
