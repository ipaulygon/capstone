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
            'isComplete' => 0,
            'total' => 6217.18,
            'paid' => 0.00,
            'start' => Carbon::now(),
            'end' => null,
            'release' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
