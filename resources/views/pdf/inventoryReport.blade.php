<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{$util->name}} | Inventory Report</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <style type="text/css">
        @page{
            margin-top: 1cm;
            margin-bottom: 0.25cm;
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
        }
        .footerd{
            font-size: 0.8em;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
    <body>
        <div style="float:left">
            <img src="{{$util->image}}" width="50px" height="50px">
        </div>
        <div class="center header">
            {{$util->name}}
        </div>
        <div style="clear:both"></div>
        <div class="center">
            <label>AUTO SERVICE CENTER</label>
        </div>
        <div class="col-md-12 border center">
            INVENTORY REPORT
        </div><br>
        <div style="float:left"  class="col-md-6">
            Total of {{count($inventory)}} records
        </div>
        <div style="float:right"  class="col-md-6">
            Date as of: {{$date}}
        </div>
        <div style="clear:both"></div>
        <br>
        <table width="100%">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-right">Total Inventory</th>
                    <th class="text-right">Delivered</th>
                    <th class="text-right">Returned</th>
                    <th class="text-right">Sales</th>
                    <th class="text-right">Current Inventory</th>
                    <th class="text-right">Re-order point</th>
                    <th>Condition</th>
                    <th class="text-right">Rank</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventory as $product)
                <tr>
                    @php
                        if($product->original!=null){
                            $type = ($product->original=="type1" ? $util->type1 : $util->type2);
                        }else{
                            $type = "";
                        }
                    @endphp
                    <td>{{$product->brand}} - {{$product->product}} {{$type}} ({{$product->variance}})</td>
                    <td class="text-right">{{number_format($product->currentStart)}}</td>
                    <td class="text-right">{{number_format($product->deliveredEnd)}}</td>
                    <td class="text-right">{{number_format($product->returnedEnd)}}</td>
                    <td class="text-right">{{number_format($product->totalEnd)}}</td>
                    <td class="text-right">{{number_format($product->currentEnd)}}</td>
                    <td class="text-right">{{number_format($product->reorder)}}</td>
                    <td>{{($product->currentEnd <= $product->reorder ? 'Warning' : 'Stable')}}</td>
                    <td class="text-right">{{($product->rank)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            <br><br>
            <div class="footerd">Printed by: {{$userName}} {{date('Y-m-d H:i:s')}}</div>
        </div>
    </body>
</html>