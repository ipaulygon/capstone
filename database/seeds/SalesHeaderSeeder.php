<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class SalesHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sales_header')->insert([
            'customerId' => 2,
            'total' => 2702.25,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
