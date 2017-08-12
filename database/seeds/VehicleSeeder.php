<?php

use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicle')->insert([
            'plate' => 'DPG 235',
            'modelId' => 2,
            'isManual' => 0,
            'mileage' => 1000.00
        ]);
    }
}
