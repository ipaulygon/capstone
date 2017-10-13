<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class JobPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_package')->insert([
            'jobId' => 1,
            'packageId' => 1,
            'quantity' => 5,
            'completed' => 5,
            'isActive' => 1,
            'isComplete' => 1,
            'isVoid' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('inventory')->where('productId',2)->decrement('quantity',10);  
        DB::table('job_package')->insert([
            'jobId' => 2,
            'packageId' => 2,
            'quantity' => 1,
            'completed' => 1,
            'isActive' => 1,
            'isComplete' => 1,
            'isVoid' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('inventory')->where('productId',2)->decrement('quantity',1);
    }
}
