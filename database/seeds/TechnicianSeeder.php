<?php

use Illuminate\Database\Seeder;

class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('technician')->insert([
            'firstName' => 'Paul Andrei',
            'middleName' => 'Navarro',
            'lastName' => 'Cruz',
            'address' => 'San Juan City',
            'birthdate' => '1999-01-28',
            'contact' => '(+639)05-4090-523',
            'email' => 'paulandrei@ymail.com',
            'image' => 'pics/steve.jpg',
            'isActive' => 1,
        ]);
    }
}
