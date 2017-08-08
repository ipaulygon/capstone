<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class DeliveryOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('delivery_order')->insert([
            'deliveryId' => 'DELIVERY00001',
            'purchaseId' => 'ORDER00001',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
