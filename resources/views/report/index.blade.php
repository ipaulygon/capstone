@extends('layouts.master')

@section('title')
    {{"Report"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker-bs3.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
            <?php $ldate = date('Y-m-d H:i:s'); ?>
                <h3 class="box-title"> Reports as of now {{$ldate}} </h3>
            </div>
            <div class="box-body dataTable_wrapper">
                {!! Form::open(['url' => 'report','id' => 'reportForm']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Report', 'Report Search') !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                <select id="reportId" name="reportId" class="form-control">
                                    <option value=""></option>
                                    <option value="1">Sales Report</option>
                                    <option value="2">Inventory Report</option>
                                    <option value="3">Service Report</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('date', 'Date Range') !!}<span>*</span>
                            <div class="input-group">
                                <div class="input-group-addon" >
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::input('text','date',$dateString,[
                                    'class' => 'form-control',
                                    'id'=>'date',
                                    'placeholder'=>'Date',
                                    'required'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <div class="panel panel-primary pan1">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <table id="salesTable" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                        {{$job->make}} {{$job->model}} - {{$job->year}} ({{($job->isManual ? 'AT' : 'MT')}})
                                    </td>
                                    <td class="text-right">{{number_format($job->cash,2)}}</td>
                                    <td class="text-right">{{number_format($job->credit,2)}}</td>
                                    <td class="text-right">{{number_format($job->cash+$job->credit,2)}}</td>
                                    <td>{{($job->paid==$job->total ? 'Paid' : 'Bal: '.number_format($job->total-$job->paid,2))}}</td>
                                    @php
                                        $totalCash += $job->cash;
                                        $totalCredit += $job->credit;
                                        $totalAll += $job->cash+$job->credit;
                                        $totalBalance += $job->total-$job->paid;
                                    @endphp
                                </tr>
                                @endforeach
                                @foreach($sales as $sale)
                                <tr>
                                    <td>{{$loop->index+1+count($jobs)}}</td>
                                    <td>{{$sale->customer}}</td>
                                    <td></td>
                                    <td class="text-right">{{number_format($sale->total,2)}}</td>
                                    <td></td>
                                    <td class="text-right">{{number_format($sale->total,2)}}</td>
                                    <td></td>
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
                <div class="panel panel-primary pan2">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <table id="inventoryTable" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th class="text-right">Delivered</th>
                                    <th class="text-right">Returned</th>
                                    <th class="text-right">Sales</th>
                                    <th class="text-right">Total Inventory</th>
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
                <div class="panel panel-primary pan3">
                    <div class="panel-heading"></div>
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
            <div>
        </div>
        
    </div>
@stop

@section('script')

    <script src="{{ URL::asset('assets/chart/Chart.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.phone.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#report').addClass('active');
            var start = moment();
            var end = moment();
            function cb(start, end) {
                $('#date input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
            $('#date').daterangepicker({
                minDate: {{$dateStart}},
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Last for 7 Days': [moment(), moment().add(6, 'days')],
                    'Last for 30 Days': [moment(), moment().add(29, 'days')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                }
            }, cb);
            cb(start, end);
            $('#date').inputmask('99/99/9999-99/99/9999');
        });
    </script>
@stop