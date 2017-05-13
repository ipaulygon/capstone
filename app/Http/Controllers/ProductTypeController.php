<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ProductType;
use App\ProductBrand;
use App\TypeBrand;
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
        return View('type.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = ProductBrand::all();
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
            'brand.*' => 'required|max:50',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Product Type',
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
                ProductType::create([
                    'name' => trim($request->name),
                    'isActive' => 1
                ]);
                $type = ProductType::all()->last();
                $brands = $request->brand;
                foreach ($brands as $brand) {
                    ProductBrand::updateOrCreate(
                        ['name' => $brand],
                        [
                            'isActive' => 1
                        ]
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
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect::back();
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
            'brand.*' => 'required|max:50',
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Product Type',
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
                    'name' => trim($request->name)
                ]);
                TypeBrand::where('typeId',$id)->delete();
                $brands = $request->brand;
                foreach ($brands as $brand) {
                    ProductBrand::updateOrCreate(
                        ['name' => $brand],
                        [
                            'isActive' => 1
                        ]
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
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully updated.');  
            return Redirect::back();
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
        $type = ProductType::findOrFail($id);
        $type->update([
            'isActive' => 0
        ]);
        $request->session()->flash('success', 'Successfully deactivated.');  
        return Redirect::back();
    }
}
