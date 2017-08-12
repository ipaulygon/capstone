<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class JobTechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_technician')->insert([
            'jobId' => 1,
            'technicianId' => 1,
            'isActive' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
