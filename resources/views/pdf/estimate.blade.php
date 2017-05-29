<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <style type="text/css">
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
    </style>
    <body>
        <div class="center header">
            Rapide
        </div>
        <div style="float:right">
            {{date('F j, Y')}}<br>
            <label style="color:red">ESTIMATE{{$estimate->id}}</label>
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
            Address: {{$estimate->customer->address}}<br>
            Phone Number: {{$estimate->customer->contact}}<br>
            Email: {{$estimate->customer->email}}<br>
        </div>
        <div style="float:right" class="col-md-6">
            Plate: {{$estimate->vehicle->plate}}<br>
            Make: {{$estimate->vehicle->model->make->name}}<br>
            Model: {{$estimate->vehicle->model->name}}<br>
            Year: {{$estimate->vehicle->model->year}}<br>
            Transmission: {{$estimate->vehicle->model->transmission}}<br>
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
                            $part = "- ".$product->product->isOriginal;
                        }else{
                            $part = "";
                        }
                        $discount = null;
                        if($product->product->discount!=null){
                            $discount = $product->product->discount->header->rate;
                        }
                        if($discount!=null){
                            $price = $product->product->price-($product->product->price*($discount/100));
                            $discountString = '['.$discount.' % discount]';
                        }else{
                            $price = $product->product->price;
                            $discountString = '';
                        }
                    ?>
                    <td>{{$product->product->brand->name}} - {{$product->product->name}} {{$part}} ({{$product->product->variance->name}}) {{$discountString}}</td>
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
                            $discount = $service->service->discount->header->rate;
                        }
                        if($discount!=null){
                            $price = $service->service->price-($service->service->price*($discount/100));
                            $discountString = '['.$discount.' % discount]';
                        }else{
                            $price = $product->service->price;
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
                                    $part = "- ".$product->product->isOriginal;
                                }else{
                                    $part = "";
                                }
                            ?>
                            *{{$product->product->brand->name}} - {{$product->product->name}} {{$part}} ({{$product->product->variance->name}}) x {{number_format($package->quantity)}} pcs.<br>
                        @endforeach
                        <br>
                        @foreach($package->package->service as $service)
                            *{{$service->service->name}} - {{$service->service->size}} ({{$service->service->category->name}})<br>
                        @endforeach
                    </td>
                    <td class="text-right">{{number_format($package->package->price,2)}}</td>
                    <td class="text-right">{{number_format($package->quantity*$package->package->price,2)}}</td>
                    <?php
                        $total += $package->quantity*$package->package->price;
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
                                    $part = "- ".$product->product->isOriginal;
                                }else{
                                    $part = "";
                                }
                            ?>
                            *{{$product->product->brand->name}} - {{$product->product->name}} {{$part}} ({{$product->product->variance->name}}) x {{number_format($promo->quantity)}} pcs.<br>
                        @endforeach
                        @foreach($promo->promo->service as $service)
                            *{{$service->service->name}} - {{$service->service->size}} ({{$service->service->category->name}})<br>
                        @endforeach
                        Free:<br>
                        @foreach($promo->promo->freeProduct as $product)
                            <?php
                                if($product->product->isOriginal!=null){
                                    $part = "- ".$product->product->isOriginal;
                                }else{
                                    $part = "";
                                }
                            ?>
                            *{{$product->product->brand->name}} - {{$product->product->name}} {{$part}} ({{$product->product->variance->name}}) x {{number_format($promo->quantity)}} pcs.<br>
                        @endforeach
                        @foreach($promo->promo->freeService as $service)
                            *{{$service->service->name}} - {{$service->service->size}} ({{$service->service->category->name}})<br>
                        @endforeach
                    </td>
                    <td class="text-right">{{number_format($promo->promo->price,2)}}</td>
                    <td class="text-right">{{number_format($promo->quantity*$promo->promo->price,2)}}</td>
                    <?php
                        $total += $promo->quantity*$promo->promo->price;
                    ?>
                </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td>{{$estimate->discount->discount->name}} - DISCOUNT</td>
                    <td class="text-right">{{$estimate->discount->discount->rate}} %</td>
                    <td class="text-right">-{{number_format($total*($estimate->discount->discount->rate/100),2)}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td class="text-right">PhP {{number_format($total-($total*($estimate->discount->discount->rate/100)),2)}}</td>
                </tr>
            </tbody>
        </table>
        <div class="footer">
            <div style="float:left" class="col-md-6">
                This serves as an estimate only.<br>
                STORE MANAGER: ______________________<br>
                ADMIN OFFICER: ______________________<br> 
            </div>
            <div style="float:right" class="col-md-6">
                <br>
                CUSTOMER'S SIGNATURE: ___________________<br>
                GRAND TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: PhP {{number_format($total-($total*($estimate->discount->discount->rate/100)),2)}}<br> 
            </div>
        </div>
    </body>
</html>