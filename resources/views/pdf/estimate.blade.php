<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{$util->name}} | Repair Estimate</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <style type="text/css">
        @page{
            margin-top: 1cm;
            margin-bottom: -0.75cm;
        }
        body{
            font-family: "SegoeUI","Sans-serif";
            font-size: 12px;
        }
        .header{
            font-size: 20px!important;
        }
        .page-break {
            page-break-after: always;
        }
        .center{
            text-align: center;
        }
        .col-md-12{
            width: 100%;
        }
        .col-md-6{
            width: 50%;
        }
        .border{
            border: 1px solid black;
        }
        .text-right{
            text-align: right;
        }
        table{
            clear: both;
            border: 1px solid black
        }
        tbody tr{
            border: 1px solid black;
            padding: 10px;
        }
        tr:nth-child(even) {
            background-color: #e6e6e6
        }
        thead th{
            background-color: black;
            color: white;
        }
        .footer{
            position: absolute;
            bottom: 0;
            margin-bottom: 60px;
        }
        .footerd{
            font-size: 0.8em;
        }
    </style>
    <body>
        <div style="float:left">
            <img src="{{$util->image}}" width="50px" height="50px">
        </div>
        <div class="center header">
            {{$util->name}}
        </div>
        <div style="float:right">
            {{date('F j, Y', strtotime($estimate->created_at))}}<br>
            <label style="color:red">{{$estId}}</label><br>
            <label>Reference: {{'JOB'.str_pad($estimate->jobId, 5, '0', STR_PAD_LEFT)}}</label>
        </div>
        <div style="clear:both"></div>
        <div class="center">
            <label>AUTO SERVICE CENTER</label>
        </div>
        <div class="col-md-12 border center">
            ESTIMATE REPAIR
        </div><br>
        <div style="float:left" class="col-md-6">
            Customer: {{$estimate->customer->firstName}} {{$estimate->customer->middleName}} {{$estimate->customer->lastName}}<br>
            Address: {{$estimate->customer->street}} {{$estimate->customer->brgy}} {{$estimate->customer->city}}<br>
            Phone Number: {{$estimate->customer->contact}}<br>
            Email: {{$estimate->customer->email}}<br>
        </div>
        <div style="float:right" class="col-md-6">
            <?php $transmission = ($estimate->vehicle->isManual ? 'MT' : 'AT')?>
            Plate: {{$estimate->vehicle->plate}}<br>
            Make: {{$estimate->vehicle->model->make->name}}<br>
            Model: {{$estimate->vehicle->model->name}}<br>
            Year: {{$estimate->vehicle->model->year}}<br>
            Transmission: {{$transmission}}<br>
            Mileage: {{$estimate->vehicle->mileage}}<br>
        </div>
        <div style="clear:both"></div>
        <br>
        <table width="100%">
            <thead>
                <tr>
                    <th width="10%">Qty</th>
                    <th>Items</th>
                    <th width="15%" class="text-right">Unit Price</th>
                    <th width="15%" class="text-right">Total Price</th>
                </tr>
            </thead>
            <tbody>
                {{-- product --}}
                @foreach($estimate->product as $product)
                <tr>
                    <td class="text-right">{{number_format($product->quantity)}}</td>
                    <?php
                        if($product->product->isOriginal!=null){
                            $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                        }else{
                            $type = "";
                        }
                        $discount = null;
                        if($product->product->discount!=null){
                            $discount = $product->product->discount->header->rateRecord->where('created_at','<=',$estimate->created_at)->first()->rate;
                        }else{
                            $dis = $product->product->discountRecord->where('created_at','<=',$estimate->created_at)->where('updated_at','>=',$estimate->created_at);
                            if(count($dis) > 0){
                                $discount = $dis->first()->header->rateRecord->where('created_at','<=',$estimate->created_at)->first()->rate;
                            }
                        }
                        $price = $product->product->priceRecord->where('created_at','<=',$estimate->created_at)->first()->price;
                        if($discount!=null){
                            $price = $price-($price*($discount/100));
                            $discountString = '['.$discount.' % discount]';
                        }else{
                            $discountString = '';
                        }
                    ?>
                    <td>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}}) {{$discountString}}</td>
                    <td class="text-right">{{number_format($price,2)}}</td>
                    <td class="text-right">{{number_format($product->quantity*$price,2)}}</td>
                    <?php
                        $total += $product->quantity*$price;
                    ?>
                </tr>
                @endforeach
                {{-- service --}}
                @foreach($estimate->service as $service)
                <tr>
                    <?php
                        $discount = null;
                        if($service->service->discount!=null){
                            $discount = $service->service->discount->header->rateRecord->where('created_at','<=',$estimate->created_at)->first()->rate;
                        }else{
                            $dis = $service->service->discountRecord->where('created_at','<=',$estimate->created_at)->where('updated_at','>=',$estimate->created_at);
                            if(count($dis) > 0){
                                $discount = $dis->first()->header->rateRecord->where('created_at','<=',$estimate->created_at)->first()->rate;
                            }
                        }
                        $price = $service->service->priceRecord->where('created_at','<=',$estimate->created_at)->first()->price;
                        if($discount!=null){
                            $price = $price-($price*($discount/100));
                            $discountString = '['.$discount.' % discount]';
                        }else{
                            $discountString = '';
                        }
                    ?>
                    <td></td>
                    <td>{{$service->service->name}} - {{$service->service->size}} ({{$service->service->category->name}}) {{$discountString}}</td>
                    <td class="text-right">{{number_format($price,2)}}</td>
                    <td class="text-right">{{number_format($price,2)}}</td>
                    <?php
                        $total += $price;
                    ?>
                </tr>
                @endforeach
                {{-- package --}}
                @foreach($estimate->package as $package)
                <tr>
                    <td class="text-right">{{number_format($package->quantity)}}</td>
                    <td>
                        {{$package->package->name}}:<br>
                        @foreach($package->package->product as $product)
                            <?php
                                if($product->product->isOriginal!=null){
                                    $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                }else{
                                    $type = "";
                                }
                            ?>
                            *{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}}) x {{number_format($product->quantity)}} pcs.<br>
                        @endforeach
                        <br>
                        @foreach($package->package->service as $service)
                            *{{$service->service->name}} - {{$service->service->size}} ({{$service->service->category->name}})<br>
                        @endforeach
                    </td>
                    <?php
                        $price = $package->package->priceRecord->where('created_at','<=',$estimate->created_at)->first()->price;
                    ?>
                    <td class="text-right">{{number_format($price,2)}}</td>
                    <td class="text-right">{{number_format($package->quantity*$price,2)}}</td>
                    <?php
                        $total += $package->quantity*$price;
                    ?>
                </tr>
                @endforeach
                {{-- promo --}}
                @foreach($estimate->promo as $promo)
                <tr>
                    <td class="text-right">{{number_format($promo->quantity)}}</td>
                    <td>
                        {{$promo->promo->name}}:<br>
                        @foreach($promo->promo->product as $product)
                            <?php
                                if($product->product->isOriginal!=null){
                                    $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                }else{
                                    $type = "";
                                }
                            ?>
                            *{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}}) x {{number_format($product->quantity)}} pcs.<br>
                        @endforeach
                        @foreach($promo->promo->service as $service)
                            *{{$service->service->name}} - {{$service->service->size}} ({{$service->service->category->name}})<br>
                        @endforeach
                        Free:<br>
                        @foreach($promo->promo->freeProduct as $product)
                            <?php
                                if($product->product->isOriginal!=null){
                                    $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                }else{
                                    $type = "";
                                }
                            ?>
                            *{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}}) x {{number_format($product->quantity)}} pcs.<br>
                        @endforeach
                        @foreach($promo->promo->freeService as $service)
                            *{{$service->service->name}} - {{$service->service->size}} ({{$service->service->category->name}})<br>
                        @endforeach
                    </td>
                    <?php
                        $price = $promo->promo->priceRecord->where('created_at','<=',$estimate->created_at)->first()->price;
                    ?>
                    <td class="text-right">{{number_format($price,2)}}</td>
                    <td class="text-right">{{number_format($promo->quantity*$price,2)}}</td>
                    <?php
                        $total += $promo->quantity*$price;
                    ?>
                </tr>
                @endforeach
            </tbody>
            <tfoot id="tFoot">
            <?php
                $vatExempt = 0;
                if($util->isVat){
                    $getVat = 100 / (100+$util->vat);
                    $vatSales = $total*$getVat;
                    $vat = $vatSales*($util->vat/100);
                    if(count($estimate->discount)>0){
                        $vatExempt = ($estimate->discount->discount->isVatExempt ? $vat : 0);
                    }
                }
            ?>
            @if($util->isVat)
                <tr>
                    <th></th>
                    <th>VAT Sales</th>
                    <th></th>
                    <th class="text-right">{{number_format($vatSales,2)}}</th>
                </tr>
                <tr>
                    <th></th>
                    <th>VAT</th>
                    <th class="text-right">{{$util->vat}} %</th>
                    <th class="text-right">{{number_format($vat,2)}}</th>
                </tr>
                @if($vatExempt!=0)
                <tr>
                    <th></th>
                    <th>VAT Exemption</th>
                    <th></th>
                    <th class="text-right">-{{number_format($vatExempt,2)}}</th>
                </tr>
                @endif
            @endif
            @if($estimate->discount)
                <?php
                    $discountRate = $estimate->discount->discount->rateRecord->where('created_at','<=',$estimate->created_at)->first()->rate;
                    $discount = ($util->isVat && $estimate->discount->discount->isVatExempt ? $vatSales*($discountRate/100) : $total*$discount);
                ?>
                <tr>
                    <th></th>
                    <th>{{$estimate->discount->discount->name}} - DISCOUNT</th>
                    <th class="text-right">{{$discountRate}} %</th>
                    <th class="text-right">-{{number_format($discount,2)}}</th>
                </tr>
            @endif
                <tr>
                    <th></th>
                    <th></th>
                    <th>Total</th>
                    <th class="text-right">PhP {{number_format($total-$discount-$vatExempt,2)}}</th>
                </tr>
            <tfoot>
        </table>
        <div class="footer">
            <div style="float:left" class="col-md-6">
                This serves as an estimate only.<br>
                STORE MANAGER: ______________________<br>
                ADMIN OFFICER: ______________________<br> 
            </div>
            <div style="float:right;margin-top:-30px!important" class="col-md-6">
                CUSTOMER'S SIGNATURE: <img width="250" src="{{$picPath}}"><br>
                GRAND TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: PhP {{number_format($total-$discount-$vatExempt,2)}}<br> 
            </div>
            <br><br>
            <div class="footerd">Printed by: {{$userName}} {{$date}}</div>
        </div>
    </body>
</html>