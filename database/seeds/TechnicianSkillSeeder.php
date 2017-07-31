<?php

use Illuminate\Database\Seeder;

class TechnicianSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('technician_skill')->insert([
            'technicianId' => 1,
            'categoryId' => 1,
        ]);
    }
}
