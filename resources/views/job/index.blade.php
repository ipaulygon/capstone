@extends('layouts.master')

@section('title')
    {{"Job Order"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body no-padding">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="box hidden">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <div class="box-tools pull-right">
                    <a href="{{ URL::to('/job/create') }}" class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> Add New</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <table id="list" class="table table-striped responsive">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Customer</th>
                            <th class="pull-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $job)
                            <tr>
                                <td>
                                    <li>Plate: {{$job->plate}}</li>
                                    <li>Model: {{$job->make}} - {{$job->year}} {{$job->model}} ({{$job->transmission}})</li>
                                    @if($job->mileage!=null)
                                    <li>Mileage: {{$job->mileage}}</li>
                                    @endif
                                </td>
                                <td>
                                    <li>Name: {{$job->firstName}} {{$job->middleName}} {{$job->lastName}}</li>
                                    <li>Address: {{$job->address}}</li>
                                    <li>Contact No.: {{$job->contact}}</li>
                                    @if($job->email!=null)
                                    <li>{{$job->email}}</li>
                                    @endif
                                </td>
                                <td class="pull-right">
                                    <a href="{{url('/job/'.$job->jobId.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <a href="{{url('/job/pdf/'.$job->jobId)}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View PDF">
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fullcalendar/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#calendar').fullCalendar({
                contentHeight: 500,
                fixedWeekCount: false,
                dayClick: function(date, jsEvent, view) {
                    //alert('Clicked on: ' + date.format());
                    // change the day's background color just for fun
                    $(this).css('border','2px solid blue');
                },
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,listWeek,listDay'
                },
                views: {
                    month: {
                        buttonText: 'Month'
                    },
                    listWeek: {
                        type: 'list',
                        duration: { days: 7 },
                        buttonText: 'Week'
                    },
                    listDay: {
                        type: 'list',
                        duration: { days: 1 },
                        buttonText: 'Day'
                    },
                }
            });
            $('#tJob').addClass('active');
        });
    </script>
@stop