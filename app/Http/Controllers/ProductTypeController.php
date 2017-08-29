<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ProductType;
use App\ProductBrand;
use App\TypeBrand;
use App\TypeVariance;
use App\Product;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = ProductType::where('isActive',1)->get();
        $deactivate = ProductType::where('isActive',0)->get();
        return View('type.index', compact('types','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = ProductBrand::where('isActive',1)->get();
        return View('type.create',compact('brands'));
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
            'name' => 'required|unique:product_type|max:50',
            'category' => 'required',
            'brand.*' => 'required|max:50',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Product Type',
            'category' => 'Category',
            'brand.*' => 'Brand Name',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $type = ProductType::create([
                    'name' => trim($request->name),
                    'category' => trim($request->category),
                ]);
                $brands = $request->brand;
                foreach ($brands as $brand) {
                    ProductBrand::updateOrCreate(
                        ['name' => $brand],
                        ['isActive' => 1]
                    );
                    $branded = ProductBrand::where('name',$brand)->first();
                    TypeBrand::firstOrCreate([
                        'typeId' => $type->id,
                        'brandId' => $branded->id
                    ]);
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors("Oops! This has not been developed yet");
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect('type');
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
        $type = ProductType::findOrFail($id);
        $brands = ProductBrand::where('isActive',1)->get();
        return View('type.edit',compact('type','brands'));
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
            'name' => ['required','max:50',Rule::unique('product_type')->ignore($id)],
            'category' => 'required',
            'brand.*' => 'required|max:50',
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Product Type',
            'category' => 'Category',
            'brand.*' => 'Brand Name',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        else{
            try{
                DB::beginTransaction();
                $type = ProductType::findOrFail($id);
                $type->update([
                    'name' => trim($request->name),
                    'category' => trim($request->category)
                ]);
                TypeBrand::where('typeId',$id)->delete();
                $brands = $request->brand;
                foreach ($brands as $brand) {
                    ProductBrand::updateOrCreate(
                        ['name' => $brand],
                        ['isActive' => 1]
                    );
                    $branded = ProductBrand::where('name',$brand)->first();
                    TypeBrand::firstOrCreate([
                        'typeId' => $id,
                        'brandId' => $branded->id
                    ]);
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors("Oops! This has not been developed yet");
            }
            $request->session()->flash('success', 'Successfully updated.');  
            return Redirect('type');
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
            $checkBrand = DB::table('type_brand')
                ->where('typeId',$id)
                ->get();
            $checkVariance = DB::table('type_variance')
                ->where('typeId',$id)
                ->get();
            $checkProduct = DB::table('product')
                ->where('typeId',$id)
                ->where('isActive',1)
                ->get();
            if(count($checkBrand) > 0 || count($checkVariance) > 0 || count($checkProduct) > 0){
                $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
            }else{
                $type = ProductType::findOrFail($id);
                $type->update([
                    'isActive' => 0
                ]);
                $request->session()->flash('success', 'Successfully deactivated.');
            }
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors("Oops! This has not been developed yet");
        }
        return Redirect('type');
    }

    public function reactivate(Request $request,$id)
    {
        try{
            DB::beginTransaction();
            $type = ProductType::findOrFail($id);
            $type->update([
                'isActive' => 1
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors("Oops! This has not been developed yet");
        }
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('type');
    }

    public function remove(Request $request, $id){
        $checkProduct = DB::table('product')
            ->where('brandId',$id)
            ->get();
        if(count($checkProduct) > 0){
            return response()->json(['message'=>'It seems that the record is still being used in other items. Discarding failed.']);
        }else{
            return response()->json(['message'=>0]);
        }
    }
}
