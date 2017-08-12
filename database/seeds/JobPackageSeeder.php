<?php

use Illuminate\Database\Seeder;

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
            'completed' => 0,
            'isActive' => 1,
            'isComplete' => 0
        ]);
    }
}
