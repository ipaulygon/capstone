<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon as Carbon;

class ServicePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_price')->insert([
            'serviceId' => 1,
            'price' => 300.25,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('service_price')->insert([
            'serviceId' => 2,
            'price' => 500,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('service_price')->insert([
            'serviceId' => 3,
            'price' => 600,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('service_price')->insert([
            'serviceId' => 4,
            'price' => 400.50,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('service_price')->insert([
            'serviceId' => 5,
            'price' => 600.25,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('service_price')->insert([
            'serviceId' => 6,
            'price' => 400.25,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('service_price')->insert([
            'serviceId' => 7,
            'price' => 525.25,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
