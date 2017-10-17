<?php

use Illuminate\Database\Seeder;

class TypeVarianceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_variance')->insert([
            'typeId' => 1,
            'varianceId' => 1,
        ]);
        DB::table('type_variance')->insert([
            'typeId' => 2,
            'varianceId' => 1,
        ]);
        DB::table('type_variance')->insert([
            'typeId' => 3,
            'varianceId' => 2,
        ]);
        DB::table('type_variance')->insert([
            'typeId' => 4,
            'varianceId' => 3,
        ]);
    }
}
