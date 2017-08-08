<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class PurchaseHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('purchase_header')->insert([
            'id' => 'ORDER00001',
            'supplierId' => 1,
            'remarks' => '',
            'dateMake' => '2017-08-06',
            'isActive' => 1,
            'isFinalize' => 1,
            'isDelivered' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
