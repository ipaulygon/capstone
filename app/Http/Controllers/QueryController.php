<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class QueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('query.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function load(Request $request){
        $id = $request->id;
        if($id==1){
            // $query = DB::table('product as p')
            // ->join('product_type as pt','pt.id','p.typeId')
            // ->join('product_brand as pb','pb.id','p.brandId')
            // ->join('product_variance as pv','pv.id','p.varianceId')
            // ->join('job_product as jp','jp.productId','p.id')
            // ->join('job_header as jh','jp.jobId','jh.id')
            // ->where('p.isActive',1)
            // ->where('jp.isActive',1)
            // ->where('jh.isComplete',1)
            // ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            // ->get();
            $query = DB::select(DB::raw("
				SELECT COUNT(*) as count, product.name as product, product_brand.name as brand, product_variance.name as variance FROM product 
				JOIN product_brand on product.brandId = product_brand.id 
				JOIN product_variance on product.varianceId = product_variance.id
				JOIN job_product ON product.id = job_product.productId 
				JOIN job_header ON job_header.id = job_product.productId
				WHERE job_header.isFinalize = '1' AND job_header.total = job_header.paid 
				GROUP BY product.name,product_brand.name,product_variance.name")
            );
            return response()->json(['query'=>$query]);
        }
        else if($id==2){
             $query = DB::select(DB::raw("
				SELECT COUNT(*) as count, service.name as service FROM `service` JOIN job_service ON 
				service.id = job_service.serviceId JOIN job_header ON job_header.id = job_service.serviceId 
				WHERE job_header.isFinalize = 1 AND job_header.total = job_header.paid
				 GROUP BY service.name LIMIT 1 ")
             );
            return response()->json(['query'=>$query]);
        }
        else if($id==3){
             $query = DB::select(DB::raw("
				SELECT COUNT(*) as count, technician.firstName, technician.middleName, technician.lastName 
				FROM technician JOIN job_technician ON technician.id = job_technician.technicianId 
				JOIN job_header ON job_header.id = job_technician.technicianId WHERE job_header.isFinalize 
				= '1' GROUP BY technician.firstName, technician.middleName, technician.lastName LIMIT 1")
             );
            return response()->json(['query'=>$query]);
        }
        else if($id==4){
             $query = DB::select(DB::raw("
				SELECT COUNT(*) as count, vehicle.plate, vehicle.mileage, vehicle_model.name, vehicle_model.year, vehicle_make.name FROM vehicle JOIN vehicle_model ON 
				vehicle_model.id = vehicle.modelId JOIN vehicle_make ON vehicle_make.id = 
				vehicle_model.makeId JOIN job_header ON job_header.vehicleId = vehicle.id WHERE 
				job_header.isFinalize = 1 AND job_header.total = job_header.paid GROUP BY vehicle.plate, 
				vehicle.mileage, vehicle_model.name, vehicle_model.year, 
				vehicle_make.name")
             );
            return response()->json(['query'=>$query]);
        }
        else if($id==5){
             $query = DB::select(DB::raw("
				Select * from job_header JOIN customer on job_header.customerId = customer.id 
				WHERE job_header.total <> job_header.paid"));
            return response()->json(['query'=>$query]);
        }
    }
}
