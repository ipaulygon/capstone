<?php

use Illuminate\Database\Seeder;

class InspectionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inspection_item')->insert([
            'name' => 'Front left',
            'typeId' => 1,
            'isActive' => 1
        ]);
        DB::table('inspection_item')->insert([
            'name' => 'Front right',
            'typeId' => 1,
            'isActive' => 1
        ]);
        DB::table('inspection_item')->insert([
            'name' => 'Rear left',
            'typeId' => 1,
            'isActive' => 1
        ]);
        DB::table('inspection_item')->insert([
            'name' => 'Rear right',
            'typeId' => 1,
            'isActive' => 1
        ]);
    }
}
