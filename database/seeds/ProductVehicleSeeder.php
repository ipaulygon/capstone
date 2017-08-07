<?php

use Illuminate\Database\Seeder;

class ProductVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_vehicle')->insert([
            'productId' => 1,
            'modelId' => 1,
            'isManual' => 0,
            'isActive' => 1,
        ]);
        DB::table('product_vehicle')->insert([
            'productId' => 1,
            'modelId' => 2,
            'isManual' => 0,
            'isActive' => 1,
        ]);
        DB::table('product_vehicle')->insert([
            'productId' => 1,
            'modelId' => 3,
            'isManual' => 0,
            'isActive' => 1,
        ]);
    }
}
