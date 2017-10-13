<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class SalesProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sales_product')->insert([
            'salesId' => 1,
            'productId' => 2,
            'quantity' => 5,
            'isActive' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('inventory')->where('productId',2)->decrement('quantity',5);
    }
}
