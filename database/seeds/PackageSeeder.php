<?php

use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('package')->insert([
            'name' => 'Summer Package',
            'price' => 500.00,
            'isActive' => 1,
        ]);

        DB::table('package')->insert([
            'name' => 'Change Oil Package',
            'price' => 700.00,
            'isActive' => 1,
        ]);
    }
}
