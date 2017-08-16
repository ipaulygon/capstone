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
                                <option value="1">Total stocks of Product</option>
                                <option value="2">List of Service</option>
                                <option value="3">LIst of Sales</option>
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

        <div>
        </div>

        <div role="tabpanel" class="tab-pane fade in active" id="Table">
        <table id="list1" class="table table-striped table-bordered responsive hidden">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Description</th>
                            <th class="text-right">Stock</th>
                            <th class="text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $product)
                        <tr>
                            <td>{{$product->brand}} - {{$product->name}}</td>
                            <td>
                                <li>Type: {{$product->type}}</li>
                                <li>Size: {{$product->variance}}</li>
                                @if($product->isOriginal!=null)
                                    <?php $type = ($product->isOriginal=="type1" ? $util->type1 : $util->type2); ?>
                                    <li>Part Information: {{$type}}</li>
                                @endif
                                @if($product->description!=null || $product->description!="")
                                    <li>{{$product->description}}</li>
                                @endif
                            </td>
                            <td class="text-right">{{$product->quantity}}</td>
                            <td class="text-right">{{number_format($product->price,2)}}</td>
                        </tr>
                        @endforeach
                   </tbody>
        </table>
        <table id="list2" class="table table-striped table-bordered responsive hidden">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Description</th>
                            <th class="text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td>{{$service->name}}</td>
                            <td>
                                <li>Category: {{$service->category}}</li>
                                <li>Size: {{$service->size}}</li>
                            </td>
                            <td class="text-right">
                                {{number_format($service->price,2)}}
                            </td>
                        </tr>
                        @endforeach
                   </tbody>
        </table>
        <table id="list3" class="table table-striped table-bordered responsive hidden">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Customer</th>
                            <th>Products Availed</th>
                            <th>Services Availed</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($jobs as $sales)
                            <tr>
                                <td>
                                <li>Plate: {{$sales->plate}}</li>
                                <?php $transmission = ($sales->transmission ? 'MT' : 'AT')?>
                                <li>Model: {{$sales->make}} - {{$sales->year}} {{$sales->model}} - {{$transmission}}</li>
                                @if($sales->mileage!=null)
                                <li>Mileage: {{$sales->mileage}}</li>
                                @endif
                            </td>
                            <td>
                                <li>Name: {{$sales->firstName}} {{$sales->middleName}} {{$sales->lastName}}</li>
                                <li>Address: {{$sales->street}} {{$sales->brgy}} {{$sales->city}}</li>
                                <li>Contact No.: {{$sales->contact}}</li>
                                @if($sales->email!=null)
                                <li>{{$sales->email}}</li>
                                @endif
                            </td>
                            <td>{{$sales->brand}} - {{$sales->product}}</td>
                            <td>{{$sales->service}}</td>
                        </tr>
                        @endforeach
                   </tbody>
        </table>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="Graph">
        <canvas id="myChart1" class="chart hidden" width="400" height="100"></canvas>

        <canvas id="myChart2" class="chart hidden" width="400" height="100"></canvas>

        <canvas id="myChart3" class="chart hidden" width="400" height="100"></canvas>
        </div>
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
                    label: '{{$rows->brand}} - {{$rows->name}}',
                   
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
                    label: 'List of Services',
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
                     @foreach($jobs as $job) 
                            '{{$job->firstName}}', 
                     @endforeach
                ],
                datasets: [{
                    label: 'List of Sales',
                    data: [
                        @foreach($jobs as $job) 
                            '{{$job->total}}', 
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
              id = $('#date').val();
                $.ajax({
                type: 'POST',
                url: '/report/where',
                data: {id : id},
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
            minDate: start,
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
    </script>
@stop