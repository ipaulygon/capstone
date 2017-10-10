<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilities;
use DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $util = Utilities::findOrFail(1);
        $dateStart = date('m/d/Y', strtotime($util->created_at));
        $dateEnd = date('m/d/Y');
        $dateString = $dateStart.'-'.$dateEnd;
        $jobs = DB::select(DB::raw('
        SELECT CONCAT_WS(" ",c.firstName,c.middleName,c.lastName) AS customer, v.plate AS plate, vd.name AS model, vd.year AS year, vk.name AS make, v.isManual AS isManual, jh.*, SUM(CASE WHEN jp.isCredit=0 THEN jp.paid END) AS cash, SUM(CASE WHEN jp.isCredit=1 THEN jp.paid END) AS credit FROM job_header as jh
        JOIN customer AS c ON c.id = jh.customerId
        JOIN vehicle AS v ON v.id = jh.vehicleId
        JOIN vehicle_model AS vd ON vd.id = v.modelId
        JOIN vehicle_make AS vk ON vk.id = vd.makeId
        LEFT JOIN job_payment AS jp ON jp.jobId = jh.id
        WHERE jh.isVoid = 0
        GROUP BY jh.id
        '));
        $sales = DB::select(DB::raw('
        SELECT CONCAT_WS(" ",c.firstName,c.middleName,c.lastName) AS customer, sh.*, SUM(sh.total) AS total FROM sales_header AS sh
        JOIN customer AS c ON c.id = sh.customerId
        GROUP BY sh.id
        '));
        $inventory = DB::select(DB::raw('
        SELECT * FROM (
            SELECT p.name AS product, p.reorder AS reorder, SUM(CASE WHEN ISNULL(dd.quantity) THEN 0 ELSE dd.quantity END) AS delivered, SUM(CASE WHEN ISNULL(rd.quantity) THEN 0 ELSE rd.quantity END) AS returned FROM product AS p
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            JOIN inventory AS i ON i.productId = p.id
            LEFT JOIN delivery_detail AS dd ON dd.productId = p.id
            LEFT JOIN return_detail AS rd ON rd.productId = p.id
            GROUP BY product,reorder
        ) AS x
        JOIN 
        ( 
            SELECT product, t.name AS type, b.name AS brand, v.name AS variance, original, description, SUM(productCount) AS total FROM (
                SELECT p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity) THEN 0 ELSE sp.quantity END) AS productCount FROM product AS p
                LEFT JOIN sales_product AS sp ON sp.productId = p.id
                WHERE p.isActive=1
                GROUP BY type,brand,variance,product,original,description
                UNION
                SELECT p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity*pp.quantity) THEN 0 ELSE sp.quantity*pp.quantity END) AS productCount FROM product AS p
                JOIN package_product AS pp ON pp.productId = p.id
                LEFT JOIN sales_package AS sp ON sp.packageId = pp.packageId
                WHERE p.isActive=1
                GROUP BY type,brand,variance,product,original,description
                UNION
                SELECT p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity*pp.quantity) THEN 0 ELSE sp.quantity*pp.quantity END) AS productCount FROM product AS p
                JOIN promo_product AS pp ON pp.productId = p.id
                LEFT JOIN sales_promo AS sp ON sp.promoId = pp.promoId
                WHERE p.isActive=1
                GROUP BY type,brand,variance,product,original,description
                UNION
                SELECT p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS productCount FROM product AS p
                LEFT JOIN job_product AS jp ON jp.productId = p.id
                WHERE p.isActive=1
                GROUP BY type,brand,variance,product,original,description
                UNION
                SELECT p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed*pp.quantity) THEN 0 ELSE jp.completed*pp.quantity END) AS productCount FROM product AS p
                JOIN package_product AS pp ON pp.productId = p.id
                LEFT JOIN job_package AS jp ON jp.packageId = pp.packageId
                WHERE p.isActive=1
                GROUP BY type,brand,variance,product,original,description
                UNION
                SELECT p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed*pp.quantity) THEN 0 ELSE jp.completed*pp.quantity END) AS productCount FROM product AS p
                JOIN promo_product AS pp ON pp.productId = p.id
                LEFT JOIN job_promo AS jp ON jp.promoId = pp.promoId
                WHERE p.isActive=1
                GROUP BY type,brand,variance,product,original,description
            ) AS result
            JOIN product_type AS t ON t.id = result.type
            JOIN product_brand AS b ON b.id = result.brand
            JOIN product_variance AS v ON v.id = result.variance
            GROUP BY type,brand,variance,product,original,description
            ORDER BY total DESC
        ) AS y ON x.product = y.product
        '));
        $services = DB::select(DB::raw('
        SELECT c.name AS category, service, size, SUM(serviceCount) AS total FROM(
            SELECT s.name AS service, s.categoryId AS category, s.size AS size, COUNT(*) AS serviceCount FROM service AS s
            JOIN job_service AS js ON js.serviceId = s.id
            WHERE s.isActive=1 AND js.isComplete=1
            GROUP BY category,service,size
            UNION
            SELECT s.name AS service, s.categoryId AS category, s.size AS size, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS serviceCount FROM service AS s
            JOIN package_service AS ps ON ps.serviceId = s.id
            JOIN job_package AS jp ON jp.packageId = ps.packageId
            WHERE s.isActive=1
            GROUP BY category,service,size
            UNION
            SELECT s.name AS service, s.categoryId AS category, s.size AS size, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS serviceCount FROM service AS s
            JOIN promo_service AS ps ON ps.serviceId = s.id
            JOIN job_promo AS jp ON jp.promoId = ps.promoId
            WHERE s.isActive=1
            GROUP BY category,service,size
        )AS result
        JOIN service_category AS c ON c.id = result.category
        GROUP BY category,service,size
        ORDER BY total DESC
        '));
        return View('report.index',compact('dateStart','dateEnd','dateString','jobs','sales','inventory','services'));
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


    public function report($id)
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

     public function where(Request $request)
     {

     }
}
