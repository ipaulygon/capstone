<?php

use Illuminate\Database\Seeder;

class VehicleMakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicle_make')->insert([
            'name' => 'Toyota',
            'isActive' => 1,
        ]);
    }
}
