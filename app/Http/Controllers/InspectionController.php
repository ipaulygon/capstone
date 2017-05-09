<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\InspectionItem;
use App\InspectionType;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspections = InspectionType::where('isActive',1)->get(); 
        return View('inspection.index', compact('inspections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View('inspection.create');
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
            'name' => 'required|unique:inspection_type|max:50',
            'item.*' => 'required|max:50',
            'inspectionForm.*' => 'required'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Type',
            'item.*' => 'Item',
            'inspectionForm.*'=>'Form'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                InspectionType::create([
                    'name' => trim($request->type),
                    'isActive' => 1
                ]);
                $type = InspectionType::all()->last();
                $items = $request->item;
                $forms = $request->inspectionForm;
                foreach ($items as $key=>$item) {
                    InspectionItem::create([
                        'name' => $item,
                        'form' => $forms[$key],
                        'typeId' => $type->id,
                        'isActive' => 1
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
        $type = InspectionType::findOrFail($id);
        return View('inspection.edit',compact('type'));
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
            'name' => ['required','max:50',Rule::unique('inspection_type')->ignore($id)],
            'item.*' => 'required|max:50',
            'inspectionForm.*' => 'required'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'name' => 'Type',
            'item.*' => 'Item',
            'inspectionForm.*'=>'Form'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        else{
            try{
                DB::beginTransaction();
                $type = InspectionType::findOrFail($id);
                $type->update([
                    'name' => trim($request->type),
                ]);
                InspectionItem::where('typeId',$id)->update(['isActive'=>0]);
                $items = $request->item;
                $forms = $request->inspectionForm;
                foreach ($items as $key=>$item) {
                    InspectionItem::updateOrCreate(
                        ['name' => $item,'typeId' => $type->id],
                        ['name'=> $item,'form'=>$forms[$key],'typeId'=>$type->id,'isActive' => 1]
                    );
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
    public function destroy($id)
    {
        //
    }
}
