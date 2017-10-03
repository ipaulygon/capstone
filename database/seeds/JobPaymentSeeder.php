<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class JobPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_payment')->insert([
            'jobId' => 1,
            'paid' => 5551.06,
            'creditCard' => bcrypt(''),
            'isCredit' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        DB::table('job_payment')->insert([
            'jobId' => 2,
            'paid' => 1970.48,
            'creditCard' => bcrypt(''),
            'isCredit' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
