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
                    <i class="glyphicon glyphicon-plus"></i> New Record</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <table id="list" class="table table-striped table-bordered responsive">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Customer</th>
                            <th>Remarks</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inspects as $inspect)
                            <tr>
                                <td>
                                    <li>Plate: {{$inspect->plate}}</li>
                                    <?php $transmission = ($inspect->transmission ? 'MT' : 'AT')?>
                                    <li>Model: {{$inspect->make}} - {{$inspect->model}} - {{$transmission}}</li>
                                    @if($inspect->mileage!=null)
                                    <li>Mileage: {{$inspect->mileage}}</li>
                                    @endif
                                </td>
                                <td>
                                    <li>Name: {{$inspect->firstName}} {{$inspect->middleName}} {{$inspect->lastName}}</li>
                                    <li>Address: {{$inspect->street}} {{$inspect->brgy}} {{$inspect->city}}</li>
                                    <li>Contact No.: {{$inspect->contact}}</li>
                                    @if($inspect->email!=null)
                                    <li>Email: {{$inspect->email}}</li>
                                    @endif
                                    @if($inspect->card!=null)
                                    <li>Senior Citizen/PWD ID: {{$inspect->card}}</li>
                                    @endif
                                </td>
                                <td>{{$inspect->remarks}}</td>
                                <td class="text-right">
                                    <a href="{{url('/inspect/'.$inspect->inspectId.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <a href="javascript: w=window.open('{{url('/inspect/pdf/'.$inspect->inspectId)}}'); w.print()" target="_blank" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Generate PDF">
                                        <i class="glyphicon glyphicon-file"></i>
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