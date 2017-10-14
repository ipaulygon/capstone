<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{$util->name}} | Job Order Report</title>
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
            JOB ORDER REPORT
        </div><br>
        <div style="float:left"  class="col-md-6">
            Total of {{count($jobs)}} records
        </div>
        <div style="float:right"  class="col-md-6">
            Date as of: {{$date}}
        </div>
        <div style="clear:both"></div>
        <br>
        <table width="100%">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th class="text-right">Cash(PhP)</th>
                    <th class="text-right">Credit Card(PhP)</th>
                    <th class="text-right">Total(PhP)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalCash = 0; $totalCredit = 0; $totalAll = 0; $totalBalance = 0;
                @endphp
                @foreach($jobs as $job)
                <tr>
                    <td>{{$job->id}}</td>
                    <td>{{$job->customer}}</td>
                    <td>
                        {{$job->plate}} | {{$job->make}} {{$job->model}} - ({{($job->isManual ? 'AT' : 'MT')}})
                    </td>
                    <td class="text-right">{{number_format($job->cash,2)}}</td>
                    <td class="text-right">{{number_format($job->credit,2)}}</td>
                    <td class="text-right">{{number_format($job->paid,2)}}</td>
                    <td>{{($job->paid==$job->total ? 'Paid' : 'Bal: '.number_format($job->balance,2))}}</td>
                </tr>
                @php
                    $totalCash += $job->cash;
                    $totalCredit += $job->credit;
                    $totalAll += $job->paid;
                    $totalBalance += $job->balance;
                @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Total:</th>
                    <th></th>
                    <th></th>
                    <th class="text-right">{{number_format($totalCash,2)}}</th>
                    <th class="text-right">{{number_format($totalCredit,2)}}</th>
                    <th class="text-right">{{number_format($totalAll,2)}}</th>
                    <th class="text-right">Balance: {{number_format($totalBalance,2)}}</th>
                </tr>
            </tfoot>
        </table>
        <div class="footer">
            <br><br>
            <div class="footerd">Printed by: {{$userName}} {{date('Y-m-d H:i:s')}}</div>
        </div>
    </body>
</html>