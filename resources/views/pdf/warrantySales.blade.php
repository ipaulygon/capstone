<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{$util->name}} | Sales Warranty</title>
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
            font-size: 11px;
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
            {{date('F j, Y', strtotime($warranty->created_at))}}<br>
            <label style="color:red">{{$warrantyId}}</label>
            <br>
            <label>Reference: {{'INV'.str_pad($warranty->salesId, 5, '0', STR_PAD_LEFT)}}</label>
        </div>
        <div style="clear:both"></div>
        <div class="center">
            <label>AUTO SERVICE CENTER</label>
        </div>
        <div class="col-md-12 border center">
            SALES WARRANTY
        </div><br>
        <div class="col-md-12">
            Customer: {{$warranty->sales->customer->firstName}} {{$warranty->sales->customer->middleName}} {{$warranty->sales->customer->lastName}}<br>
            Address: {{$warranty->sales->customer->street}} {{$warranty->sales->customer->brgy}} {{$warranty->sales->customer->city}}<br>
            Phone Number: {{$warranty->sales->customer->contact}}<br>
            Email: {{$warranty->sales->customer->email}}<br>
        </div>
        <br>
        <table width="100%">
            <thead>
                <tr>
                    <th class="text-right">Quantity</th>
                    <th>Item</th>
                    <th>From(Package/Promo)</th>
                </tr>
            </thead>
            <tbody>
            @foreach($warranty->product as $product)
                <?php
                    if($product->product->isOriginal!=null){
                        $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                    }else{
                        $type = "";
                    }
                ?>
                <tr>
                    <td class="text-right">{{number_format($product->quantity)}}</td>
                    <td>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}})</td>
                    <td></td>
                </tr>
            @endforeach
            @foreach($warranty->package as $product)
                <?php
                    if($product->product->isOriginal!=null){
                        $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                    }else{
                        $type = "";
                    }
                ?>
                <tr>
                    <td class="text-right">{{number_format($product->quantity)}}</td>
                    <td>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}})</td>
                    <td>{{$product->sales->package->name}}</td>
                </tr>
            @endforeach
            @foreach($warranty->promo as $product)
                <?php
                    if($product->product->isOriginal!=null){
                        $type = ($product->product->isOriginal=="type1" ? $util->type1 : $util->type2);
                    }else{
                        $type = "";
                    }
                ?>
                <tr>
                    <td class="text-right">{{number_format($product->quantity)}}</td>
                    <td>{{$product->product->brand->name}} - {{$product->product->name}} {{$type}} ({{$product->product->variance->name}})</td>
                    <td>{{$product->sales->promo->name}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="footer">
            <div style="float:left" class="col-md-6">
                STORE MANAGER: ______________________<br>
                ADMIN OFFICER: ______________________<br> 
            </div>
            <br><br>
            <div class="footerd">Printed by: {{$userName}} {{date('Y-m-d H:i:s')}}</div>
        </div>
    </body>
</html>