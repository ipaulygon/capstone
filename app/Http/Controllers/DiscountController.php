<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discount;
use App\DiscountRate;
use App\DiscountProduct;
use App\DiscountService;
use App\Product;
use App\Service;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::where('isActive',1)->get();
        $deactivate = Discount::where('isActive',0)->get();
        return View('discount.index', compact('discounts','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dProducts = DB::table('discount_product')
            ->distinct()
            ->where('isActive',1)
            ->select('productId')
            ->get();
        $discountedProducts = [];
        foreach($dProducts as $d){
            $discountedProducts[] = $d->productId;
        }
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->whereNotIn('p.id',$discountedProducts)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $dServices = DB::table('discount_service')
            ->distinct()
            ->where('isActive',1)
            ->select('serviceId')
            ->get();
        $discountedServices = [];
        foreach($dServices as $d){
            $discountedServices[] = $d->serviceId;
        }
        $services = DB::table('service as s')
            ->join('service_category as c','c.id','s.categoryId')
            ->where('s.isActive',1)
            ->whereNotIn('s.id',$discountedServices)
            ->select('s.*','c.name as category')
            ->get();
        return View('discount.create',compact('products','services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required','max:50','unique:discount','regex:/^[^~`!@#*_={}|\;<>,.?]+$/'],
            'rate' => 'required|between:1,100',
            'isWhole' => 'required',
            'isVatExempt' => 'required'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'regex' => 'The :attribute must not contain special characters. (i.e. ~`!@#^*_={}|\;<>,.?).'                
        ];
        $niceNames = [
            'name' => 'Discount',
            'rate' => 'Rate',
            'isWhole' => 'Type',
            'isVatExempt' => 'VAT Exempted'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $discount = Discount::create([
                    'name' => trim($request->name),
                    'rate' => trim(str_replace(' %','',$request->rate)),
                    'isWhole' => $request->isWhole,
                    'isVatExempt' => $request->isVatExempt,
                ]);
                $products = $request->product;
                $services = $request->service;
                if(!empty($products)){
                    foreach ($products as $product) {
                        DiscountProduct::create([
                            'discountId' => $discount->id,
                            'productId' => $product,
                        ]);
                    }
                }
                if(!empty($services)){
                    foreach ($services as $service) {
                        DiscountService::create([
                            'discountId' => $discount->id,
                            'serviceId' => $service,
                        ]);
                    }
                }
                DiscountRate::create([
                    'discountId' => $discount->id,
                    'rate' => trim(str_replace(' %','',$request->rate))
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect('discount');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return View('layouts.404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        $dProducts = DB::table('discount_product')
            ->distinct()
            ->where('isActive',1)
            ->whereNotIn('discountId',[$id])
            ->select('productId')
            ->get();
        $discountedProducts = [];
        foreach($dProducts as $d){
            $discountedProducts[] = $d->productId;
        }
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->whereNotIn('p.id',$discountedProducts)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $dServices = DB::table('discount_service')
            ->distinct()
            ->where('isActive',1)
            ->whereNotIn('discountId',[$id])
            ->select('serviceId')
            ->get();
        $discountedServices = [];
        foreach($dServices as $d){
            $discountedServices[] = $d->serviceId;
        }
        $services = DB::table('service as s')
            ->join('service_category as c','c.id','s.categoryId')
            ->where('s.isActive',1)
            ->whereNotIn('s.id',$discountedServices)
            ->select('s.*','c.name as category')
            ->get();
        return View('discount.edit',compact('discount','products','services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => ['required','max:50',Rule::unique('discount')->ignore($id),'regex:/^[^~`!@#*_={}|\;<>,.?]+$/'],
            'rate' => 'required|between:1,100',
            'isWhole' => 'required',
            'isVatExempt' => 'required'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'regex' => 'The :attribute must not contain special characters. (i.e. ~`!@#^*_={}|\;<>,.?).'                
        ];
        $niceNames = [
            'name' => 'Discount',
            'rate' => 'Rate',
            'isWhole' => 'Type',
            'isVatExempt' => 'VAT Exempted'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $discount = Discount::findOrFail($id);
                $discount->update([
                    'name' => trim($request->name),
                    'rate' => trim(str_replace(' %','',$request->rate)),
                    'isWhole' => $request->isWhole,
                    'isVatExempt' => $request->isVatExempt,
                ]);
                DiscountProduct::where('discountId',$id)->update(['isActive'=>0]);
                DiscountService::where('discountId',$id)->update(['isActive'=>0]);
                $products = $request->product;
                $services = $request->service;
                if(!empty($products)){
                    foreach ($products as $product) {
                        DiscountProduct::create([
                            'discountId' => $discount->id,
                            'productId' => $product,
                        ]);
                    }
                }
                if(!empty($services)){
                    foreach ($services as $service) {
                        DiscountService::create([
                            'discountId' => $discount->id,
                            'serviceId' => $service,
                        ]);
                    }
                }
                DiscountRate::create([
                    'discountId' => $discount->id,
                    'rate' => trim(str_replace(' %','',$request->rate))
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully updated.');  
            return Redirect('discount');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $discount = Discount::findOrFail($id);
            $discount->update([
                'isActive' => 0
            ]);
            DiscountProduct::where('discountId',$id)->update(['isActive'=>0]);
            DiscountService::where('discountId',$id)->update(['isActive'=>0]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        $request->session()->flash('success', 'Successfully deactivated.');  
        return Redirect('discount');
    }
    
    public function reactivate(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $discount = Discount::findOrFail($id);
            $discount->update([
                'isActive' => 1
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('discount');
    }
}
