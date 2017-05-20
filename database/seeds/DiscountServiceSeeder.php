<?php

use Illuminate\Database\Seeder;

class DiscountServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('discount_service')->insert([
            'discountId' => 1,
            'serviceId' => 1,
            'isActive' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
