<?php

use Illuminate\Database\Seeder;

class PackageProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('package_product')->insert([
            'packageId' => 1,
            'productId' => 2,
            'quantity' => 2,
            'isActive' => 1,
        ]);
        
        DB::table('package_product')->insert([
            'packageId' => 2,
            'productId' => 2,
            'quantity' => 1,
            'isActive' => 1,
        ]);
    }
}
