<?php

use Illuminate\Database\Seeder;

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
            'quantity' => 10,
            'isActive' => 1,
            'isVoid' => 0
        ]);
    }
}
