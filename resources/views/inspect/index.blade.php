@extends('layouts.master')

@section('title')
    {{"Inspect Vehicle"}}
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
                    <a href="{{ URL::to('/inspect/create') }}" class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> Add New</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <table id="list" class="table table-striped responsive">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Customer</th>
                            <th>Remarks</th>
                            <th class="pull-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inspects as $inspect)
                            <tr>
                                <td>
                                    <li>Plate: {{$inspect->plate}}</li>
                                    <li>Model: {{$inspect->make}} - {{$inspect->year}} {{$inspect->model}} ({{$inspect->transmission}})</li>
                                    @if($inspect->mileage!=null)
                                    <li>Mileage: {{$inspect->mileage}}</li>
                                    @endif
                                </td>
                                <td>
                                    <li>Name: {{$inspect->firstName}} {{$inspect->middleName}} {{$inspect->lastName}}</li>
                                    <li>Address: {{$inspect->address}}</li>
                                    <li>Contact No.: {{$inspect->contact}}</li>
                                    @if($inspect->email!=null)
                                    <li>{{$inspect->email}}</li>
                                    @endif
                                </td>
                                <td>{{$inspect->remarks}}</td>
                                <td class="pull-right">
                                    <a href="{{url('/inspect/'.$inspect->id.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                        <i class="glyphicon glyphicon-edit"></i>
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
            $('#tInspect').addClass('active');
        });
    </script>
@stop