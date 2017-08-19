<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{$util->name}} | Receipt</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <style type="text/css">
        @page{
            margin-top: 1cm;
            margin-bottom: 0.25cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
        }
        body{
            font-family: "SegoeUI","Sans-serif";
            font-size: 10px;
        }
        p{
            line-height: 25px;
        }
        .header{
            font-size: 18px!important;
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
        .col-md-8{
            width: 75%;
        }
        .col-md-7{
            width: 58.33333333%;
        }
        .col-md-6{
            width: 50%;
        }
        .col-md-5{
            width: 41.66666667%;
        }
        .col-md-4{
            width: 25%;
        }
        .border{
            border: 1px solid black;
        }
        .text-right{
            text-align: right;
        }
        table{
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
        .footerd{
            font-size: 0.8em;
        }
    </style>
    <body>
        <div class="col-md-12">
            <div class="col-md-5" style="float:left">
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">IN SETTLEMENT OF THE FOLLOWING</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="50%"><center>PARTICULARS</center></td>
                            <td width="50%"><center>AMOUNT</center></td>
                        </tr>
                        <tr><td></td><td></td></tr>
                        <tr><td></td><td></td></tr>
                        <tr>
                            <td>Total Sales(VAT Inclusive)</td><td></td>
                        </tr>
                        <tr>
                            <td>Less: VAT</td><td></td>
                        </tr>
                        <tr>
                            <td>Total</td><td></td>
                        </tr>
                        <tr>
                            <td>Less: SC/PWD Discount</td><td></td>
                        </tr>
                        <tr>
                            <td>Total Due</td><td></td>
                        </tr>
                        <tr>
                            <td>Less: Withholding Tax</td><td></td>
                        </tr>
                        <tr>
                            <td><b>Amount Due</b></td><td></td>
                        </tr>
                        <tr><td></td><td></td></tr>
                        <tr>
                            <td>VATABLE SALES</td><td></td>
                        </tr>
                        <tr>
                            <td>VAT - Exempt Sales</td><td></td>
                        </tr>
                        <tr>
                            <td>VAT - Zero Rated Sales</td><td></td>
                        </tr>
                        <tr>
                            <td>VAT Amount</td><td></td>
                        </tr>
                        <tr>
                            <td><b>Total Sales</b></td><td></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table width="92%">
                    <thead>
                        <tr>
                            <th colspan="2"><center>SENIOR CITIZEN/PWD</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Senior Citizen TIN:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="50%"><center>PWD/ID No.</center></td>
                            <td width="50%"><center>Signature</center></td>
                        </tr>
                        <tr><td></td><td></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-7" style="float:right">
                <div class="center header">
                    {{$util->name}}
                </div>
                <label style="float:right;color:red">{{$paymentId}}</label>
                <div class="center">
                    <label>AUTO SERVICE CENTER</label>
                </div>
                <div class="center">
                    OFFICIAL RECEIPT
                </div><br><br>
                <div style="float:right"  class="col-md-6">
                    Date: {{date('F j, Y', strtotime($payment->created_at))}}
                </div>
                <br><br>
                <?php 
                    $method = ($payment->isCredit ? 'Credit Card' : 'Cash');
                    $format = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                    $currencyWord = $format->format($payment->paid);
                    $partial = ($payment->paid!=$job->total ? '<u>partial</u>' : 'partial');
                    $full = ($payment->paid==$job->total ? '<u>full</u>' : 'full');
                    $cash = (!$payment->isCredit ? 'checked' : '');
                    $card = ($payment->isCredit ? 'checked' : '');
                ?>
                <p>
                    Received from <u>{{$job->customer->firstName}} {{$job->customer->middleName}} {{$job->customer->lastName}}</u>
                    with TIN <u>005-339-213-002</u> and address at <u>{{$job->customer->street}} {{$job->customer->brgy}} {{$job->customer->city}}</u>
                    the sum of <u>{{$currencyWord}} pesos</u> (<u>PhP {{number_format($payment->paid,2)}}</u>)
                    in {!!$partial!!} / {!!$full!!} payment for <u>{{$jobId}}</u>.
                </p>
                <br><br>
                <div class="col-m-6" style="float:left">
                    <table width="45%">
                        <thead>
                            <tr><th colspan="2"><center>FORM OF PAYMENT</center></th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox" {{$cash}}> Cash</td>
                                <td><input type="checkbox" {{$card}}> Card</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6" style="float:right">
                    By: _____________________
                </div>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="footer">
            <div class="footerd">Printed by: Admin {{$date}}</div>
        </div>
    </body>
</html>