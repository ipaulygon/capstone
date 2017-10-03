<?php

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('service')->insert([
            'name' => 'Change Oil',
            'price' => 300.25,
            'size' => 'Sedan',
            'categoryId' => 1,
            'year' => 1,
            'month' => 0,
            'day' => 0,
            'isActive' => 1
        ]);
    }
}
