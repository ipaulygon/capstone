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
        $product = DB::select(DB::raw("
                SELECT COUNT(*) as count, product.name as product, product_brand.name as brand, product_variance.name as variance, product_type.name as type, product.description as description, product.isOriginal as isOriginal FROM product 
                JOIN product_brand on product.brandId = product_brand.id 
                join product_type on product_type.id = product.typeId
                JOIN product_variance on product.varianceId = product_variance.id
                JOIN job_product ON product.id = job_product.productId 
                JOIN job_header ON job_header.id = job_product.productId
                WHERE job_header.isFinalize = '1' AND job_header.total = job_header.paid 
                GROUP BY product.name,product_brand.name,product_variance.name,product_type.name,product.description,product.isOriginal ORDER BY count DESC LIMIT 3 ")
        );

        $services = DB::select(DB::raw("
                SELECT COUNT(*) as count, service.name as service, service_category.name as category, service.size as size FROM `service` JOIN job_service ON  service.id = job_service.serviceId JOIN job_header ON job_header.id = job_service.serviceId join service_category on service.categoryId = service_category.id
                WHERE job_header.isFinalize = 1 AND job_header.total = job_header.paid
                 GROUP BY service.name,service_category.name,service.size ORDER BY count DESC LIMIT 3")
        );

        $technician = DB::select(DB::raw("
                SELECT COUNT(*) as count, technician.firstName, technician.middleName, technician.lastName, technician.image, technician.birthdate, technician.contact, technician.street, technician.brgy, technician.city, technician.email, technician_skill.categoryId as skill FROM technician JOIN job_technician ON technician.id = job_technician.technicianId JOIN job_header ON job_header.id = job_technician.technicianId join technician_skill on technician_skill.technicianId = technician.id WHERE job_header.isFinalize = '1' GROUP BY technician.firstName, technician.middleName, technician.lastName, technician.image, technician.birthdate, technician.contact, technician.street, technician.brgy, technician.city, technician.email, technician_skill.categoryId ORDER BY count DESC LIMIT 3")
        );

        $vehicle = DB::select(DB::raw("
                SELECT COUNT(*) as count, vehicle.plate, vehicle.mileage, vehicle_model.name as model, vehicle_model.year, vehicle_make.name as make, vehicle.isManual as transmission FROM vehicle JOIN vehicle_model ON 
                vehicle_model.id = vehicle.modelId JOIN vehicle_make ON vehicle_make.id = 
                vehicle_model.makeId JOIN job_header ON job_header.vehicleId = vehicle.id WHERE 
                job_header.isFinalize = 1 AND job_header.total = job_header.paid GROUP BY vehicle.plate, 
                vehicle.mileage, vehicle_model.name, vehicle_model.year, 
                vehicle_make.name,vehicle.isManual ORDER BY count desc LIMIT 3")
        );

        $customer = DB::select(DB::raw("
                Select * from job_header JOIN customer on job_header.customerId = customer.id 
                WHERE job_header.total <> job_header.paid"));

        $inventory = DB::table('inventory as i')
            ->join('product as p','p.id','i.productId')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->select('i.*','p.name as product','p.isOriginal as isOriginal','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();

        return View('query.index',compact('product','services','technician','vehicle','customer','inventory'));
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
        return View('layouts.404');
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

    public function load(Request $request){
        $id = $request->id;
        if($id==1){
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
