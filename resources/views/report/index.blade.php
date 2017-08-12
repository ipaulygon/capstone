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
                <h3 class="box-title"> </h3>
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
                           {{--  {!! Form::input('text','date',$date,[
                                'class' => 'form-control',
                                'id'=>'date',
                                'placeholder'=>'Date',
                                'required'])
                            !!} --}}
                        </div>
                    </div>
                </div>
                </div>

        <canvas id="myChart" width="400" height="100"></canvas>

        <canvas id="myChart2" width="400" height="100"></canvas>

        <canvas id="myChart3" width="400" height="100"></canvas>
       
        </div>
    </div>

@stop

@section('script')

    <script src="{{ URL::asset('assets/chart/Chart.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
         var ctx = document.getElementById("myChart").getContext('2d');

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
            labels: [
              @foreach($data as $rows) 
                    '{{$rows->brand}} - {{$rows->name}}', 
              @endforeach
                    ],
            datasets: [{
            label: 'List of Products',
           
            data: [
              @foreach($data as $rows) 
                '{{$rows->quantity}}', 
              @endforeach
                  ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
                'rgba(255,99,132,1)',
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
    </script>
 {{--    <script>
        $('#reportId').on('change', function() {
          alert( this.value );
        })
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
    </script> --}}
@stop