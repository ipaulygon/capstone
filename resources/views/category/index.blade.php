@extends('layouts.master')

@section('title')
    {{"Category"}}
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
                    <i class="glyphicon glyphicon-plus"></i> Add New</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <table id="list" class="table table-striped responsive">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="pull-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{$category->name}}</td>
                                <td>{{$category->description}}</td>
                                <td class="pull-right">
                                    <button onclick="updateModal({{$category->id}})" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </button>
                                    <button onclick="showModal({{$category->id}})" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deactivate record">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    {!! Form::open(['method'=>'delete','action' => ['ServiceCategoryController@destroy',$category->id],'id'=>'del'.$category->id]) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="form-group pull-right">
                    <label class="checkbox-inline"><input type="checkbox" id="show"> Show deactivated records</label>
                </div>
                <table id="dlist" class="table table-striped responsive hidden">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="pull-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deactivate as $category)
                            <tr>
                                <td>{{$category->name}}</td>
                                <td>{{$category->description}}</td>
                                <td class="pull-right">
                                    <button onclick="show({{$category->id}})"type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Reactivate record">
                                        <i class="glyphicon glyphicon-refresh"></i>
                                    </button>
                                    {!! Form::open(['method'=>'patch','action' => ['ServiceCategoryController@reactivate',$category->id],'id'=>'reactivate'.$category->id]) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Reactivate --}}
                <div id="reactivateModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Reactivate</h4>
                            </div>
                            <div class="modal-body" style="text-align:center">
                                Are you sure you want to reactivate this record?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button id="reactivate" type="button" class="btn btn-info">Reactivate</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Create --}}
                <div id="createModal" class="modal fade">
                    <div class="modal-dialog">
                    {!! Form::open(['url' => 'category']) !!}
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Add New</h4>
                            </div>
                            <div class="modal-body">
                                <h3>Category Information</h3>
                                <div class="form-group">
                                    {!! Form::label('name', 'Category') !!}<span>*</span>
                                    {!! Form::input('text','name',null,[
                                        'class' => 'form-control',
                                        'placeholder'=>'Name',
                                        'maxlength'=>'50',
                                        'required']) 
                                    !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('description', 'Description') !!}
                                    {!! Form::textarea('description',null,[
                                        'class' => 'form-control',
                                        'placeholder'=>'Description',
                                        'maxlength'=>'50',
                                        'rows'=>'3']) 
                                    !!}
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
                    {!! Form::open(['method'=>'patch','action' => ['ServiceCategoryController@destroy',0]]) !!}
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Update</h4>
                            </div>
                            <div class="modal-body">
                                <h3>Category Information</h3>
                                <input id="categoryId" name="id" type="hidden">
                                <div class="form-group">
                                    {!! Form::label('name', 'Category') !!}<span>*</span>
                                    {!! Form::input('text','name',null,[
                                        'id'=>'categoryName',
                                        'class' => 'form-control',
                                        'placeholder'=>'Name',
                                        'maxlength'=>'50',
                                        'required']) 
                                    !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('description', 'Description') !!}
                                    {!! Form::textarea('description',null,[
                                        'id'=>'categoryDesc',
                                        'class' => 'form-control',
                                        'placeholder'=>'Description',
                                        'maxlength'=>'50',
                                        'rows'=>'3']) 
                                    !!}
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
                {{-- Deactivate --}}
                <div id="deactivateModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Deactivate</h4>
                            </div>
                            <div class="modal-body" style="text-align:center">
                                Are you sure you want to deactivate this record?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button id="deactivate" type="button" class="btn btn-danger">Deactivate</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script>
        $(document).ajaxStart(function() { Pace.restart(); });
        var deactivate = null;
        var reactivate = null;
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#dlist').DataTable({
                paging: false,
                searching: false,
                info: false,
                responsive: true,
            });
            $('#maintenance').addClass('active');
            $('#ms').addClass('active');
            $('#mCategory').addClass('active');
        });
        function showModal(id){
			deactivate = id;
			$('#deactivateModal').modal('show');
		}
        function updateModal(id){
            $.ajax({
				type: "GET",
				url: "/category/"+id+"/edit",
				dataType: "JSON",
				success:function(data){
                    $("#categoryId").val(data.category.id);
					$("#categoryName").val(data.category.name);
                    $("#categoryDesc").val(data.category.description);
				}
			});
            $('#updateModal').modal('show');
        }
		$('#deactivate').on('click', function (){
			$('#del'+deactivate).submit();
		});
        $(document).on('change','#show',function(){
            if($(this).prop('checked')){
                $('#dlist').removeClass('hidden');
            }else{
                 $('#dlist').addClass('hidden');
            }
        });
        function show(id){
			reactivate = id;
			$('#reactivateModal').modal('show');
		}
        $('#reactivate').on('click', function (){
			$('#reactivate'+reactivate).submit();
		});
    </script>
@stop