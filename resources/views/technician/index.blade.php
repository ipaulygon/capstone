@extends('layouts.master')

@section('title')
    {{"Technician"}}
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
                    <a href="{{ URL::to('/technician/create') }}" class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> New Record</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="activeTable">
                        <table id="list" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Technician</th>
                                    <th>Details</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($technicians as $technician)
                                    <tr>
                                        <td>
                                            <img class="img-responsive" src="{{URL::asset($technician->image)}}" alt="" style="max-width:150px; background-size: contain">
                                        </td>
                                        <td>{{$technician->firstName}} {{$technician->middleName}} {{$technician->lastName}}</td>
                                        <td>
                                            <?php
                                                $date = date_create($technician->birthdate);
                                                $date = date_format($date,"F d,Y");
                                            ?>
                                            <li>Birthdate: {{$date}}</li>
                                            <li>Contact: {{$technician->contact}}</li>
                                            <li>Address: {{$technician->street}} {{$technician->brgy}} {{$technician->city}}</li>
                                            @if($technician->email)
                                            <li>Email: {{$technician->email}}</li>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{url('/technician/'.$technician->id.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </a>
                                            <button onclick="deactivateShow({{$technician->id}})"type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deactivate record">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </button>
                                            {!! Form::open(['method'=>'delete','action' => ['TechnicianController@destroy',$technician->id],'id'=>'del'.$technician->id]) !!}
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
                                    <th>Technician</th>
                                    <th>Details</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deactivate as $technician)
                                    <tr>
                                        <td>{{$technician->firstName}} {{$technician->middleName}} {{$technician->lastName}}</td>
                                        <td>
                                            <?php
                                                $date = date_create($technician->birthdate);
                                                $date = date_format($date,"F d,Y");
                                            ?>
                                            <li>Birthdate: {{$date}}</li>
                                            <li>Contact: {{$technician->contact}}</li>
                                            <li>Address: {{$technician->street}} {{$technician->brgy}} {{$technician->city}}</li>
                                            @if($technician->email)
                                            <li>Email: {{$technician->email}}</li>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <button onclick="reactivateShow({{$technician->id}})"type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Reactivate record">
                                                <i class="glyphicon glyphicon-refresh"></i>
                                            </button>
                                            {!! Form::open(['method'=>'patch','action' => ['TechnicianController@reactivate',$technician->id],'id'=>'reactivate'.$technician->id]) !!}
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
            $('#ms').addClass('active');
            $('#mTechnician').addClass('active');
        });
    </script>
@stop