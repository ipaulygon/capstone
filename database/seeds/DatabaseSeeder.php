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
        $this->call(UtilitySeeder::class);
        $this->call(VehicleMakeSeeder::class);
        $this->call(VehicleModelSeeder::class);
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
        $this->call(ProductPriceSeeder::class);
        $this->call(ProductVehicleSeeder::class);
        $this->call(InventorySeeder::class);
        $this->call(ServiceCategorySeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ServicePriceSeeder::class);
        $this->call(InspectionTypeSeeder::class);
        $this->call(InspectionItemSeeder::class);
        $this->call(RackSeeder::class);
        $this->call(TechnicianSeeder::class);
        $this->call(TechnicianSkillSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(PackagePriceSeeder::class);
        $this->call(PackageProductSeeder::class);
        $this->call(PackageServiceSeeder::class);
        $this->call(PromoSeeder::class);
        $this->call(PromoPriceSeeder::class);
        $this->call(PromoProductSeeder::class);
        $this->call(PromoServiceSeeder::class);
        $this->call(DiscountSeeder::class);
        $this->call(DiscountRateSeeder::class);
        $this->call(DiscountProductSeeder::class);
        $this->call(DiscountServiceSeeder::class);
        $this->call(PurchaseHeaderSeeder::class);
        $this->call(PurchaseDetailSeeder::class);
        $this->call(DeliveryHeaderSeeder::class);
        $this->call(DeliveryDetailSeeder::class);
        $this->call(DeliveryOrderSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(VehicleSeeder::class);
        $this->call(JobHeaderSeeder::class);
        $this->call(JobProductSeeder::class);
        $this->call(JobServiceSeeder::class);
        $this->call(JobPackageSeeder::class);
        $this->call(JobDiscountSeeder::class);
        $this->call(JobTechnicianSeeder::class);
        $this->call(SalesHeaderSeeder::class);
        $this->call(SalesProductSeeder::class);
    }
}
