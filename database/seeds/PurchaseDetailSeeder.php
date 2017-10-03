<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class PurchaseDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('purchase_detail')->insert([
            'purchaseId' => 'ORDER00001',
            'productId' => 1,
            'modelId' => 2,
            'isManual' => 1,
            'quantity' => 10,
            'delivered' => 10,
            'price' => 900.00,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('purchase_detail')->insert([
            'purchaseId' => 'ORDER00001',
            'productId' => 2,
            'modelId' => null,
            'isManual' => null,
            'quantity' => 20,
            'delivered' => 10,
            'price' => 100.00,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
