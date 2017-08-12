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
            'completed' => 0,
            'isActive' => 1,
            'isComplete' => 0
        ]);
    }
}
