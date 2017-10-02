<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class DeliveryDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('delivery_detail')->insert([
            'deliveryId' => 'DELIVERY00001',
            'productId' => 1,
            'quantity' => 10,
            'isActive' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('inventory')->where('productId',1)->increment('quantity',10);
        DB::table('delivery_detail')->insert([
            'deliveryId' => 'DELIVERY00001',
            'productId' => 2,
            'quantity' => 10,
            'isActive' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('inventory')->where('productId',2)->increment('quantity',10);
    }
}
