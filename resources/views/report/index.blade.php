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
                <form action="">
                    <div class="form-group">
                        {!! Form::label('Report', 'Report Search') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <select id="reportId" name="reportId" class="form-control">
                                <option value=""></option>
                                {{--  <option value="1">Inventory Report</option>  --}}
                                <option value="2">Services Report</option>
                                <option value="3">Sales Report</option>
                                <option value="4">Products Report</option>
                                <option value="5">Discrepancy Report</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('date', 'Date Range') !!}<span>*</span>
                            <div class="input-group">
                                <div class="input-group-addon" >
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::input('text','date',null,[
                                    'class' => 'form-control',
                                    'id'=>'date',
                                    'placeholder'=>'Date',
                                    'required'])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            <div>
        </div>

        <ul class="nav nav-pills" role="tablist">
            <li class="active" role="presentation"><a href="#Table" id ="tab1" aria-controls="table" role="tab" data-toggle="pill">Table</a></li>
            <li role="presentation"><a href="#Graph" aria-controls="table" id ="tab2" role="tab" data-toggle="pill">Graph</a></li>
        </ul>
        <div role="tabpanel" class="tab-pane fade in active" id="Table">
            <table id="list1" class="table table-striped table-bordered responsive hidden">
                <thead>
                    <tr>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventory as $in)
                    <tr>
                        <td>{{$in->datecreated}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table id="list2" class="table table-striped table-bordered responsive hidden">
                <thead>
                    <tr>
                        <th>Count</th>
                        <th>Service</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                    <tr>
                        <td>{{count($service)}}</td>
                        <td>{{$service->name}}</td>
                        <td>
                            <li>Category: {{$service->category}}</li>
                            <li>Size: {{$service->size}}</li>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table id="list3" class="table table-striped table-bordered responsive hidden">
                <thead>
                    <tr>
                        <th>Invoice No.</th>
                        <th>Customer</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pos as $sales)
                        <tr>
                        <?php 
                            $salesId = 'INV'.str_pad($sales->invoId, 5, '0', STR_PAD_LEFT); 
                        ?>
                        <td>{{$salesId}}</td>
                        <td>
                            <li>Name: {{$sales->firstName}} {{$sales->middleName}} {{$sales->lastName}}</li>
                            <li>Address: {{$sales->street}} {{$sales->brgy}} {{$sales->city}}</li>
                            <li>Contact No.: {{$sales->contact}}</li>
                            @if($sales->email!=null)
                            <li>{{$sales->email}}</li>
                            @endif
                        </td>
                        <td class="text-right">{{number_format($sales->total,2)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table id="list4" class="table table-striped table-bordered responsive hidden">
                <thead>
                    <tr>
                        <th>Job Id</th>
                        <th>Product</th>
                        <th>Description</th>
                        <th class="text-right">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $prod)
                    <tr>
                        <td id="detailIds"></td>
                        <td>{{$prod->brand}} - {{$prod->product}}</td>
                        <td>
                            <li>Type: {{$prod->type}}</li>
                            <li>Size: {{$prod->variance}}</li>
                            @if($prod->isOriginal!=null)
                                <?php $type = ($prod->isOriginal=="type1" ? $util->type1 : $util->type2); ?>
                                <li>Part Information: {{$type}}</li>
                            @endif
                            @if($prod->description!=null || $prod->description!="")
                                <li>{{$prod->description}}</li>
                            @endif
                        </td>
                        <td class="text-right">{{number_format($prod->pprices,2)}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <table id="list5" class="table table-striped table-bordered responsive hidden">
                <thead>
                    <tr>
                        <th>Return Id</th>
                        <th>Supplier</th>
                        <th>Description</th>
                        <th class="text-right">Quantity Returned</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($return as $r)
                    <tr>
                        <td>{{$r->id}}</td>
                        <td>{{$r->supplier}}</td>
                        <td>
                            <li>Product: {{$r->brand}} - {{$r->product}}</li>
                            <li>Type: {{$r->type}}</li>
                            <li>Size: {{$r->variance}}</li>
                            @if($r->isOriginal!=null)
                                <?php $type = ($r->isOriginal=="type1" ? $util->type1 : $util->type2); ?>
                                <li>Part Information: {{$type}}</li>
                            @endif
                            @if($r->description!=null || $r->description!="")
                                <li>{{$r->description}}</li>
                            @endif
                        </td>
                        <td class="text-right">{{$r->qtyre}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="Graph">
            <canvas id="myChart1" class="chart hidden" width="400" height="100"></canvas>
            <canvas id="myChart2" class="chart hidden" width="400" height="100"></canvas>
            <canvas id="myChart3" class="chart hidden" width="400" height="100"></canvas>
            <canvas id="myChart4" class="chart hidden" width="400" height="100"></canvas>
            <canvas id="myChart5" class="chart hidden" width="400" height="100"></canvas>
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
    <script>
        $(document).ready(function(){
            $('#report').addClass('active');

            @foreach($services as $id)
                var jobId = String("00000" + {{$id->jobId}}).slice(-5);
                $('#detailId').text('JOB'+jobId);
            @endforeach

            @foreach($products as $prod)
                var jobId2 = String("00000" + {{$prod->jobId}}).slice(-5);
                $('#detailIds').text('JOB'+jobId2);
            @endforeach
        });
        var ctx = document.getElementById("myChart1").getContext('2d');
        var myChart1 = new Chart(ctx, {
            type: 'bar',
            data: {
            labels: [
                  @foreach($data as $rows) 
                        '{{$rows->brand}} - {{$rows->name}}', 
                  @endforeach
                        ],
                datasets: [{
                    label: 'Inventory Report',
                   
                    data: [
                      @foreach($data as $rows) 
                        '{{$rows->quantity}}', 
                      @endforeach
                          ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
        var ctx2 = document.getElementById("myChart2").getContext('2d');
        var myChart2 = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: [
                      @foreach($services as $ser) 
                            '{{$ser->name}} - {{$ser->category}}', 
                      @endforeach
                ],
                datasets: [{
                    label: 'Services Report',
                    data: [
                         @foreach($services as $ser) 
                            '{{$ser->price}}', 
                         @endforeach
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
        var ctx3 = document.getElementById("myChart3").getContext('2d');
        var myChart3 = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: [
                     @foreach($pos as $s) 
                            '{{$s->lastName}}, {{$s->firstName}} {{$s->middleName}}', 
                     @endforeach
                ],
                datasets: [{
                    label: 'Sales Report',
                    data: [
                        @foreach($pos as $s) 
                            '{{$s->total}}', 
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
        var ctx4 = document.getElementById("myChart4").getContext('2d');
        var myChart4 = new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($products as $pro)
                        '{{$pro->brand}} - {{$pro->product}}'
                    @endforeach
                      
                ],
                datasets: [{
                    label: 'Products Report',
                    data: [
                    @foreach($products as $pro)
                      '{{$pro->price}}',
                    @endforeach
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
        var ctx5 = document.getElementById("myChart5").getContext('2d');
        var myChart5 = new Chart(ctx5, {
            type: 'bar',
            data: {
                labels: [
                     @foreach($return as $re)
                        '{{$re->brand}} - {{$re->product}}',
                      @endforeach
                ],
                datasets: [{
                    label: 'Discrepancy Report',
                    data: [
                    @foreach($return as $rr)
                      '{{$rr->qtyre}}',
                    @endforeach  
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
        $('#reportId').on('change', function() {          
            $('.chart').addClass('hidden');
            $('.table').addClass('hidden');
            $('#myChart'+$(this).val()).removeClass('hidden');
            $('#list'+$(this).val()).removeClass('hidden');
        });
        $('#date').on('change', function() {
                id = $('#reportId').val();
                date = $('#date').val();
                $.ajax({
                type: 'POST',
                url: '/report/where',
                data: {date:date,id:id},
                success: function(data){
                    console.log(data);
                },
            });
        });
        $('#date').inputmask('99/99/9999-99/99/9999');
                var start = moment();
        var end = moment();
        function cb(start, end) {
            $('#date input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        $('#date').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Last for 7 Days': [moment(), moment().add(6, 'days')],
                'Last for 30 Days': [moment(), moment().add(29, 'days')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
            }
        }, cb);
        cb(start, end);


    </script>
@stop