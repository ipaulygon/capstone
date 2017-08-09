<?php

use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service_category')->insert([
            'name' => 'Maintenance',
            'description' => '',
            'isActive' => 1
        ]);
        DB::table('service_category')->insert([
            'name' => 'Suspension',
            'description' => '',
            'isActive' => 1
        ]);
        DB::table('service_category')->insert([
            'name' => 'Aircon',
            'description' => '',
            'isActive' => 1
        ]);
    }
}
