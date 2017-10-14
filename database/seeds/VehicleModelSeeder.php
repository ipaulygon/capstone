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
            'name' => 'Corolla 1998',
            'makeId' => 1,
            'hasAuto' => 1,
            'hasManual' => 1,
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Wigo 2010',
            'makeId' => 1,
            'hasAuto' => 1,
            'hasManual' => 0,
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Vios 2012',
            'makeId' => 1,
            'hasAuto' => 1,
            'hasManual' => 1,
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Vios 2010',
            'makeId' => 1,
            'hasAuto' => 1,
            'hasManual' => 1,
            'isActive' => 1,
        ]);
    }
}
