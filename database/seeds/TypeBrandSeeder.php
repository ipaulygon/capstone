<?php

use Illuminate\Database\Seeder;

class TypeBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_brand')->insert([
            'typeId' => 1,
            'brandId' => 1,
        ]);
        DB::table('type_brand')->insert([
            'typeId' => 1,
            'brandId' => 2,
        ]);
        DB::table('type_brand')->insert([
            'typeId' => 2,
            'brandId' => 1,
        ]);
        DB::table('type_brand')->insert([
            'typeId' => 2,
            'brandId' => 2,
        ]);
        DB::table('type_brand')->insert([
            'typeId' => 3,
            'brandId' => 3,
        ]);
        DB::table('type_brand')->insert([
            'typeId' => 3,
            'brandId' => 4,
        ]);
    }
}
