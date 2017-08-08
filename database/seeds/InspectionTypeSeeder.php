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
            'type' => 'Lights',
            'isActive' => 1
        ]);
        
        DB::table('inspection_type')->insert([
            'type' => 'Tires',
            'isActive' => 1
        ]);
    }
}
