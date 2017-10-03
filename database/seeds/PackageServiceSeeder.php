<?php

use Illuminate\Database\Seeder;

class PackageServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('package_service')->insert([
            'packageId' => 2,
            'serviceId' => 1,
            'isActive' => 1,
        ]);
    }
}
