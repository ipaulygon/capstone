<?php

use Illuminate\Database\Seeder;

class PackagePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('package_price')->insert([
            'packageId' => 1,
    	    'price'	=> 500.00,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
