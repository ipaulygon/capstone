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
        DB::table('technician_skill')->insert([
            'technicianId' => 1,
            'categoryId' => 2,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 1,
            'categoryId' => 3,
        ]);

        DB::table('technician_skill')->insert([
            'technicianId' => 2,
            'categoryId' => 1,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 2,
            'categoryId' => 2,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 2,
            'categoryId' => 3,
        ]);

        DB::table('technician_skill')->insert([
            'technicianId' => 3,
            'categoryId' => 1,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 3,
            'categoryId' => 2,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 3,
            'categoryId' => 3,
        ]);

        DB::table('technician_skill')->insert([
            'technicianId' => 4,
            'categoryId' => 1,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 4,
            'categoryId' => 2,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 4,
            'categoryId' => 3,
        ]);

        DB::table('technician_skill')->insert([
            'technicianId' => 5,
            'categoryId' => 1,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 5,
            'categoryId' => 2,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 5,
            'categoryId' => 3,
        ]);

        DB::table('technician_skill')->insert([
            'technicianId' => 6,
            'categoryId' => 1,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 6,
            'categoryId' => 2,
        ]);
        DB::table('technician_skill')->insert([
            'technicianId' => 6,
            'categoryId' => 3,
        ]);
    }
}
