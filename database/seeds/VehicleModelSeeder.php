<?php

use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicle_model')->insert([
            'name' => 'Corolla',
            'makeId' => 1,
            'year' => '1998',
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Camry',
            'makeId' => 1,
            'year' => '2000',
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Hi-Ace',
            'makeId' => 1,
            'year' => '2016',
            'isActive' => 1,
        ]);
    }
}
