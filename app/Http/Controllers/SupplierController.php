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
            'name' => ['required','max:75','unique:supplier','regex:/^[^~`!@#*_={}|\;<>,.?]+$/'],
            'street' => 'nullable|max:140',
            'brgy' => 'nullable|max:140',
            'city' => 'required|max:140',
            'spName.*' => ['required','disctinct','max:100','regex:/^[^~`!@#*_={}|\;<>,.?()$%&^]+$/'],
            'spContact.*' => ['nullable','distinct','max:30','regex:/^[^_]+$/'],
            'scNo.*' => ['required','distinct','max:30','regex:/^[^_]+$/']
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'regex' => 'The :attribute must not contain special characters.'
        ];
        $niceNames = [
            'name' => 'Supplier',
            'street' => 'No. & St./Bldg.',
            'brgy' => 'Brgy./Subd.',
            'city' => 'City/Municipality',
            'spName.*' => 'Contact Person',
            'spContact.*' => 'Contact Number',
            'scNo.*' => 'Supplier Contact',
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
                    'street' => trim($request->street),
                    'brgy' => trim($request->brgy),
                    'city' => trim($request->city),
                ]);
                $persons = $request->spName;
                $personContact = $request->spContact;
                $contacts = $request->scNo;
                foreach ($persons as $key=>$person) {
                    $isMain = ($key==0 ? 1 : 0);
                    SupplierPerson::create([
                        'spId' => $supplier->id,
                        'spName' => $person,
                        'spContact' => $personContact[$key],
                        'isMain' => $isMain
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
            return Redirect('supplier');
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
            'name' => ['required','max:75',Rule::unique('supplier')->ignore($id),'regex:/^[^~`!@#*_={}|\;<>,.?]+$/'],
            'street' => 'nullable|max:140',
            'brgy' => 'nullable|max:140',
            'city' => 'required|max:140',
            'spName.*' => ['required','distinct','max:100','regex:/^[^~`!@#*_={}|\;<>,.?()$%&^]+$/'],
            'spContact.*' => ['nullable','distinct','max:30','regex:/^[^_]+$/'],
            'scNo.*' => ['required','distinct','max:30','regex:/^[^_]+$/']
        ];
        $messages = [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'regex' => 'The :attribute must not contain special characters.'
        ];
        $niceNames = [
            'name' => 'Supplier',
            'street' => 'No. & St./Bldg.',
            'brgy' => 'Brgy./Subd.',
            'city' => 'City/Municipality',
            'spName.*' => 'Contact Person',
            'spContact.*' => 'Contact Number',
            'scNo.*' => 'Supplier Contact',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $supplier = Supplier::findOrFail($id);
                $supplier->update([
                    'name' => trim($request->name),
                    'street' => trim($request->street),
                    'brgy' => trim($request->brgy),
                    'city' => trim($request->city),
                ]);
                SupplierPerson::where('spId',$id)->delete();
                SupplierContact::where('scId',$id)->delete();
                $persons = $request->spName;
                $personContact = $request->spContact;
                $contacts = $request->scNo;
                foreach ($persons as $key=>$person) {
                    $isMain = ($key==0 ? 1 : 0);
                    SupplierPerson::create([
                        'spId' => $supplier->id,
                        'spName' => $person,
                        'spContact' => $personContact[$key],
                        'isMain' => $isMain
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
            return Redirect('supplier');
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
        try{
            DB::beginTransaction();
            $checkPurchase = DB::table('purchase_header as ph')
                ->join('supplier as s','s.id','ph.supplierId')
                ->where('ph.supplierId',$id)
                ->get();
            if(count($checkPurchase) > 0){
                $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
            }else{
                $supplier = Supplier::findOrFail($id);
                $supplier->update([
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
        return Redirect('supplier');
    }
    
    public function reactivate(Request $request,$id)
    {
        try{
            DB::beginTransaction();
            $supplier = Supplier::findOrFail($id);
            $supplier->update([
                'isActive' => 1
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('supplier');
    }
}
