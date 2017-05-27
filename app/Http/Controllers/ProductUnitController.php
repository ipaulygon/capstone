<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ProductUnit;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

class ProductUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = DB::table('product_unit')
            ->where('isActive',1)
            ->get();
        $deactivate = DB::table('product_unit')
            ->where('isActive',0)
            ->get();
        return View('unit.index', compact('units','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('layouts.404');
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
            'name' => 'required|alpha|unique:product_unit|max:20',
            'description' => 'required|max:50',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Product UOM',
            'description' => 'Alternative Name',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                ProductUnit::create([
                    'name' => trim($request->name),
                    'description' => trim($request->description),
                    'isActive' => 1
                ]);
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
        $unit = ProductUnit::findOrFail($id);
        return response()->json(['unit'=>$unit]);
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
            'name' => ['required','alpha','max:20',Rule::unique('product_unit')->ignore(trim($request->id))],
            'description' => 'required|max:50',
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Product UOM',
            'description' => 'Alternative Name',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $unit = ProductUnit::findOrFail(trim($request->id));
                $unit->update([
                    'name' => trim($request->name),
                    'description' => trim($request->description),
                ]);
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
        $unit = ProductUnit::findOrFail($id);
        $unit->update([
            'isActive' => 0
        ]);
        $request->session()->flash('success', 'Successfully deactivated.');  
        return Redirect::back();
    }

    public function reactivate(Request $request, $id)
    {
        $unit = ProductUnit::findOrFail($id);
        $unit->update([
            'isActive' => 1
        ]);
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect::back();
    }
}
