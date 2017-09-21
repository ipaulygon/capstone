<?php

use Illuminate\Database\Seeder;

class UtilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('utilities')->insert([
            'image' => 'pics/logo.png',
            'name' => 'iAyos',
            'address' => 'Anonas St., Sta. Mesa, Manila',
            'category1' => 'Parts',
            'category2' => 'Supplies',
            'type1' => 'Original',
            'type2' => 'Replacement',
            'max' => 100,
            'backlog' => 7,
            'isVat' => 1,
            'vat' => 12,
            'isWarranty' => 1,
            'year' => 1,
            'month' => 0,
            'day' => 0
        ]);
    }
}
