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
            'transmission' => 'AT',
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Camry',
            'makeId' => 1,
            'year' => '2000',
            'transmission' => 'AT',
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Hi-Ace',
            'makeId' => 1,
            'year' => '2016',
            'transmission' => 'AT',
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Corolla',
            'makeId' => 1,
            'year' => '1998',
            'transmission' => 'MT',
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Camry',
            'makeId' => 1,
            'year' => '2000',
            'transmission' => 'MT',
            'isActive' => 1,
        ]);
        DB::table('vehicle_model')->insert([
            'name' => 'Hi-Ace',
            'makeId' => 1,
            'year' => '2016',
            'transmission' => 'MT',
            'isActive' => 1,
        ]);
    }
}
