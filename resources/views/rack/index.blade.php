@extends('layouts.master')

@section('title')
    {{"Rack"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"></h3>
                <div class="box-tools pull-right">
                    <button type="button" data-toggle="modal" data-target="#createModal"  class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> New Record</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="activeTable">
                        <table id="list" class="table table-striped table-bordered responsive">
                            <thead>
                                <tr>
                                    <th>Rack</th>
                                    <th>Description</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($racks as $rack)
                                    <tr>
                                        <td>{{$rack->name}}</td>
                                        <td>{{$rack->description}}</td>
                                        <td class="text-right">
                                            <button onclick="updateModal({{$rack->id}})" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </button>
                                            <button onclick="deactivateShow({{$rack->id}})" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deactivate record">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </button>
                                            {!! Form::open(['method'=>'delete','action' => ['RackController@destroy',$rack->id],'id'=>'del'.$rack->id]) !!}
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
                                    <th>Rack</th>
                                    <th>Description</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deactivate as $rack)
                                    <tr>
                                        <td>{{$rack->name}}</td>
                                        <td>{{$rack->description}}</td>
                                        <td class="text-right">
                                            <button onclick="reactivateShow({{$rack->id}})"type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Reactivate record">
                                                <i class="glyphicon glyphicon-refresh"></i>
                                            </button>
                                            {!! Form::open(['method'=>'patch','action' => ['RackController@reactivate',$rack->id],'id'=>'reactivate'.$rack->id]) !!}
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
                {{-- Create --}}
                <div id="createModal" class="modal fade">
                    <div class="modal-dialog">
                    {!! Form::open(['url' => 'rack']) !!}
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">New Record</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    @include('layouts.required')
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('name', 'Rack') !!}<span>*</span>
                                            {!! Form::input('text','name',null,[
                                                'class' => 'form-control',
                                                'placeholder'=>'Name',
                                                'maxlength'=>'20',
                                                'required']) 
                                            !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('description', 'Description') !!}
                                            {!! Form::textarea('description',null,[
                                                'class' => 'form-control',
                                                'placeholder'=>'Description',
                                                'maxlength'=>'50',
                                                'rows'=>'2']) 
                                            !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                    </div>
                </div>
                {{-- Update --}}
                <div id="updateModal" class="modal fade">
                    <div class="modal-dialog">
                    {!! Form::open(['method'=>'patch','action' => ['RackController@update',0]]) !!}
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Update Record</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    @include('layouts.required')
                                    <input id="rackId" name="id" type="hidden">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('name', 'Rack') !!}<span>*</span>
                                            {!! Form::input('text','name',null,[
                                                'id'=>'rackName',
                                                'class' => 'form-control',
                                                'placeholder'=>'Name',
                                                'maxlength'=>'20',
                                                'required']) 
                                            !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('description', 'Description') !!}
                                            {!! Form::textarea('description',null,[
                                                'id'=>'rackDesc',
                                                'class' => 'form-control',
                                                'placeholder'=>'Description',
                                                'maxlength'=>'50',
                                                'rows'=>'2']) 
                                            !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                    </div>
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
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('js/record.js') }}"></script>
    <script>
        $(document).ajaxStart(function() { Pace.restart(); });
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#dlist').DataTable({
                responsive: true,
            });
            $('#maintenance').addClass('active');
            $('#ms').addClass('active');
            $('#mRack').addClass('active');
        });
        function updateModal(id){
            $.ajax({
				type: "GET",
				url: "/rack/"+id+"/edit",
				dataType: "JSON",
				success:function(data){
                    $("#rackId").val(data.rack.id);
					$("#rackName").val(data.rack.name);
                    $("#rackDesc").val(data.rack.description);
				}
			});
            $('#updateModal').modal('show');
        }
    </script>
@stop