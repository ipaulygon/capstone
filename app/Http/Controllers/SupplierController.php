<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Supplier;
use App\SupplierPerson;
use App\SupplierContact;
use Validator;
use Redirect;
use Session;
use DB;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::where('isActive',1)->get();
        $deactivate = Supplier::where('isActive',0)->get();
        return View('supplier.index', compact('suppliers','deactivate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('supplier.create');
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
            'name' => 'required|unique:supplier|max:75',
            'address' => 'required|max:140',
            'spName.*' => 'required|max:100',
            'scNo.*' => 'required|max:20'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Supplier',
            'address' => 'Address',
            'spName.*' => 'Contact Person',
            'scNo.*' => 'Contact Number',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $supplier = Supplier::create([
                    'name' => trim($request->name),
                    'address' => trim($request->address),
                    'isActive' => 1
                ]);
                $persons = $request->spName;
                $contacts = $request->scNo;
                foreach ($persons as $person) {
                    SupplierPerson::create([
                        'spId' => $supplier->id,
                        'spName' => $person,
                    ]);
                }
                foreach ($contacts as $contact) {
                    SupplierContact::create([
                        'scId' => $supplier->id,
                        'scNo' => $contact,
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
        $supplier = Supplier::findOrFail($id);
        return View('supplier.edit',compact('supplier'));
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
            'name' => ['required','max:75',Rule::unique('supplier')->ignore($id)],
            'address' => 'required|max:140',
            'spName.*' => 'required|max:100',
            'scNo.*' => 'required|max:20',
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Supplier',
            'address' => 'Address',
            'spName.*' => 'Contact Person',
            'scNo.*' => 'Contact Number',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        else{
            try{
                DB::beginTransaction();
                $supplier = Supplier::findOrFail($id);
                $supplier->update([
                    'name' => trim($request->name),
                    'address' => trim($request->address),
                ]);
                SupplierPerson::where('spId',$id)->delete();
                SupplierContact::where('scId',$id)->delete();
                $persons = $request->spName;
                $contacts = $request->scNo;
                foreach ($persons as $person) {
                    SupplierPerson::create([
                        'spId' => $id,
                        'spName' => $person,
                    ]);
                }
                foreach ($contacts as $contact) {
                    SupplierContact::create([
                        'scId' => $id,
                        'scNo' => $contact,
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
    public function destroy(Request $request,$id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'isActive' => 0
        ]);
        $request->session()->flash('success', 'Successfully deactivated.');  
        return Redirect::back();
    }
    
    public function reactivate(Request $request,$id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'isActive' => 1
        ]);
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect::back();
    }
}
