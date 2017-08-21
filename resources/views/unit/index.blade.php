@extends('layouts.master')

@section('title')
    {{"Unit of Measurement"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
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
                                    <th>Unit</th>
                                    <th>Description</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $unit)
                                    <tr>
                                        <td>{{$unit->name}}</td>
                                        <td>{{$unit->description}}</td>
                                        <td class="text-right">
                                            <button onclick="updateModal({{$unit->id}})" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </button>
                                            <button onclick="deactivateShow({{$unit->id}})" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deactivate record">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </button>
                                            {!! Form::open(['method'=>'delete','action' => ['ProductUnitController@destroy',$unit->id],'id'=>'del'.$unit->id]) !!}
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
                                    <th>Unit</th>
                                    <th>Description</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deactivate as $unit)
                                    <tr>
                                        <td>{{$unit->name}}</td>
                                        <td>{{$unit->description}}</td>
                                        <td class="text-right">
                                            <button onclick="reactivateShow({{$unit->id}})"type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Reactivate record">
                                                <i class="glyphicon glyphicon-refresh"></i>
                                            </button>
                                            {!! Form::open(['method'=>'patch','action' => ['ProductUnitController@reactivate',$unit->id],'id'=>'reactivate'.$unit->id]) !!}
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
                    {!! Form::open(['url' => 'unit']) !!}
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">New Record</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    @include('layouts.required')
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('name', 'Unit of Measurement') !!}<span>*</span>
                                            {!! Form::input('text','name',null,[
                                                'class' => 'form-control',
                                                'placeholder'=>'Name',
                                                'maxlength'=>'20',
                                                'required']) 
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('category', 'Unit Category') !!}<span>*</span><br>
                                            <select id="category" name="category" class="select2 form-control" required>
                                                <option value="1">Length</option>
                                                <option value="2">Mass</option>
                                                <option value="3">Volume</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('description', 'Description') !!}<span>*</span>
                                            {!! Form::textarea('description',null,[
                                                'class' => 'form-control',
                                                'placeholder'=>'Description',
                                                'maxlength'=>'50',
                                                'rows'=>'2',
                                                'required']) 
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
                    {!! Form::open(['method'=>'patch','action' => ['ProductUnitController@update',0]]) !!}
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Update Record</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    @include('layouts.required')
                                    <input id="unitId" name="id" type="hidden">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('name', 'Unit of Measurement') !!}<span>*</span>
                                            {!! Form::input('text','name',null,[
                                                'id'=>'unitName',
                                                'class' => 'form-control',
                                                'placeholder'=>'Name',
                                                'maxlength'=>'20',
                                                'required']) 
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('category', 'Unit Category') !!}<span>*</span><br>
                                            <select id="uc" name="category" class="select2 form-control" required>
                                                <option value="1">Length</option>
                                                <option value="2">Mass</option>
                                                <option value="3">Volume</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('description', 'Description') !!}<span>*</span>
                                            {!! Form::textarea('description',null,[
                                                'id'=>'unitDesc',
                                                'class' => 'form-control',
                                                'placeholder'=>'Description',
                                                'maxlength'=>'50',
                                                'rows'=>'2',
                                                'required']) 
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
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
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
            $(".select2").select2();
            $('#maintenance').addClass('active');
            $('#mi').addClass('active');
            $('#mUnit').addClass('active');
        });
        function updateModal(id){
            $.ajax({
				type: "GET",
				url: "/unit/"+id+"/edit",
				dataType: "JSON",
				success:function(data){
                    $("#unitId").val(data.unit.id);
					$("#unitName").val(data.unit.name);
                    $("#unitDesc").val(data.unit.description);
                    $('#uc').val(data.unit.category);
                    $(".select2").select2();
				}
			});
            $('#updateModal').modal('show');
        }
    </script>
@stop