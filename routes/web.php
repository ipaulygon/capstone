<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
	Route::resource('dashboard', 'DashboardController');
	Route::resource('vehicle','VehicleController');
	Route::resource('supplier','SupplierController');
	Route::resource('type','ProductTypeController');
	Route::resource('brand','ProductBrandController');
	Route::resource('unit','ProductUnitController');
	Route::resource('variance','ProductVarianceController');
	Route::resource('product','ProductController');
	Route::resource('category','ServiceCategoryController');
	Route::resource('service','ServiceController');
	Route::resource('inspection','InspectionController');
	Route::resource('package','PackageController');
	Route::resource('promo','PromoController');
	Route::resource('discount','DiscountController');
	Route::resource('technician','TechnicianController');
	// Transactions
	Route::resource('purchase','PurchaseController');
	Route::patch('purchase/final/{id}','PurchaseController@finalize');
	Route::resource('delivery','DeliveryController');
	Route::resource('inspect','InspectController');
	Route::resource('estimate','EstimateController');

	//PDF
	Route::get('purchase/pdf/{id}','PdfController@purchase');
	Route::get('delivery/pdf/{id}','PdfController@delivery');

	//GetJSON
	Route::get('type/remove/{id}','ProductTypeController@remove');
	Route::get('product/type/{id}','ProductController@type');
	Route::get('package/product/{id}','PackageController@product');
	Route::get('package/service/{id}','PackageController@service');
	Route::get('promo/product/{id}','PromoController@product');
	Route::get('promo/service/{id}','PromoController@service');
	Route::get('purchase/product/{id}','PurchaseController@product');
	Route::get('delivery/header/{id}','DeliveryController@header');
	Route::get('delivery/detail/{id}','DeliveryController@detail');
	Route::get('inspect/customer/{name}','InspectController@customer');
	Route::get('inspect/vehicle/{name}','InspectController@vehicle');

	// Reactivate
	Route::patch('vehicle/reactivate/{id}','VehicleController@reactivate');
	Route::patch('supplier/reactivate/{id}','SupplierController@reactivate');
	Route::patch('type/reactivate/{id}','ProductTypeController@reactivate');
	Route::patch('brand/reactivate/{id}','ProductBrandController@reactivate');
	Route::patch('unit/reactivate/{id}','ProductUnitController@reactivate');
	Route::patch('variance/reactivate/{id}','ProductVarianceController@reactivate');
	Route::patch('product/reactivate/{id}','ProductController@reactivate');
	Route::patch('category/reactivate/{id}','ServiceCategoryController@reactivate');
	Route::patch('service/reactivate/{id}','ServiceController@reactivate');
	Route::patch('inspection/reactivate/{id}','InspectionController@reactivate');
	Route::patch('technician/reactivate/{id}','TechnicianController@reactivate');
	Route::patch('package/reactivate/{id}','PackageController@reactivate');
	Route::patch('promo/reactivate/{id}','PromoController@reactivate');
	Route::patch('discount/reactivate/{id}','DiscountController@reactivate');
});