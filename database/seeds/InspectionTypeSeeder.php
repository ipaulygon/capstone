<?php

use Illuminate\Database\Seeder;

class InspectionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inspection_type')->insert([
            'name' => 'Tires',
            'description' => '',
            'isActive' => 1
        ]);
    }
}
