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
            'hasAuto' => 1,
            'hasManual' => 1,
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Wigo',
            'makeId' => 1,
            'year' => '2010',
            'hasAuto' => 1,
            'hasManual' => 0,
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Vios',
            'makeId' => 1,
            'year' => '2012',
            'hasAuto' => 1,
            'hasManual' => 1,
            'isActive' => 1,
        ]);
    }
}
