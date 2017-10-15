<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilities;
use Validator;
use Redirect;
use Session;
use DB;
use App\Audit;
use Auth;
use Illuminate\Validation\Rule;

class UtilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $utilities = Utilities::find(1);
        return View('utility.index',compact('utilities'));
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
        return View('layouts.404');
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
        return View('layouts.404');
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
            'image' => 'image|mimes:jpeg,png,jpg,svg',
            'name' => 'required|max:50',
            'address' => 'required|max:140',
            'category1' => 'required|max:50',
            'category2' => 'required|max:50',
            'type1' => 'required|max:50',
            'type2' => 'required|max:50',
            'max' => 'required|between:0,200',
            'isVat' => 'required',
            'vat' => 'required|between:0,100',
            'isWarranty' => 'required',
            'year' => 'required|between:0,5',
            'month' => 'required|between:0,12',
            'day' => 'required|between:0,31'
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'image' => 'Business Icon',
            'name' => 'Business Name',
            'address' => 'Business Address',
            'category1' => 'Product Type Category no.1',
            'category2' => 'Product Type Category no.2',
            'type1' => 'Part Type no.1',
            'type2' => 'Part Type no.2',
            'max' => 'Max Pieces',
            'isVat' => 'VAT/NON-VAT',
            'vat' => 'VAT',
            'isWarranty' => 'Warranty',
            'year' => 'Warranty Year',
            'month' => 'Warranty Month',
            'day' => 'Warranty Day'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($request->except('image'));
        }
        else{
            try{
                DB::beginTransaction();
                $utility = Utilities::findOrFail(1);
                $file = $request->file('image');
                $utilPic = "";
                if($file == '' || $file == null){
                    $utilPic = $utility->image;
                }else{
                    $date = date("Ymdhis");
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $utilPic = "pics/".$date.'util.'.$extension;
                    $request->file('image')->move("pics",$utilPic);    
                }
                $utility->update([
                    'image' => $utilPic,
                    'name' => trim($request->name),
                    'address' => trim($request->address),
                    'category1' => trim($request->category1),
                    'category2' => trim($request->category2),
                    'type1' => trim($request->type1),
                    'type2' => trim($request->type2),
                    'max' => str_replace(' pcs.','',$request->max),
                    'backlog' => $request->backlog,
                    'isVat' => $request->isVat,
                    'vat' => str_replace(' %','',$request->vat),
                    'isWarranty' => $request->isWarranty,
                    'year' => $request->year,
                    'month' => $request->month,
                    'day' => $request->day
                ]);
                Audit::create([
                    'userId' => Auth::id(),
                    'name' => "Change Settings",
                    'json' => json_encode($request->all())
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
    public function destroy($id)
    {
        return View('layouts.404');
    }
}
