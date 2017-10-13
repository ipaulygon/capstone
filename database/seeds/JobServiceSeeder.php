<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class JobServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_service')->insert([
            'jobId' => 1,
            'serviceId' => 1,
            'isActive' => 1,
            'isComplete' => 1,
            'isVoid' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('job_service')->insert([
            'jobId' => 2,
            'serviceId' => 1,
            'isActive' => 1,
            'isComplete' => 1,
            'isVoid' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
