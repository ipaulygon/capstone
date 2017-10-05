<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

clASs QueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::select(DB::raw('
        SELECT product, type, brand, variance, original, description, SUM(productCount) AS total FROM (
            SELECT p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, SUM(sp.quantity) AS productCount FROM product AS p
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            JOIN sales_product AS sp ON sp.id = p.id
            WHERE p.isActive=1
            GROUP BY type,brand,variance,product,original,description
            UNION
            SELECT p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, SUM(sp.quantity*pp.quantity) AS productCount FROM product AS p
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            JOIN package_product AS pp ON pp.id = p.id
            JOIN package ON package.id = pp.packageId
            JOIN sales_package AS sp ON sp.packageId = package.id
            WHERE p.isActive=1
            GROUP BY type,brand,variance,product,original,description
            UNION
            SELECT p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, SUM(sp.quantity*pp.quantity) AS productCount FROM product AS p
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            JOIN promo_product AS pp ON pp.id = p.id
            JOIN promo ON promo.id = pp.promoId
            JOIN sales_promo AS sp ON sp.promoId = promo.id
            WHERE p.isActive=1
            GROUP BY type,brand,variance,product,original,description
            UNION
            SELECT p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, SUM(jp.quantity) AS productCount FROM product AS p
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            JOIN job_product AS jp ON jp.id = p.id
            JOIN job_header AS jh ON jh.id = jp.jobId
            WHERE p.isActive=1 AND jh.isComplete=1
            GROUP BY type,brand,variance,product,original,description
            UNION
            SELECT p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, SUM(jp.quantity*pp.quantity) AS productCount FROM product AS p
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            JOIN package_product AS pp ON pp.id = p.id
            JOIN package ON package.id = pp.packageId
            JOIN job_package AS jp ON jp.packageId = package.id
            JOIN job_header AS jh ON jh.id = jp.jobId
            WHERE p.isActive=1 AND jh.isComplete=1
            GROUP BY type,brand,variance,product,original,description
            UNION
            SELECT p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, SUM(jp.quantity*pp.quantity) AS productCount FROM product AS p
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            JOIN promo_product AS pp ON pp.id = p.id
            JOIN promo ON promo.id = pp.promoId
            JOIN job_promo AS jp ON jp.promoId = promo.id
            JOIN job_header AS jh ON jh.id = jp.jobId
            WHERE p.isActive=1 AND jh.isComplete=1
            GROUP BY type,brand,variance,product,original,description
        ) AS result
        GROUP BY type,brand,variance,product,original,description
        ORDER BY total
        LIMIT 5
        '));
        $services = DB::select(DB::raw('
        SELECT category, service, size, SUM(serviceCount) AS total FROM(
            SELECT s.name AS service, sc.name AS category, s.size AS size, COUNT(*) AS serviceCount FROM service AS s
            JOIN service_category AS sc ON sc.id = s.categoryId
            JOIN job_service AS js ON js.serviceId = s.id
            JOIN job_header AS jh ON jh.id = js.jobId
            WHERE s.isActive=1 AND jh.isComplete=1
            GROUP BY category,service,size
            UNION
            SELECT s.name AS service, sc.name AS category, s.size AS size, SUM(jp.quantity) AS serviceCount FROM service AS s
            JOIN service_category AS sc ON sc.id = s.categoryId
            JOIN package_service AS ps ON ps.serviceId = s.id
            JOIN package ON package.id = ps.packageId
            JOIN job_package AS jp ON jp.packageId = package.id
            JOIN job_header AS jh ON jh.id = jp.jobId
            WHERE s.isActive=1 AND jh.isComplete=1
            GROUP BY category,service,size
            UNION
            SELECT s.name AS service, sc.name AS category, s.size AS size, SUM(jp.quantity) AS serviceCount FROM service AS s
            JOIN service_category AS sc ON sc.id = s.categoryId
            JOIN promo_service AS ps ON ps.serviceId = s.id
            JOIN promo ON promo.id = ps.promoId
            JOIN job_promo AS jp ON jp.promoId = promo.id
            JOIN job_header AS jh ON jh.id = jp.jobId
            WHERE s.isActive=1 AND jh.isComplete=1
            GROUP BY category,service,size
        )AS result
        GROUP BY category,service,size
        ORDER BY total
        LIMIT 5
        '));

        $technicians = DB::select(DB::raw('
        SELECT tech.*, COUNT(*) AS total FROM technician AS tech
        JOIN job_technician AS jt ON jt.technicianId = tech.id
        JOIN job_header AS jh ON jh.id = jt.jobId
        WHERE jh.isComplete=1
        GROUP BY tech.id
        ORDER BY total
        LIMIT 5
        '));

        $vehicles = DB::select(DB::raw('
        SELECT v.*, vd.name AS model, vk.name AS make, vd.year AS year, COUNT(*) AS total FROM vehicle AS v
        JOIN vehicle_model AS vd ON vd.id = v.modelId
        JOIN vehicle_make AS vk ON vk.id = vd.makeId
        JOIN job_header AS jh ON jh.vehicleId = v.id
        WHERE jh.isComplete=1
        GROUP BY v.id
        ORDER BY total
        LIMIT 5
        '));

        $customers = DB::select(DB::raw('
        SELECT c.*, jh.total as total, jh.paid as paid FROM customer as c
        JOIN job_header AS jh ON jh.customerId = c.id
        WHERE jh.total!=jh.paid
        '));

        $inventory = DB::table('inventory AS i')
            ->join('product AS p','p.id','i.productId')
            ->join('product_type AS pt','pt.id','p.typeId')
            ->join('product_brand AS pb','pb.id','p.brandId')
            ->join('product_variance AS pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->select('i.*','p.name AS product','p.isOriginal AS isOriginal','pt.name AS type','pb.name AS brand','pv.name AS variance')
            ->get();

        return View('query.index',compact('products','services','technicians','vehicles','customers','inventory'));
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
}
