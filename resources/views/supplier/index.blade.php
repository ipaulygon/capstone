@extends('layouts.master')

@section('title')
    {{"Supplier"}}
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
                    <a href="{{ URL::to('/supplier/create') }}" class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> New Record</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="activeTable">
                        <table id="list" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Contact Person(s)</th>
                                    <th class="text-right">Contact Number(s)</th>
                                    <th>Address</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                    <tr>
                                        <td>{{$supplier->name}}</td>
                                        <td>
                                            @foreach($supplier->person as $person)
                                                <li>{{$person->spName}} - {{$person->spContact}}</li>
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            @foreach($supplier->number as $number)
                                                {{$number->scNo}}<br>
                                            @endforeach
                                        </td>
                                        <td>{{$supplier->street}} {{$supplier->brgy}} {{$supplier->city}}</td>
                                        <td class="text-right">
                                            <a href="{{url('/supplier/'.$supplier->id.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </a>
                                            <button onclick="deactivateShow({{$supplier->id}})" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deactivate record">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </button>
                                            {!! Form::open(['method'=>'delete','action' => ['SupplierController@destroy',$supplier->id],'id'=>'del'.$supplier->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="inactiveTable">
                        <table id="dlist" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Contact Person(s)</th>
                                    <th class="text-right">Contact Number(s)</th>
                                    <th>Address</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deactivate as $supplier)
                                    <tr>
                                        <td>{{$supplier->name}}</td>
                                        <td>
                                            @foreach($supplier->person as $person)
                                                <li>{{$person->spName}} - {{$person->spContact}}</li>
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            @foreach($supplier->number as $number)
                                                {{$number->scNo}}<br>
                                            @endforeach
                                        </td>
                                        <td>{{$supplier->street}} {{$supplier->brgy}} {{$supplier->city}}</td>
                                        <td class="text-right">
                                            <button onclick="reactivateShow({{$supplier->id}})"type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Reactivate record">
                                                <i class="glyphicon glyphicon-refresh"></i>
                                            </button>
                                            {!! Form::open(['method'=>'patch','action' => ['SupplierController@reactivate',$supplier->id],'id'=>'reactivate'.$supplier->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="form-group pull-right">
                    <label class="checkbox-inline"><input type="checkbox" id="showDeactivated"> Show deactivated records</label>
                </div>
                @include('layouts.reactivateModal')
                @include('layouts.deactivateModal') 
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('js/record.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#dlist').DataTable({
                responsive: true,
            });
            $('#maintenance').addClass('active');
            $('#mi').addClass('active');
            $('#mSupplier').addClass('active');
        });
    </script>
@stop