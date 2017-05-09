<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(SupplierPersonSeeder::class);
        $this->call(SupplierContactSeeder::class);
        $this->call(ProductTypeSeeder::class);
        $this->call(ProductBrandSeeder::class);
        $this->call(TypeBrandSeeder::class);
        $this->call(ProductUnitSeeder::class);
        $this->call(ProductVarianceSeeder::class);
        $this->call(TypeVarianceSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ServiceCategorySeeder::class);
        $this->call(ServiceSeeder::class);
        // $this->call(InspectionTypeSeeder::class);
        // $this->call(InspectionItemSeeder::class);
    }
}
