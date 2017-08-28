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
            'email' => '',
            'image' => 'pics/20170828054534.jpg',
            'isActive' => 1,
        ]);
        DB::table('technician')->insert([
            'firstName' => 'Mariella',
            'middleName' => 'Reyes',
            'lastName' => 'Capispisan',
            'street' => 'Blk. 9 Lot 3 Tagupo St.',
            'brgy' => 'Brgy. Tatalon',
            'city' => 'Quezon City',
            'birthdate' => '1998-02-23',
            'contact' => '+63 926 9243 001',
            'email' => '',
            'image' => 'pics/20170828054856.jpg',
            'isActive' => 1,
        ]);
        DB::table('technician')->insert([
            'firstName' => 'Khen Khen',
            'middleName' => 'Barrera',
            'lastName' => 'Gaviola',
            'street' => '01 Apple St.',
            'brgy' => 'Welfarevill Compound',
            'city' => 'Mandaluyong City',
            'birthdate' => '1998-03-25',
            'contact' => '+63 916 3073 315',
            'email' => '',
            'image' => 'pics/20170828055300.jpg',
            'isActive' => 1,
        ]);
        DB::table('technician')->insert([
            'firstName' => 'Jefferson Van',
            'middleName' => 'Lao',
            'lastName' => 'Lapuz',
            'street' => '2844 Aurora Blvd.',
            'brgy' => 'Brgy. Di Mamahalin',
            'city' => 'Manila',
            'birthdate' => '1997-09-09',
            'contact' => '+63 905 8883 169',
            'email' => '',
            'image' => 'pics/20170828055612.jpg',
            'isActive' => 1,
        ]);
        DB::table('technician')->insert([
            'firstName' => 'Alexandra Corrine',
            'middleName' => 'Nabu-ab',
            'lastName' => 'Alcantara',
            'street' => '11-A, A.Bonifacio St.',
            'brgy' => 'Brgy. Hagdan Bato Libis',
            'city' => 'Quezon City',
            'birthdate' => '1998-06-27',
            'contact' => '+63 915 6439 450',
            'email' => '',
            'image' => 'pics/20170828055804.jpg',
            'isActive' => 1,
        ]);
        DB::table('technician')->insert([
            'firstName' => 'Aeron Paul',
            'middleName' => '',
            'lastName' => 'Bunag',
            'street' => 'Bldg. 69',
            'brgy' => 'LRT 2',
            'city' => 'Walter Mart',
            'birthdate' => '1999-08-27',
            'contact' => '+63 995 4794 505',
            'email' => '',
            'image' => 'pics/20170828060354.jpg',
            'isActive' => 1,
        ]);
    }
}
