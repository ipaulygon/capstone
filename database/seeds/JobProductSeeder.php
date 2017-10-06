<?php

use Illuminate\Database\Seeder;

class JobProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_product')->insert([
            'jobId' => 1,
            'productId' => 1,
            'quantity' => 5,
            'completed' => 5,
            'isActive' => 1,
            'isComplete' => 1,
            'isVoid' => 0,
        ]);
        DB::table('inventory')->where('productId',1)->decrement('quantity',5);        
        DB::table('job_product')->insert([
            'jobId' => 2,
            'productId' => 1,
            'quantity' => 1,
            'completed' => 1,
            'isActive' => 1,
            'isComplete' => 1,
            'isVoid' => 0,
        ]);
        DB::table('inventory')->where('productId',1)->decrement('quantity',1);  
    }
}
