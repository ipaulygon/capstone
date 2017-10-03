<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class JobHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_header')->insert([
            'customerId' => 1,
            'vehicleId' => 1,
            'rackId' => 1,
            'isFinalize' => 1,
            'isComplete' => 1,
            'total' => 5551.06,
            'paid' => 5551.06,
            'start' => Carbon::now(),
            'end' => Carbon::now(),
            'release' => Carbon::now(),
            'remarks' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('job_header')->insert([
            'customerId' => 2,
            'vehicleId' => 1,
            'rackId' => 1,
            'isFinalize' => 1,
            'isComplete' => 1,
            'total' => 1970.48,
            'paid' => 1970.48,
            'start' => Carbon::now(),
            'end' => Carbon::now(),
            'release' => Carbon::now(),
            'remarks' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
