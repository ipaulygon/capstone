<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Service;
use App\Package;
use App\Promo;
use App\Discount;
use App\Vehicle;
use App\Customer;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;
use Auth;
use Hash;
use App\User;

class ItemController extends Controller
{
    public function product($id){
        $product = Product::with('type')
            ->with('brand')
            ->with('variance')
            ->with('inventory')
            ->with('discount.header.rateRecord')
            ->with('discountRecord.header.rateRecord')
            ->with('priceRecord')
            ->with('vehicle.model.make')
            ->findOrFail($id);
        return response()->json(['product'=>$product]);
    }

    public function service($id){
        $service = Service::with('category')
            ->with('discount.header.rateRecord')
            ->with('discountRecord.header.rateRecord')
            ->with('priceRecord')
            ->findOrFail($id);
        return response()->json(['service'=>$service]);
    }

    public function package($id){
        $package = Package::with('product.product.type')
            ->with('product.product.brand')
            ->with('product.product.variance')
            ->with('product.product.inventory')
            ->with('service.service.category')
            ->with('priceRecord')
            ->findOrFail($id);
        return response()->json(['package'=>$package]);
    }

    public function promo($id){
        $promo = Promo::with('product.product.type')
            ->with('product.product.brand')
            ->with('product.product.variance')
            ->with('product.product.inventory')
            ->with('freeProduct.product.type')
            ->with('freeProduct.product.brand')
            ->with('freeProduct.product.variance')
            ->with('freeProduct.product.inventory')
            ->with('service.service.category')
            ->with('freeService.service.category')
            ->with('priceRecord')
            ->findOrFail($id);
        return response()->json(['promo'=>$promo]);
    }

    public function discount($id){
        $discount = Discount::with('rateRecord')->findOrFail($id);
        return response()->json(['discount'=>$discount]);
    }

    public function customer($id)
    {
        $customer = DB::table('customer as c')
            ->where(DB::raw('CONCAT_WS(" ",c.firstName,c.middleName,c.lastName)'),''.$id)
            ->select('c.*')
            ->first();
        return response()->json(['customer'=>$customer]);
    }
    
    public function vehicle($id)
    {
        $vehicle = DB::table('vehicle as v')
            ->where('v.plate',''.$id)
            ->select('v.*')
            ->first();
        if(!empty($vehicle)){
            return response()->json(['vehicle'=>$vehicle]);
        }
    }

    public function user(Request $request)
    {
        $key = trim($request->key);
        $id = Auth::id();
        $user = DB::table('users')
            ->where('id',$id)
            ->select('users.*')
            ->first();
        $message = (Hash::check($key,$user->password) ? true : false);
        return response()->json(['message'=>$message]);
    }
}
