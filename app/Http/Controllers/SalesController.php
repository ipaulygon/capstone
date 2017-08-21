<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\SalesHeader;
use App\SalesProduct;
use App\SalesPackage;
use App\SalesPromo;
use App\SalesDiscount;
use App\Customer;
use App\Inventory;
use Validator;
use Redirect;
use Response;
use Session;
use DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon as Carbon;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = SalesHeader::get();
        return View('sales.index',compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $date = date('Y-m-d');
        $customers = DB::table('customer')
            ->select('customer.*')
            ->get();
        $products = DB::table('product as p')
            ->join('product_type as pt','pt.id','p.typeId')
            ->join('product_brand as pb','pb.id','p.brandId')
            ->join('product_variance as pv','pv.id','p.varianceId')
            ->where('p.isActive',1)
            ->select('p.*','pt.name as type','pb.name as brand','pv.name as variance')
            ->get();
        $packages = DB::table('package as p')
            ->where(DB::raw('(SELECT COUNT(*) FROM package_service WHERE package_service.packageId=p.id)'),0)
            ->where('p.isActive',1)
            ->select('p.*')
            ->get();
        $promos = DB::table('promo as p')
            ->where(DB::raw('(SELECT COUNT(*) FROM promo_service WHERE promo_service.promoId=p.id)'),0)
            ->where('dateStart','>=',$date)
            ->where('dateEnd','<=',$date)
            ->where('p.isActive',1)
            ->select('p.*')
            ->get();
        $discounts = DB::table('discount as d')
            ->where('d.isActive',1)
            ->where('d.type','Whole')
            ->select('d.*')
            ->get();
        return View('sales.create',compact('date','customers','products','packages','promos','discounts'));
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
            'product.*' => 'sometimes|required',
            'productQty.*' => 'sometimes|required|numeric',
            'package.*' => 'sometimes|required',
            'packageQty.*' => 'sometimes|required|numeric',
            'promo.*' => 'sometimes|required',
            'promoQty.*' => 'sometimes|required|numeric',
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
            'product.*' => 'Product',
            'productQty.*' => 'Product Quantity',
            'package.*' => 'Package',
            'packageQty.*' => 'Package Quantity',
            'promo.*' => 'Promo Quantity',
            'promoQty.*' => 'Promo Quantity',
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
                $sales = SalesHeader::create([
                    'customerId' => $customer->id,
                    'total' => str_replace(',','',$request->computed),
                ]);
                $products = $request->product;
                $prodQty = $request->productQty;
                $packages = $request->package;
                $packQty = $request->packageQty;
                $promos = $request->promo;
                $promoQty = $request->promoQty;
                $discounts = $request->discount;
                if(!empty($products)){
                    foreach($products as $key=>$product){
                        SalesProduct::create([
                            'salesId' => $sales->id,
                            'productId' => $product,
                            'quantity' => $prodQty[$key],
                            'isActive' => 1
                        ]);
                    }
                }
                if(!empty($packages)){
                    foreach($packages as $key=>$package){
                        SalesPackage::create([
                            'salesId' => $sales->id,
                            'packageId' => $package,
                            'quantity' => $packQty[$key],
                        ]);
                    }
                }
                if(!empty($promos)){
                    foreach($promos as $key=>$promo){
                        SalesPromo::create([
                            'salesId' => $sales->id,
                            'promoId' => $promo,
                            'quantity' => $promoQty[$key],
                        ]);
                    }
                }
                if(!empty($discounts)){
                    foreach($discounts as $key=>$discount){
                        SalesDiscount::create([
                            'salesId' => $sales->id,
                            'discountId' => $discount,
                        ]);
                    }
                }
                DB::commit();
            }catch(\Illuminate\Database\QueryException $e){
                DB::rollBack();
                $errMess = $e->getMessage();
                return Redirect::back()->withErrors($errMess);
            }
            $request->session()->flash('success', 'Successfully added.');  
            return Redirect('sales');
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
}
