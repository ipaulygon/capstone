<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product')->insert([
            'name' => 'FB-6PK1110',
            'description' => '',
            'price' => 1000.25,
            'reorder' => 10,
            'typeId' => 3,
            'brandId' => 3,
            'varianceId' => 2,
            'isOriginal' => 'type1',
            'isWarranty' => 1,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1,
        ]);
        DB::table('product')->insert([
            'name' => 'Ultron',
            'description' => '',
            'price' => 300.25,
            'reorder' => 10,
            'typeId' => 1,
            'brandId' => 1,
            'varianceId' => 1,
            'isOriginal' => null,
            'isWarranty' => 1,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1,
        ]);
        DB::table('product')->insert([
            'name' => 'Motolite 4500',
            'description' => '',
            'price' => 750.25,
            'reorder' => 10,
            'typeId' => 4,
            'brandId' => 5,
            'varianceId' => 3,
            'isOriginal' => 'type1',
            'isWarranty' => 1,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1,
        ]);
    }
}
