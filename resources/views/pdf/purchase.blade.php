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
        }
    </style>
    <body>
        <div class="center header">
            Rapide
        </div>
        <label style="float:right;color:red">{{$purchase->id}}</label>
        <div style="clear:both"></div>
        <div class="center">
            <label>AUTO SERVICE CENTER</label>
        </div>
        <div class="col-md-12 border center">
            PURCHASE ORDER
        </div><br>
        <div style="float:left" class="col-md-6">
            Supplier: {{$purchase->supplier->name}}<br>
            Address: {{$purchase->supplier->address}}
        </div>
        <div style="float:right"  class="col-md-6">
            Date: {{date('F j, Y', strtotime($purchase->created_at))}}
        </div>
        <div style="clear:both"></div>
        <br>
        <table width="100%">
            <thead>
                <tr>
                    <th width="5%" class="text-right">Qty</th>
                    <th>Product</th>
                    <th>Vehicle</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->detail as $product)
                <tr>
                    <td class="text-right">{{number_format($product->quantity)}}</td>
                    <?php
                        if($product->product->isOriginal!=null){
                            $part = "- ".$product->product->isOriginal;
                        }else{
                            $part = "";
                        }
                        $price = $product->product->priceRecord->where('created_at','<=',$purchase->created_at)->first()->price;
                    ?>
                    <td>{{$product->product->brand->name}} - {{$product->product->name}} {{$part}} ({{$product->product->variance->name}})</td>
                    <td>
                        @if($product->modelId!=null)
                        {{$product->vehicle->make->name}} - {{$product->vehicle->year}} {{$product->vehicle->name}} ({{$product->vehicle->transmission}})
                        @endif
                    </td>
                    <td class="text-right">{{number_format($price,2)}}</td>
                    <td class="text-right">{{number_format($product->quantity*$price,2)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            <div style="float:left" class="col-md-6">
                Please deliver to: ______________________<br>
                Approved by &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ______________________<br> 
            </div>
            <div style="clear:both"></div>
            <br>
            Remarks: {{$purchase->remarks}}
        </div>
    </body>
</html>