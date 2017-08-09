<?php

use Illuminate\Database\Seeder;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rack')->insert([
            'name' => 'Rack 1',
            'description' => '',
            'isActive' => 1
        ]);
        DB::table('rack')->insert([
            'name' => 'Rack 2',
            'description' => '',
            'isActive' => 1
        ]);
    }
}
