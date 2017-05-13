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

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function(){
	Route::resource('supplier','SupplierController');
	Route::resource('type','ProductTypeController');
	Route::resource('brand','ProductBrandController');
	Route::resource('unit','ProductUnitController');
	Route::resource('variance','ProductVarianceController');
	Route::resource('product','ProductController');
	Route::get('product/type/{id}','ProductController@type');
	Route::resource('category','ServiceCategoryController');
	Route::resource('service','ServiceController');
	Route::resource('inspection','InspectionController');
	Route::resource('package','PackageController');
	Route::get('package/product/{id}','PackageController@product');
	Route::get('package/service/{id}','PackageController@service');
	Route::resource('promo','PromoController');
	Route::get('promo/product/{id}','PromoController@product');
	Route::get('promo/service/{id}','PromoController@service');
});