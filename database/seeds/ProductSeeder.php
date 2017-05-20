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
            'name' => 'FB-6PK110',
            'description' => '',
            'price' => 1000.25,
            'reorder' => 10,
            'typeId' => 3,
            'brandId' => 3,
            'varianceId' => 2,
            'isOriginal' => 'Original',
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
            'isOriginal' => NULL,
            'isActive' => 1,
        ]);
    }
}
