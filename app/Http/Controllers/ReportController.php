<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilities;
use DB;
use PDF;

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
        SELECT CONCAT_WS(" ",c.firstName,c.middleName,c.lastName) AS customer, v.plate AS plate, vd.name AS model, vk.name AS make, v.isManual AS isManual, jh.*, SUM(CASE WHEN jp.isCredit=0 THEN jp.paid END) AS cash, SUM(CASE WHEN jp.isCredit=1 THEN jp.paid END) AS credit FROM job_header as jh
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
        $discrepancy = DB::select(DB::raw('
        SELECT p.id AS id, p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, SUM(CASE WHEN ISNULL(dp.quantity) THEN 0 ELSE dp.quantity END) AS total FROM product AS p
        JOIN damage_product AS dp ON dp.productId = p.id
        JOIN product_type AS pt ON pt.id = p.typeId
        JOIN product_brand AS pb ON pb.id = p.brandId
        JOIN product_variance AS pv ON pv.id = p.varianceId
        WHERE p.isActive = 1
        GROUP BY id,type,brand,variance,product,original,description
        '));
        $analysis = DB::select(DB::raw('
        SELECT p.id AS id,p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, pd.price AS price, s.name AS supplier, pd.created_at AS ordered FROM product AS p
        LEFT JOIN purchase_detail AS pd ON pd.productId = p.id
        JOIN purchase_header AS ph ON ph.id = pd.purchaseId
        JOIN supplier AS s ON s.id = ph.supplierId
        JOIN product_type AS pt ON pt.id = p.typeId
        JOIN product_brand AS pb ON pb.id = p.brandId
        JOIN product_variance AS pv ON pv.id = p.varianceId
        GROUP BY id,supplier,type,brand,variance,product,original,description,price,ordered
        '));
        return View('report.index',compact('dateStart','dateEnd','dateString','jobs','sales','inventory','services','discrepancy','analysis'));
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
        $util = Utilities::findOrFail(1);
        $dateStart = date('Y-m-d H:i:s', strtotime($util->created_at));
        $date = $request->date;
        $dates = explode('-',$request->date); // two dates MM/DD/YYYY-MM/DD/YYYY
        $startDate = explode('/',$dates[0]); // MM[0] DD[1] YYYY[2]
        $minus = $startDate[1]-1;
        $dateEnd = "$startDate[2]-$startDate[0]-$minus 23:59:59";
        $finalStartDate = "$startDate[2]-$startDate[0]-$startDate[1] 00:00:00";
        $endDate = explode('/',$dates[1]); // MM[0] DD[1] YYYY[2] 
        $finalEndDate = "$endDate[2]-$endDate[0]-$endDate[1] 23:59:59";
        if($request->reportId=="1"){
            $jobs = DB::select(DB::raw('
            SELECT CONCAT_WS(" ",c.firstName,c.middleName,c.lastName) AS customer, v.plate AS plate, vd.name AS model, vk.name AS make, v.isManual AS isManual, jh.*, SUM(CASE WHEN jp.isCredit=0 THEN jp.paid ELSE 0 END) AS cash, SUM(CASE WHEN jp.isCredit=1 THEN jp.paid ELSE 0 END) AS credit, total-(SUM(CASE WHEN jp.isCredit=0 THEN jp.paid ELSE 0 END)+SUM(CASE WHEN jp.isCredit=1 THEN jp.paid ELSE 0 END)) AS balance FROM job_header as jh
            JOIN customer AS c ON c.id = jh.customerId
            JOIN vehicle AS v ON v.id = jh.vehicleId
            JOIN vehicle_model AS vd ON vd.id = v.modelId
            JOIN vehicle_make AS vk ON vk.id = vd.makeId
            LEFT JOIN job_payment AS jp ON jp.jobId = jh.id
            WHERE jh.isVoid = 0 AND jh.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
            GROUP BY jh.id
            ORDER BY jh.id
            '));
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            $pdf = PDF::loadview('pdf.jobReport',compact('date','jobs'))->setPaper([0,0,612,792]);
            return $pdf->stream('jobreport.pdf');
        }
        if($request->reportId=="2"){
            $sales = DB::select(DB::raw('
            SELECT CONCAT_WS(" ",c.firstName,c.middleName,c.lastName) AS customer, sh.*, SUM(sh.total) AS total FROM sales_header AS sh
            JOIN customer AS c ON c.id = sh.customerId
            WHERE sh.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
            GROUP BY sh.id
            ORDER BY sh.id
            '));
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            $pdf = PDF::loadview('pdf.salesReport',compact('date','sales'))->setPaper([0,0,612,792]);
            return $pdf->stream('salesreport.pdf');
        }
        if($request->reportId=="3"){
            $statement = DB::statement("SET @rank=0;");
            $inventory = DB::select(DB::raw('
            SELECT * FROM (
                SELECT pIdStart AS pId, product, deliveredStart, returnedStart, type, brand, variance, original, reorder, description, totalStart, (deliveredStart-returnedStart-totalStart) AS currentStart FROM (
                    SELECT p.id AS pIdStart, SUM(CASE WHEN ISNULL(dd.quantity) THEN 0 ELSE dd.quantity END) AS deliveredStart, SUM(CASE WHEN ISNULL(rd.quantity) THEN 0 ELSE rd.quantity END) AS returnedStart FROM product AS p
                    JOIN product_type AS pt ON pt.id = p.typeId
                    JOIN product_brand AS pb ON pb.id = p.brandId
                    JOIN product_variance AS pv ON pv.id = p.varianceId
                    JOIN inventory AS i ON i.productId = p.id
                    LEFT JOIN delivery_detail AS dd ON dd.productId = p.id AND dd.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                    LEFT JOIN return_detail AS rd ON rd.productId = p.id AND rd.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                    GROUP BY pIdStart
                ) AS x
                JOIN 
                ( 
                    SELECT pId, product, t.name AS type, b.name AS brand, v.name AS variance, original, reorder, description, SUM(productCount) AS totalStart FROM (
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity) THEN 0 ELSE sp.quantity END) AS productCount FROM product AS p
                        LEFT JOIN sales_product AS sp ON sp.productId = p.id AND sp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1 
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity*pp.quantity) THEN 0 ELSE sp.quantity*pp.quantity END) AS productCount FROM product AS p
                        JOIN package_product AS pp ON pp.productId = p.id
                        LEFT JOIN sales_package AS sp ON sp.packageId = pp.packageId AND sp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity*pp.quantity) THEN 0 ELSE sp.quantity*pp.quantity END) AS productCount FROM product AS p
                        JOIN promo_product AS pp ON pp.productId = p.id
                        LEFT JOIN sales_promo AS sp ON sp.promoId = pp.promoId AND sp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS productCount FROM product AS p
                        LEFT JOIN job_product AS jp ON jp.productId = p.id AND jp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed*pp.quantity) THEN 0 ELSE jp.completed*pp.quantity END) AS productCount FROM product AS p
                        JOIN package_product AS pp ON pp.productId = p.id
                        LEFT JOIN job_package AS jp ON jp.packageId = pp.packageId AND jp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed*pp.quantity) THEN 0 ELSE jp.completed*pp.quantity END) AS productCount FROM product AS p
                        JOIN promo_product AS pp ON pp.productId = p.id
                        LEFT JOIN job_promo AS jp ON jp.promoId = pp.promoId AND jp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                    ) AS result
                    JOIN product_type AS t ON t.id = result.type
                    JOIN product_brand AS b ON b.id = result.brand
                    JOIN product_variance AS v ON v.id = result.variance
                    GROUP BY pId,type,brand,variance,product,original,reorder,description
                    ORDER BY totalStart DESC
                ) AS y ON x.pIdStart = y.pId
            ) AS resultX
            JOIN (
                SELECT pIdEnd AS pId, product, deliveredEnd, returnedEnd, type, brand, variance, original, reorder, description, totalEnd, (deliveredEnd-returnedEnd-totalEnd) AS currentEnd, @rank:=@rank+1 AS rank FROM (
                    SELECT p.id AS pIdEnd, SUM(CASE WHEN ISNULL(dd.quantity) THEN 0 ELSE dd.quantity END) AS deliveredEnd, SUM(CASE WHEN ISNULL(rd.quantity) THEN 0 ELSE rd.quantity END) AS returnedEnd FROM product AS p
                    JOIN product_type AS pt ON pt.id = p.typeId
                    JOIN product_brand AS pb ON pb.id = p.brandId
                    JOIN product_variance AS pv ON pv.id = p.varianceId
                    JOIN inventory AS i ON i.productId = p.id
                    LEFT JOIN delivery_detail AS dd ON dd.productId = p.id AND dd.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                    LEFT JOIN return_detail AS rd ON rd.productId = p.id AND rd.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                    GROUP BY pIdEnd
                ) AS x
                JOIN 
                ( 
                    SELECT pId, product, t.name AS type, b.name AS brand, v.name AS variance, original, reorder, description, SUM(productCount) AS totalEnd FROM (
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity) THEN 0 ELSE sp.quantity END) AS productCount FROM product AS p
                        LEFT JOIN sales_product AS sp ON sp.productId = p.id AND sp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1 
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity*pp.quantity) THEN 0 ELSE sp.quantity*pp.quantity END) AS productCount FROM product AS p
                        JOIN package_product AS pp ON pp.productId = p.id
                        LEFT JOIN sales_package AS sp ON sp.packageId = pp.packageId AND sp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity*pp.quantity) THEN 0 ELSE sp.quantity*pp.quantity END) AS productCount FROM product AS p
                        JOIN promo_product AS pp ON pp.productId = p.id
                        LEFT JOIN sales_promo AS sp ON sp.promoId = pp.promoId AND sp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS productCount FROM product AS p
                        LEFT JOIN job_product AS jp ON jp.productId = p.id AND jp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed*pp.quantity) THEN 0 ELSE jp.completed*pp.quantity END) AS productCount FROM product AS p
                        JOIN package_product AS pp ON pp.productId = p.id
                        LEFT JOIN job_package AS jp ON jp.packageId = pp.packageId AND jp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed*pp.quantity) THEN 0 ELSE jp.completed*pp.quantity END) AS productCount FROM product AS p
                        JOIN promo_product AS pp ON pp.productId = p.id
                        LEFT JOIN job_promo AS jp ON jp.promoId = pp.promoId AND jp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                    ) AS result
                    JOIN product_type AS t ON t.id = result.type
                    JOIN product_brand AS b ON b.id = result.brand
                    JOIN product_variance AS v ON v.id = result.variance
                    GROUP BY pId,type,brand,variance,product,original,reorder,description
                    ORDER BY totalEnd DESC
                ) AS y ON x.pIdEnd = y.pId
            ) AS resultY ON resultX.pId = resultY.pId
            '));
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            $pdf = PDF::loadview('pdf.inventoryReport',compact('date','inventory'))->setPaper([0,0,612,792]);
            return $pdf->stream('inventoryreport.pdf');
        }
        if($request->reportId=="4"){
            $statement = DB::statement("SET @rank=0;");
            $services = DB::select(DB::raw('
            SELECT * FROM (
                SELECT s.id AS s_sId, s.name AS s_service, sc.name AS s_category, s.size AS s_size FROM service AS s
                JOIN service_category AS sc ON sc.id = s.categoryId
                WHERE s.isActive = 1
            )AS x
            LEFT JOIN (
                SELECT sId, c.name AS category, service, size, SUM(CASE WHEN ISNULL(serviceCount) THEN 0 ELSE serviceCount END) AS total, @rank:=@rank+1 AS rank FROM(
                    SELECT s.id AS sId, s.name AS service, s.categoryId AS category, s.size AS size, COUNT(*) AS serviceCount FROM service AS s
                    LEFT JOIN job_service AS js ON js.serviceId = s.id
                    WHERE s.isActive=1 AND js.isComplete=1 AND js.updated_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                    GROUP BY sId,category,service,size
                    UNION
                    SELECT s.id AS sId, s.name AS service, s.categoryId AS category, s.size AS size, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS serviceCount FROM service AS s
                    JOIN package_service AS ps ON ps.serviceId = s.id
                    LEFT JOIN job_package AS jp ON jp.packageId = ps.packageId AND jp.updated_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                    WHERE s.isActive=1
                    GROUP BY sId,category,service,size
                    UNION
                    SELECT s.id AS sId, s.name AS service, s.categoryId AS category, s.size AS size, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS serviceCount FROM service AS s
                    JOIN promo_service AS ps ON ps.serviceId = s.id
                    LEFT JOIN job_promo AS jp ON jp.promoId = ps.promoId AND jp.updated_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                    WHERE s.isActive=1
                    GROUP BY sId,category,service,size
                )AS result
                JOIN service_category AS c ON c.id = result.category
                GROUP BY sId,category,service,size
                ORDER BY total DESC
            ) AS y ON x.s_sId = y.sId
            '));
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            $pdf = PDF::loadview('pdf.serviceReport',compact('date','services'))->setPaper([0,0,612,792]);
            return $pdf->stream('servicereport.pdf');
        }
        if($request->reportId=="5"){
            $discrepancy = DB::select(DB::raw('
            SELECT p.id AS id, p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, SUM(CASE WHEN ISNULL(dp.quantity) THEN 0 ELSE dp.quantity END) AS total FROM product AS p
            JOIN damage_product AS dp ON dp.productId = p.id AND dp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            GROUP BY id,type,brand,variance,product,original,description
            '));
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            $pdf = PDF::loadview('pdf.discrepancyReport',compact('date','discrepancy'))->setPaper([0,0,612,792]);
            return $pdf->stream('discrepancyreport.pdf');
        }
        if($request->reportId=="6"){
            $analysis = DB::select(DB::raw('
            SELECT p.id AS id,p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, pd.price AS price, s.name AS supplier, pd.created_at AS ordered FROM product AS p
            LEFT JOIN purchase_detail AS pd ON pd.productId = p.id AND pd.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
            JOIN purchase_header AS ph ON ph.id = pd.purchaseId
            JOIN supplier AS s ON s.id = ph.supplierId
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            GROUP BY id,supplier,type,brand,variance,product,original,description,price,ordered
            '));
            PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            $pdf = PDF::loadview('pdf.priceReport',compact('date','analysis'))->setPaper([0,0,612,792]);
            return $pdf->stream('pricereport.pdf');
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

     public function filter(Request $request)
     {
        $util = Utilities::findOrFail(1);
        $dateStart = date('Y-m-d H:i:s', strtotime($util->created_at));
        $dates = explode('-',$request->date); // two dates MM/DD/YYYY-MM/DD/YYYY
        $startDate = explode('/',$dates[0]); // MM[0] DD[1] YYYY[2]
        $minus = $startDate[1]-1;
        $dateEnd = "$startDate[2]-$startDate[0]-$minus 23:59:59";
        $finalStartDate = "$startDate[2]-$startDate[0]-$startDate[1] 00:00:00";
        $endDate = explode('/',$dates[1]); // MM[0] DD[1] YYYY[2] 
        $finalEndDate = "$endDate[2]-$endDate[0]-$endDate[1] 23:59:59";
        if($request->reportId=="1"){
            $jobs = DB::select(DB::raw('
            SELECT CONCAT_WS(" ",c.firstName,c.middleName,c.lastName) AS customer, v.plate AS plate, vd.name AS model, vk.name AS make, v.isManual AS isManual, jh.*, SUM(CASE WHEN jp.isCredit=0 THEN jp.paid ELSE 0 END) AS cash, SUM(CASE WHEN jp.isCredit=1 THEN jp.paid ELSE 0 END) AS credit, total-(SUM(CASE WHEN jp.isCredit=0 THEN jp.paid ELSE 0 END)+SUM(CASE WHEN jp.isCredit=1 THEN jp.paid ELSE 0 END)) AS balance FROM job_header as jh
            JOIN customer AS c ON c.id = jh.customerId
            JOIN vehicle AS v ON v.id = jh.vehicleId
            JOIN vehicle_model AS vd ON vd.id = v.modelId
            JOIN vehicle_make AS vk ON vk.id = vd.makeId
            LEFT JOIN job_payment AS jp ON jp.jobId = jh.id
            WHERE jh.isVoid = 0 AND jh.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
            GROUP BY jh.id
            ORDER BY jh.id
            '));
            return response()->json(['data'=>$jobs]);
        }
        if($request->reportId=="2"){
            $sales = DB::select(DB::raw('
            SELECT CONCAT_WS(" ",c.firstName,c.middleName,c.lastName) AS customer, sh.*, SUM(sh.total) AS total FROM sales_header AS sh
            JOIN customer AS c ON c.id = sh.customerId
            WHERE sh.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
            GROUP BY sh.id
            ORDER BY sh.id
            '));
            return response()->json(['data'=>$sales]);
        }
        if($request->reportId=="3"){
            $statement = DB::statement("SET @rank=0;");
            $inventory = DB::select(DB::raw('
            SELECT * FROM (
                SELECT pIdStart AS pId, product, deliveredStart, returnedStart, type, brand, variance, original, reorder, description, totalStart, (deliveredStart-returnedStart-totalStart) AS currentStart FROM (
                    SELECT p.id AS pIdStart, SUM(CASE WHEN ISNULL(dd.quantity) THEN 0 ELSE dd.quantity END) AS deliveredStart, SUM(CASE WHEN ISNULL(rd.quantity) THEN 0 ELSE rd.quantity END) AS returnedStart FROM product AS p
                    JOIN product_type AS pt ON pt.id = p.typeId
                    JOIN product_brand AS pb ON pb.id = p.brandId
                    JOIN product_variance AS pv ON pv.id = p.varianceId
                    JOIN inventory AS i ON i.productId = p.id
                    LEFT JOIN delivery_detail AS dd ON dd.productId = p.id AND dd.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                    LEFT JOIN return_detail AS rd ON rd.productId = p.id AND rd.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                    GROUP BY pIdStart
                ) AS x
                JOIN 
                ( 
                    SELECT pId, product, t.name AS type, b.name AS brand, v.name AS variance, original, reorder, description, SUM(productCount) AS totalStart FROM (
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity) THEN 0 ELSE sp.quantity END) AS productCount FROM product AS p
                        LEFT JOIN sales_product AS sp ON sp.productId = p.id AND sp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1 
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity*pp.quantity) THEN 0 ELSE sp.quantity*pp.quantity END) AS productCount FROM product AS p
                        JOIN package_product AS pp ON pp.productId = p.id
                        LEFT JOIN sales_package AS sp ON sp.packageId = pp.packageId AND sp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity*pp.quantity) THEN 0 ELSE sp.quantity*pp.quantity END) AS productCount FROM product AS p
                        JOIN promo_product AS pp ON pp.productId = p.id
                        LEFT JOIN sales_promo AS sp ON sp.promoId = pp.promoId AND sp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS productCount FROM product AS p
                        LEFT JOIN job_product AS jp ON jp.productId = p.id AND jp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed*pp.quantity) THEN 0 ELSE jp.completed*pp.quantity END) AS productCount FROM product AS p
                        JOIN package_product AS pp ON pp.productId = p.id
                        LEFT JOIN job_package AS jp ON jp.packageId = pp.packageId AND jp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed*pp.quantity) THEN 0 ELSE jp.completed*pp.quantity END) AS productCount FROM product AS p
                        JOIN promo_product AS pp ON pp.productId = p.id
                        LEFT JOIN job_promo AS jp ON jp.promoId = pp.promoId AND jp.created_at BETWEEN "'.$dateStart.'" AND "'.$dateEnd.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                    ) AS result
                    JOIN product_type AS t ON t.id = result.type
                    JOIN product_brand AS b ON b.id = result.brand
                    JOIN product_variance AS v ON v.id = result.variance
                    GROUP BY pId,type,brand,variance,product,original,reorder,description
                    ORDER BY totalStart DESC
                ) AS y ON x.pIdStart = y.pId
            ) AS resultX
            JOIN (
                SELECT pIdEnd AS pId, product, deliveredEnd, returnedEnd, type, brand, variance, original, reorder, description, totalEnd, (deliveredEnd-returnedEnd-totalEnd) AS currentEnd, @rank:=@rank+1 AS rank FROM (
                    SELECT p.id AS pIdEnd, SUM(CASE WHEN ISNULL(dd.quantity) THEN 0 ELSE dd.quantity END) AS deliveredEnd, SUM(CASE WHEN ISNULL(rd.quantity) THEN 0 ELSE rd.quantity END) AS returnedEnd FROM product AS p
                    JOIN product_type AS pt ON pt.id = p.typeId
                    JOIN product_brand AS pb ON pb.id = p.brandId
                    JOIN product_variance AS pv ON pv.id = p.varianceId
                    JOIN inventory AS i ON i.productId = p.id
                    LEFT JOIN delivery_detail AS dd ON dd.productId = p.id AND dd.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                    LEFT JOIN return_detail AS rd ON rd.productId = p.id AND rd.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                    GROUP BY pIdEnd
                ) AS x
                JOIN 
                ( 
                    SELECT pId, product, t.name AS type, b.name AS brand, v.name AS variance, original, reorder, description, SUM(productCount) AS totalEnd FROM (
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity) THEN 0 ELSE sp.quantity END) AS productCount FROM product AS p
                        LEFT JOIN sales_product AS sp ON sp.productId = p.id AND sp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1 
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity*pp.quantity) THEN 0 ELSE sp.quantity*pp.quantity END) AS productCount FROM product AS p
                        JOIN package_product AS pp ON pp.productId = p.id
                        LEFT JOIN sales_package AS sp ON sp.packageId = pp.packageId AND sp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(sp.quantity*pp.quantity) THEN 0 ELSE sp.quantity*pp.quantity END) AS productCount FROM product AS p
                        JOIN promo_product AS pp ON pp.productId = p.id
                        LEFT JOIN sales_promo AS sp ON sp.promoId = pp.promoId AND sp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS productCount FROM product AS p
                        LEFT JOIN job_product AS jp ON jp.productId = p.id AND jp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed*pp.quantity) THEN 0 ELSE jp.completed*pp.quantity END) AS productCount FROM product AS p
                        JOIN package_product AS pp ON pp.productId = p.id
                        LEFT JOIN job_package AS jp ON jp.packageId = pp.packageId AND jp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                        UNION
                        SELECT p.id AS pId, p.name AS product, p.typeId AS type, p.brandId AS brand, p.varianceId AS variance, p.isOriginal AS original, p.reorder AS reorder, p.description AS description, SUM(CASE WHEN ISNULL(jp.completed*pp.quantity) THEN 0 ELSE jp.completed*pp.quantity END) AS productCount FROM product AS p
                        JOIN promo_product AS pp ON pp.productId = p.id
                        LEFT JOIN job_promo AS jp ON jp.promoId = pp.promoId AND jp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                        WHERE p.isActive=1 AND jp.isActive=1
                        GROUP BY pId,type,brand,variance,product,original,reorder,description
                    ) AS result
                    JOIN product_type AS t ON t.id = result.type
                    JOIN product_brand AS b ON b.id = result.brand
                    JOIN product_variance AS v ON v.id = result.variance
                    GROUP BY pId,type,brand,variance,product,original,reorder,description
                    ORDER BY totalEnd DESC
                ) AS y ON x.pIdEnd = y.pId
            ) AS resultY ON resultX.pId = resultY.pId
            '));
            return response()->json(['data'=>$inventory]);
        }
        if($request->reportId=="4"){
            $statement = DB::statement("SET @rank=0;");
            $services = DB::select(DB::raw('
            SELECT * FROM (
                SELECT s.id AS s_sId, s.name AS s_service, sc.name AS s_category, s.size AS s_size FROM service AS s
                JOIN service_category AS sc ON sc.id = s.categoryId
                WHERE s.isActive = 1
            )AS x
            LEFT JOIN (
                SELECT sId, c.name AS category, service, size, SUM(CASE WHEN ISNULL(serviceCount) THEN 0 ELSE serviceCount END) AS total, @rank:=@rank+1 AS rank FROM(
                    SELECT s.id AS sId, s.name AS service, s.categoryId AS category, s.size AS size, COUNT(*) AS serviceCount FROM service AS s
                    LEFT JOIN job_service AS js ON js.serviceId = s.id
                    WHERE s.isActive=1 AND js.isComplete=1 AND js.updated_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                    GROUP BY sId,category,service,size
                    UNION
                    SELECT s.id AS sId, s.name AS service, s.categoryId AS category, s.size AS size, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS serviceCount FROM service AS s
                    JOIN package_service AS ps ON ps.serviceId = s.id
                    LEFT JOIN job_package AS jp ON jp.packageId = ps.packageId AND jp.updated_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                    WHERE s.isActive=1
                    GROUP BY sId,category,service,size
                    UNION
                    SELECT s.id AS sId, s.name AS service, s.categoryId AS category, s.size AS size, SUM(CASE WHEN ISNULL(jp.completed) THEN 0 ELSE jp.completed END) AS serviceCount FROM service AS s
                    JOIN promo_service AS ps ON ps.serviceId = s.id
                    LEFT JOIN job_promo AS jp ON jp.promoId = ps.promoId AND jp.updated_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
                    WHERE s.isActive=1
                    GROUP BY sId,category,service,size
                )AS result
                JOIN service_category AS c ON c.id = result.category
                GROUP BY sId,category,service,size
                ORDER BY total DESC
            ) AS y ON x.s_sId = y.sId
            '));
            return response()->json(['data'=>$services]);
        }
        if($request->reportId=="5"){
            $discrepancy = DB::select(DB::raw('
            SELECT p.id AS id, p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, SUM(CASE WHEN ISNULL(dp.quantity) THEN 0 ELSE dp.quantity END) AS total FROM product AS p
            JOIN damage_product AS dp ON dp.productId = p.id AND dp.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            GROUP BY id,type,brand,variance,product,original,description
            '));
            return response()->json(['data'=>$discrepancy]);
        }
        if($request->reportId=="6"){
            $analysis = DB::select(DB::raw('
            SELECT p.id AS id,p.name AS product, pt.name AS type, pb.name AS brand, pv.name AS variance, p.isOriginal AS original, p.description AS description, pd.price AS price, s.name AS supplier, pd.created_at AS ordered FROM product AS p
            LEFT JOIN purchase_detail AS pd ON pd.productId = p.id AND pd.created_at BETWEEN "'.$finalStartDate.'" AND "'.$finalEndDate.'"
            JOIN purchase_header AS ph ON ph.id = pd.purchaseId
            JOIN supplier AS s ON s.id = ph.supplierId
            JOIN product_type AS pt ON pt.id = p.typeId
            JOIN product_brand AS pb ON pb.id = p.brandId
            JOIN product_variance AS pv ON pv.id = p.varianceId
            GROUP BY id,supplier,type,brand,variance,product,original,description,price,ordered
            '));
            return response()->json(['data'=>$analysis]);
        }
     }
}
