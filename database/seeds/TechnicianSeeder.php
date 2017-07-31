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
            'street' => '521 D.Santiago St.',
            'brgy' => 'Brgy. Pedro Cruz',
            'city' => 'San Juan City',
            'birthdate' => '1999-01-28',
            'contact' => '+63 905 4090 523',
            'email' => 'paulandrei@ymail.com',
            'image' => 'pics/steve.jpg',
            'username' => 'cruz1',
            'password' => bcrypt('password'),
            'isActive' => 1,
        ]);
    }
}
