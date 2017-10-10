<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

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
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('package_price')->insert([
            'packageId' => 2,
    	    'price'	=> 700.00,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
