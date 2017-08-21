<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer')->insert([
            'firstName' => 'Madelyn',
            'middleName' => 'Pambid',
            'lastName' => 'Recio',
            'street' => '',
            'brgy' => '',
            'city' => 'San Juan City',
            'contact' => '+63 998 4123 460',
            'email' => null,
            'card' => null
        ]);
    }
}
