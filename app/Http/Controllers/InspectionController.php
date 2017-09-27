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
        $deactivate = InspectionType::where('isActive',0)->get(); 
        return View('inspection.index', compact('inspections','deactivate'));
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
            'type' => ['required','max:50','unique:inspection_type','regex:/^[^~`!@#*_={}|\;<>,.?]+$/'],
            'item.*' => ['required','max:50','regex:/^[^~`!@#*_={}|\;<>,.?]+$/'],
            'inspectionForm.*' => 'required'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'regex' => 'The :attribute must not contain special characters.'                
        ];
        $niceNames = [
            'type' => 'Type',
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
                $type = InspectionType::create([
                    'type' => trim($request->type),
                ]);
                $items = $request->item;
                $forms = $request->inspectionForm;
                foreach ($items as $key=>$item) {
                    InspectionItem::create([
                        'name' => $item,
                        'form' => $forms[$key],
                        'typeId' => $type->id,
                    ]);
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect('inspection');
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
            'type' => ['required','max:50',Rule::unique('inspection_type')->ignore($id),'regex:/^[^~`!@#*_={}|\;<>,.?]+$/'],
            'item.*' => ['required','max:50','regex:/^[^~`!@#*_={}|\;<>,.?]+$/'],
            'inspectionForm.*' => 'required'
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.',
            'regex' => 'The :attribute must not contain special characters.'                
        ];
        $niceNames = [
            'type' => 'Type',
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
                $type = InspectionType::findOrFail($id);
                $type->update([
                    'type' => trim($request->type),
                ]);
                InspectionItem::where('typeId',$id)->update(['isActive'=>0]);
                $items = $request->item;
                $forms = $request->inspectionForm;
                foreach ($items as $key=>$item) {
                    InspectionItem::updateOrCreate(
                        ['name' => $item,'typeId' => $type->id],
                        ['form'=>$forms[$key],'isActive' => 1]
                    );
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully updated.'); 
            return Redirect('inspection');
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
            $checkInspect = DB::table('inspection_detail as id')
                ->join('inspection_item as ii','ii.id','id.itemId')
                ->join('inspection_type as it','it.id','ii.typeId')
                ->where('it.id',$id)
                ->where('id.isActive',1)
                ->get();
            if(count($checkInspect) > 0){
                $request->session()->flash('error', 'It seems that the record is still being used in other items. Deactivation failed.');
            }else{
                $type = InspectionType::findOrFail($id);
                $type->update([
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
        return Redirect('inspection');
    }
    
    public function reactivate(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $type = InspectionType::findOrFail($id);
            $type->update([
                'isActive' => 1
            ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            $errMess = $e->getMessage();
            return Redirect::back()->withErrors($errMess);
        }
        $request->session()->flash('success', 'Successfully reactivated.');  
        return Redirect('inspection');
    }

    public function remove(Request $request, $id){
        $checkInspect = DB::table('inspection_detail')
            ->where('itemId',$id)
            ->where('isActive',1)
            ->get();
        if(count($checkInspect) > 0){
            return response()->json(['message'=>'It seems that the record is still being used in other items. Discarding failed.']);
        }else{
            return response()->json(['message'=>0]);
        }
    }

    public function getData($id){
        $items = InspectionItem::where('isActive',1)->get();
        $customers = DB::table('customer')
            ->select('customer.*')
            ->get();
        $autos = DB::table('vehicle_model as vd')
            ->join('vehicle_make as vk','vd.makeId','vk.id')
            ->select('vd.*','vk.name as make')
            ->where('hasAuto',1)
            ->get();
        $manuals = DB::table('vehicle_model as vd')
            ->join('vehicle_make as vk','vd.makeId','vk.id')
            ->select('vd.*','vk.name as make')
            ->where('hasManual',1)
            ->get();
        $technicians = DB::table('technician')
            ->where('isActive',1)
            ->select('technician.*')
            ->get();
        $racks = DB::table('rack')
            ->where('isActive',1)
            ->select('rack.*')
            ->get();
        return View('inspection.form',compact('items','customers','autos','manuals','technicians','racks'));
    }
}
