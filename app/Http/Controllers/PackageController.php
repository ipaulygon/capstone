<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Package;
use App\PackageProduct;
use App\PackageService;
use App\PackagePrice;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::where('isActive',1)->get();
        $deactivate = Package::where('isActive',0)->get();
        return View('package.index',compact('packages','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $services = DB::table('service as s')
            ->join('service_category as c','c.id','s.categoryId')
            ->where('s.isActive',1)
            ->select('s.*','c.name as category')
            ->get();
        return View('package.create',compact('products','services'));
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
            'name' => 'required|unique:package|max:50',
            'price' => 'required|between:0,1000000',
            'qty.*' => 'sometimes|required|integer|between:0,100',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'numeric' => 'The :attribute field must be a valid number.',
        ];
        $niceNames = [
            'name' => 'Package',
            'price' => 'Price',
            'qty.*' => 'Product Quantity',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $package = Package::create([
                    'name' => trim($request->name),
                    'price' => trim(str_replace(',','',$request->price)),
                ]);
                $products = $request->product;
                $qty = $request->qty;
                $services = $request->service;
                if(!empty($products)){
                    foreach ($products as $key=>$product) {
                        PackageProduct::create([
                            'packageId' => $package->id,
                            'productId' => $product,
                            'quantity' => $qty[$key],
                        ]);
                    }
                }
                if(!empty($services)){
                    foreach ($services as $service) {
                        PackageService::create([
                            'packageId' => $package->id,
                            'serviceId' => $service,
                        ]);
                    }
                }
                PackagePrice::create([
                    'packageId' => $package->id,
                    'price' => trim(str_replace(',','',$request->price))
                ]);
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect('package');
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
        $package = Package::findOrFail($id);
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $services = DB::table('service as s')
            ->join('service_category as c','c.id','s.categoryId')
            ->where('s.isActive',1)
            ->select('s.*','c.name as category')
            ->get();
        return View('package.edit',compact('package','products','services'));
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
            'name' => ['required','max:50',Rule::unique('package')->ignore($id)],
            'price' => 'required|between:0,1000000',
            'qty.*' => 'sometimes|required|integer|between:0,100',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'numeric' => 'The :attribute field must be a valid number.',
        ];
        $niceNames = [
            'name' => 'Package',
            'price' => 'Price',
            'qty.*' => 'Product Quantity',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        else{
            $checkEstimate = DB::table('estimate_header as eh')
                ->join('estimate_package as ep','ep.estimateId','eh.id')
                ->where('ep.isActive',1)
                ->where('ep.packageId',$id)
                ->get();
            $checkJob = DB::table('job_header as jh')
                ->join('job_package as jp','jp.jobId','jh.id')
                ->where('jp.packageId',$id)
                ->get();
            if(count($checkEstimate) > 0 || count($checkJob) > 0){
                $request->session()->flash('error', 'It seems that the record is still being used in other items. Update failed.');
                return Redirect('package');
            }else{
                try{
                    DB::beginTransaction();
                    $package = Package::findOrFail($id);
                    $package->update([
                        'name' => trim($request->name),
                        'price' => trim(str_replace(',','',$request->price)),
                    ]);
                    $products = $request->product;
                    $qty = $request->qty;
                    $services = $request->service;
                    PackageProduct::where('packageId',$id)->update(['isActive'=>0]);
                    PackageService::where('packageId',$id)->update(['isActive'=>0]);
                    if(!empty($products)){
                        foreach ($products as $key=>$product) {
                            PackageProduct::updateOrCreate([
                                'packageId' => $package->id,
                                'productId' => $product,
                            ],[
                                'quantity' => $qty[$key],
                                'isActive' => 1
                            ]
                            );
                        }
                    }
                    if(!empty($services)){
                        foreach ($services as $service) {
                            PackageService::updateOrCreate([
                                'packageId' => $id,
                                'serviceId' => $service,
                            ],[
                                'isActive' => 1
                            ]);
                        }
                    }
                    PackagePrice::create([
                        'packageId' => $id,
                        'price' => trim(str_replace(',','',$request->price))
                    ]);
                    DB::commit();
                }catch(\Illuminate\Database\QueryException $e){
                    DB::rollBack();
                    $errMess = $e->getMessage();
                    return Redirect::back()->withErrors($errMess);
                }
            }
            $request->session()->flash('success', 'Successfully updated.');  
            return Redirect('package');
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
            $checkEstimate = DB::table('estimate_header as eh')
                ->join('estimate_package as ep','ep.estimateId','eh.id')
                ->where('ep.isActive',1)
                ->where('ep.packageId',$id)
                ->get();
            $checkJob = DB::table('job_header as jh')
                ->join('job_package as jp','jp.jobId','jh.id')
                ->where('jp.packageId',$id)
                ->get();
            if(count($checkEstimate) > 0 || count($checkJob) > 0){
                $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
            }else{
                $item = Package::findOrFail($id);
                $item->update([
                    'isActive' => 0
                ]);
                $request->session()->flash('success', 'Successfully deactivated.');  
            }
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        return Redirect('package');
    }
    
    public function reactivate(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $item = Package::findOrFail($id);
            $item->update([
                'isActive' => 1
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('package');
    }
}
