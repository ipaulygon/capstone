@extends('layouts.master')

@section('title')
    {{"Estimate Repair"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <div class="box-tools pull-right">
                    <a href="{{ URL::to('/estimate/create') }}" class="btn btn-success btn-md">
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
                        @foreach($estimates as $estimate)
                            <tr>
                                <td>
                                    <li>Plate: {{$estimate->plate}}</li>
                                    <li>Model: {{$estimate->make}} - {{$estimate->year}} {{$estimate->model}} ({{$estimate->transmission}})</li>
                                    @if($estimate->mileage!=null)
                                    <li>Mileage: {{$estimate->mileage}}</li>
                                    @endif
                                </td>
                                <td>
                                    <li>Name: {{$estimate->firstName}} {{$estimate->middleName}} {{$estimate->lastName}}</li>
                                    <li>Address: {{$estimate->street}} {{$estimate->brgy}} {{$estimate->city}}</li>
                                    <li>Contact No.: {{$estimate->contact}}</li>
                                    @if($estimate->email!=null)
                                    <li>{{$estimate->email}}</li>
                                    @endif
                                </td>
                                <td class="pull-right">
                                    <a href="{{url('/estimate/'.$estimate->estimateId.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <a href="{{url('/estimate/pdf/'.$estimate->estimateId)}}" target="_blank" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View PDF">
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
    <script>
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#tEstimate').addClass('active');
        });
    </script>
@stop