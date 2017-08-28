<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\InspectionItem;
use App\InspectionType;
use App\InspectionHeader;
use App\InspectionDetail;
use App\InspectionTechnician;
use App\Vehicle;
use App\Customer;
use App\Technician;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;
class InspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspects = DB::table('inspection_header as i')
            ->join('customer as c','c.id','i.customerId')
            ->join('vehicle as v','v.id','i.vehicleId')
            ->join('vehicle_model as vd','vd.id','v.modelId')
            ->join('vehicle_make as vk','vk.id','vd.makeId')
            ->select('i.*','i.id as inspectId','c.*','v.*','vd.name as model','vd.year as year','v.isManual as transmission','vk.name as make')
            ->get();
        return View('inspect.index',compact('inspects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $technicians = Technician::where('isActive',1)->get();
        $racks = DB::table('rack')
            ->where('isActive',1)
            ->select('rack.*')
            ->get();
        return View('inspect.create',compact('items','customers','autos','manuals','technicians','racks'));
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
            'firstName' => 'required|max:45',
            'middleName' => 'max:45',
            'lastName' => 'required|max:45',
            'contact' => 'required',
            'email' => 'nullable|email',
            'street' => 'nullable|max:140',
            'brgy' => 'nullable|max:140',
            'city' => 'required|max:140',
            'plate' => 'required',
            'modelId' => 'required',
            'mileage' => 'nullable|between:0,1000000',
            'technician.*' => 'required',
            'rackId.*' => 'required',
            'remarks' => 'max:140',
            'form.*' => 'required',
            'item.*' => 'required',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'firstName' => 'First Name',
            'middleName' => 'Middle Name',
            'lastName' => 'Last Name',
            'contact' => 'Contact No.',
            'email' => 'Email Address',
            'street' => 'No. & St./Bldg.',
            'brgy' => 'Brgy./Subd.',
            'city' => 'City/Municipality',
            'plate' => 'Plate No.',
            'modelId' => 'Vehicle Model',
            'mileage' => 'Mileage',
            'technician.*' => 'Technician Assigned',
            'rackId.*' => 'Rack',
            'remarks' => 'Remarks',
            'form.*' => 'Form',
            'item.*' => 'Item'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $customer = Customer::updateOrCreate(
                    [
                        'firstName' => trim($request->firstName),
                        'middleName' => trim($request->middleName),
                        'lastName' => trim($request->lastName)
                    ],[
                        'contact' => str_replace('_','',trim($request->contact)),
                        'email' => $request->email,
                        'card' => $request->card,
                        'street' => trim($request->street),
                        'brgy' => trim($request->brgy),
                        'city' => trim($request->city),
                    ]
                );
                $mileage = ($request->mileage==''||$request->mileage==null ? 0 : $request->mileage);
                $model = explode(',',$request->modelId);
                $vehicle = Vehicle::updateOrCreate(
                    ['plate' => str_replace('_','',trim($request->plate))],
                    [
                        'modelId' => $model[0],
                        'isManual' => $model[1],
                        'mileage' => str_replace(' km','',$mileage)
                    ]
                );
                $inspection = InspectionHeader::create([
                    'customerId' => $customer->id,
                    'vehicleId' => $vehicle->id,
                    'rackId' => $request->rackId,
                    'remarks' => trim($request->remarks)
                ]);
                $forms = $request->form;
                $items = $request->itemId;
                foreach($items as $key=>$item){
                    InspectionDetail::create([
                        'inspectionId' => $inspection->id,
                        'itemId' => $item,
                        'remarks' => $forms[$key],
                    ]);
                }
                $technicians = $request->technician;
                foreach($technicians as $technician){
                    InspectionTechnician::create([
                        'inspectionId' => $inspection->id,
                        'technicianId' => $technician,
                    ]);
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect('inspect');
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
        $inspect = InspectionHeader::findOrfail($id);
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
        $technicians = Technician::where('isActive',1)->get();
        $racks = DB::table('rack')
            ->where('isActive',1)
            ->select('rack.*')
            ->get();
        return View('inspect.edit',compact('inspect','customers','autos','manuals','technicians','racks'));
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
            'firstName' => 'required|max:45',
            'middleName' => 'max:45',
            'lastName' => 'required|max:45',
            'contact' => 'required',
            'email' => 'nullable|email',
            'street' => 'nullable|max:140',
            'brgy' => 'nullable|max:140',
            'city' => 'required|max:140',
            'plate' => 'required',
            'modelId' => 'required',
            'mileage' => 'nullable|between:0,1000000',
            'technician.*' => 'required',
            'rackId.*' => 'required',
            'remarks' => 'max:140',
            'form.*' => 'required',
            'item.*' => 'required',
        ];
        $messages = [
            'unique' => ':attribute already exists.',
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute field must be no longer than :max characters.'
        ];
        $niceNames = [
            'firstName' => 'First Name',
            'middleName' => 'Middle Name',
            'lastName' => 'Last Name',
            'contact' => 'Contact No.',
            'email' => 'Email Address',
            'street' => 'No. & St./Bldg.',
            'brgy' => 'Brgy./Subd.',
            'city' => 'City/Municipality',
            'plate' => 'Plate No.',
            'modelId' => 'Vehicle Model',
            'mileage' => 'Mileage',
            'technician.*' => 'Technician Assigned',
            'rackId.*' => 'Rack',
            'remarks' => 'Remarks',
            'form.*' => 'Form',
            'item.*' => 'Item'
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        $validator->setAttributeNames($niceNames); 
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        else{
            try{
                DB::beginTransaction();
                $customer = Customer::updateOrCreate(
                    [
                        'firstName' => trim($request->firstName),
                        'middleName' => trim($request->middleName),
                        'lastName' => trim($request->lastName)
                    ],[
                        'contact' => str_replace('_','',trim($request->contact)),
                        'email' => $request->email,
                        'card' => $request->card,
                        'street' => trim($request->street),
                        'brgy' => trim($request->brgy),
                        'city' => trim($request->city),
                    ]
                );
                $mileage = ($request->mileage==''||$request->mileage==null ? 0 : $request->mileage);
                $model = explode(',',$request->modelId);
                $vehicle = Vehicle::updateOrCreate(
                    ['plate' => str_replace('_','',trim($request->plate))],
                    [
                        'modelId' => $model[0],
                        'isManual' => $model[1],
                        'mileage' => str_replace(' km','',$mileage)
                    ]
                );
                $inspection = InspectionHeader::findOrFail($id);
                $inspection->update([
                    'customerId' => $customer->id,
                    'vehicleId' => $vehicle->id,
                    'rackId' => $request->rackId,
                    'remarks' => trim($request->remarks)
                ]);
                $forms = $request->form;
                $items = $request->itemId;
                InspectionDetail::where('inspectionId',$id)->update(['isActive'=>0]);
                foreach($items as $key=>$item){
                    InspectionDetail::updateOrCreate(
                        [
                            'inspectionId' => $inspection->id,
                            'itemId' => $item,
                        ],
                        [   
                            'remarks' => $forms[$key],
                            'isActive' => 1
                        ]
                    );
                }
                InspectionTechnician::where('inspectionId',$id)->update(['isActive'=>0]);
                $technicians = $request->technician;
                foreach($technicians as $technician){
                    InspectionTechnician::updateOrCreate(
                        [
                            'inspectionId' => $inspection->id,
                            'technicianId' => $technician
                        ],
                        ['isActive' => 1]
                    );
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully updated.');  
            return Redirect('inspect');
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
