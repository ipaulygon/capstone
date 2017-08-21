<?php

use Illuminate\Database\Seeder;

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
            'isComplete' => 0,
            'isVoid' => 0,
        ]);
    }
}
