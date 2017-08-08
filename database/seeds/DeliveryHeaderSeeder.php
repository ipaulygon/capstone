<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class DeliveryHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('delivery_header')->insert([
            'id' => 'DELIVERY00001',
            'supplierId' => 1,
            'dateMake' => '2017-08-06',
            'isActive' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
