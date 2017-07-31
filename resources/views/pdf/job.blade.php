<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
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
            font-size: 14px;
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
        th{
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
        <div class="center header">
            Rapide
        </div>
        <div style="float:right">
            {{date('F j, Y', strtotime($job->created_at))}}<br>
            <label style="color:red">{{$jobId}}</label>
        </div>
        <div style="clear:both"></div>
        <div class="center">
            <label>AUTO SERVICE CENTER</label>
        </div>
        <div class="col-md-12 border center">
            JOB ORDER
        </div><br>
        <div style="float:left" class="col-md-6">
            Customer: {{$job->customer->firstName}} {{$job->customer->middleName}} {{$job->customer->lastName}}<br>
            Address: {{$job->customer->street}} {{$job->customer->brgy}} {{$job->customer->city}}<br>
            Phone Number: {{$job->customer->contact}}<br>
            Email: {{$job->customer->email}}<br>
        </div>
        <div style="float:right" class="col-md-6">
            Plate: {{$job->vehicle->plate}}<br>
            Make: {{$job->vehicle->model->make->name}}<br>
            Model: {{$job->vehicle->model->name}}<br>
            Year: {{$job->vehicle->model->year}}<br>
            Transmission: {{$job->vehicle->model->transmission}}<br>
            Mileage: {{$job->vehicle->mileage}}<br>
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
                @foreach($job->product as $product)
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
                            $discount = $product->product->discount->header->rateRecord->where('created_at','<=',$job->created_at)->first()->rate;
                        }else{
                            $dis = $product->product->discountRecord->where('created_at','<=',$job->created_at)->where('updated_at','>=',$job->created_at);
                            if(count($dis) > 0){
                                $discount = $dis->first()->header->rateRecord->where('created_at','<=',$job->created_at)->first()->rate;
                            }
                        }
                        $price = $product->product->priceRecord->where('created_at','<=',$job->created_at)->first()->price;
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
                @foreach($job->service as $service)
                <tr>
                    <?php
                        $discount = null;
                        if($service->service->discount!=null){
                            $discount = $service->service->discount->header->rateRecord->where('created_at','<=',$job->created_at)->first()->rate;
                        }else{
                            $dis = $service->service->discountRecord->where('created_at','<=',$job->created_at)->where('updated_at','>=',$job->created_at);
                            if(count($dis) > 0){
                                $discount = $dis->first()->header->rateRecord->where('created_at','<=',$job->created_at)->first()->rate;
                            }
                        }
                        $price = $service->service->priceRecord->where('created_at','<=',$job->created_at)->first()->price;
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
                @foreach($job->package as $package)
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
                            *{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}}) x {{number_format($package->quantity)}} pcs.<br>
                        @endforeach
                        <br>
                        @foreach($package->package->service as $service)
                            *{{$service->service->name}} - {{$service->service->size}} ({{$service->service->category->name}})<br>
                        @endforeach
                    </td>
                    <?php
                        $price = $package->package->priceRecord->where('created_at','<=',$job->created_at)->first()->price;
                    ?>
                    <td class="text-right">{{number_format($price,2)}}</td>
                    <td class="text-right">{{number_format($package->quantity*$price,2)}}</td>
                    <?php
                        $total += $package->quantity*$price;
                    ?>
                </tr>
                @endforeach
                {{-- promo --}}
                @foreach($job->promo as $promo)
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
                            *{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}}) x {{number_format($promo->quantity)}} pcs.<br>
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
                            *{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}}) x {{number_format($promo->quantity)}} pcs.<br>
                        @endforeach
                        @foreach($promo->promo->freeService as $service)
                            *{{$service->service->name}} - {{$service->service->size}} ({{$service->service->category->name}})<br>
                        @endforeach
                    </td>
                    <?php
                        $price = $promo->promo->priceRecord->where('created_at','<=',$job->created_at)->first()->price;
                    ?>
                    <td class="text-right">{{number_format($price,2)}}</td>
                    <td class="text-right">{{number_format($promo->quantity*$price,2)}}</td>
                    <?php
                        $total += $promo->quantity*$price;
                    ?>
                </tr>
                @endforeach
                @if($job->discount)
                    <tr>
                        <td></td>
                        <td>{{$job->discount->discount->name}} - DISCOUNT</td>
                        <td class="text-right">{{$job->discount->discount->rateRecord->where('created_at','<=',$job->created_at)->first()->rate}} %</td>
                        <td class="text-right">-{{number_format($total*($job->discount->discount->rateRecord->where('created_at','<=',$job->created_at)->first()->rate/100),2)}}</td>
                        <?php 
                            $discounts += $total*($job->discount->discount->rateRecord->where('created_at','<=',$job->created_at)->first()->rate/100);
                        ?>
                    </tr>
                @endif
                <tr>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td class="text-right">PhP {{number_format($total-$discounts,2)}}</td>
                </tr>
            </tbody>
        </table>
        <div class="footer">
            <div style="float:left" class="col-md-6">
                This serves as an job only.<br>
                STORE MANAGER: ______________________<br>
                ADMIN OFFICER: ______________________<br> 
            </div>
            <div style="float:right" class="col-md-6">
                <br>
                CUSTOMER'S SIGNATURE: ___________________<br>
                GRAND TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: PhP {{number_format($total-$discounts,2)}}<br> 
            </div>
            <br><br>
            <div class="footerd">Printed by: Admin {{$date}}</div>
        </div>
    </body>
</html>