@extends('layouts.master')

@section('title')
    {{"Report"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title"> Reports as of now <b id="dateLabel">{{$dateEnd}}</b> </h4>
            </div>
            <div class="box-body dataTable_wrapper">
                {!! Form::open(['url' => 'report','id' => 'reportForm','target' => '_blank']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Report', 'Report Search') !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                <select id="reportId" name="reportId" class="form-control">
                                    <option value="5">Discrepancy Report</option>
                                    <option value="3">Inventory Report</option>
                                    <option value="1">Job Order Report</option>
                                    <option value="6">Price Analysis Report</option>
                                    <option value="2">Sales Report</option>
                                    <option value="4">Service Report</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('date', 'Date Range') !!}<span>*</span>
                            <div class="input-group">
                                <div class="input-group-addon" >
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::input('text','date',$dateString,[
                                    'class' => 'form-control',
                                    'id'=>'datepicker',
                                    'placeholder'=>'Date',
                                    'required'])
                                !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('action', 'Action') !!}
                        <button type="submit" class="btn btn-primary btn-md" id="generatePdf"><i class="glyphicon glyphicon-file"></i> Generate PDF</button>
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="panel panel-primary pan1 hidden">
                    <div class="panel-heading"><h3 class="panel-title">Job Order Report</h3></div>
                    <div class="panel-body">
                        <table id="jobsTable" class="table table-striped table-bordered responsive">
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
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$job->customer}}</td>
                                    <td>
                                        {{$job->plate}}<br>
                                        {{$job->make}} {{$job->model}} - ({{($job->isManual ? 'AT' : 'MT')}})
                                    </td>
                                    <td class="text-right">{{number_format($job->cash,2)}}</td>
                                    <td class="text-right">{{number_format($job->credit,2)}}</td>
                                    <td class="text-right">{{number_format($job->paid,2)}}</td>
                                    <td>{{($job->paid==$job->total ? 'Paid' : 'Bal: '.number_format($job->total-$job->paid,2))}}</td>
                                    @php
                                        $totalCash += $job->cash;
                                        $totalCredit += $job->credit;
                                        $totalAll += $job->paid;
                                        $totalBalance += $job->total-$job->paid;
                                    @endphp
                                </tr>
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
                    </div>
                </div>
                <div class="panel panel-primary pan2 hidden">
                    <div class="panel-heading"><h3 class="panel-title">Sales Report</h3></div>
                    <div class="panel-body">
                        <table id="salesTable" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Customer</th>
                                    <th class="text-right">Total(PhP)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $totalCash = 0; $totalCredit = 0; $totalAll = 0; $totalBalance = 0;
                                @endphp
                                @foreach($sales as $sale)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$sale->customer}}</td>
                                    <td class="text-right">{{number_format($sale->total,2)}}</td>
                                    @php
                                        $totalCash += $sale->total;
                                        $totalAll += $sale->total;
                                    @endphp
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total:</th>
                                    <th></th>
                                    <th class="text-right">{{number_format($totalAll,2)}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary pan3 hidden">
                    <div class="panel-heading"><h3 class="panel-title">Inventory Report</h3></div>
                    <div class="panel-body">
                        <table id="inventoryTable" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                    <td>{{$loop->index+1}}</td>
                                    @php
                                        if($product->original!=null){
                                            $type = ($product->original=="type1" ? $util->type1 : $util->type2);
                                        }else{
                                            $type = "";
                                        }
                                    @endphp
                                    <td>{{$product->brand}} - {{$product->product}} {{$type}} ({{$product->variance}})</td>
                                    <td class="text-right">{{number_format(0)}}</td>
                                    <td class="text-right">{{number_format($product->delivered)}}</td>
                                    <td class="text-right">{{number_format($product->returned)}}</td>
                                    <td class="text-right">{{number_format($product->total)}}</td>
                                    <td class="text-right">{{number_format($product->delivered-$product->total)}}</td>
                                    <td class="text-right">{{number_format($product->reorder)}}</td>
                                    <td>{{($product->delivered-$product->total <= $product->reorder ? 'For reorder' : 'Stable')}}</td>
                                    <td class="text-right">{{$loop->index+1}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary pan4 hidden">
                    <div class="panel-heading"><h3 class="panel-title">Service Report</h3></div>
                    <div class="panel-body">
                        <table id="serviceTable" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Service</th>
                                    <th class="text-right">Rendered</th>
                                    <th class="text-right">Rank</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$service->service}} - {{$service->size}} ({{$service->category}})</td>
                                    <td class="text-right">{{number_format($service->total)}}</td>
                                    <td class="text-right">{{$loop->index+1}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary pan5">
                    <div class="panel-heading"><h3 class="panel-title">Discrepancy Report</h3></div>
                    <div class="panel-body">
                        <table id="discrepancyTable" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th class="text-right">No. of items disposed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($discrepancy as $product)
                                <tr>
                                    <td>{{$product->id}}</td>
                                    @php
                                        if($product->original!=null){
                                            $type = ($product->original=="type1" ? $util->type1 : $util->type2);
                                        }else{
                                            $type = "";
                                        }
                                    @endphp
                                    <td>{{$product->brand}} - {{$product->product}} {{$type}} ({{$product->variance}})</td>
                                    <td class="text-right">{{number_format($product->total)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary pan6 hidden">
                    <div class="panel-heading"><h3 class="panel-title">Price Analysis Report</h3></div>
                    <div class="panel-body">
                        <table id="priceTable" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-right">Price</th>
                                    <th>Supplier</th>
                                    <th>Date Ordered</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analysis as $product)
                                <tr>
                                    @php
                                        if($product->original!=null){
                                            $type = ($product->original=="type1" ? $util->type1 : $util->type2);
                                        }else{
                                            $type = "";
                                        }
                                    @endphp
                                    <td>{{$product->brand}} - {{$product->product}} {{$type}} ({{$product->variance}})</td>
                                    <td class="text-right">{{number_format($product->price,2)}}</td>
                                    <td>{{$product->supplier}}</td>
                                    <td>{{$product->ordered}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            <div>
        </div>
        
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/api/sum().js') }}"></script>
    <script src="{{ URL::asset('assets/chart/Chart.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.phone.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('js/report.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#report').addClass('active');
        });
    </script>
@stop