<?php

use Illuminate\Database\Seeder;

class JobDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_discount')->insert([
            'jobId' => 1,
            'discountId' => 2,
            'isActive' => 1
        ]);
    }
}
