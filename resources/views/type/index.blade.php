@extends('layouts.master')

@section('title')
    {{"Product Type"}}
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
                    <a href="{{ URL::to('/type/create') }}" class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> Add New</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <table id="list" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product Type</th>
                            <th>Category</th>
                            <th>Brand(s)</th>
                            <th class="pull-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($types as $type)
                            <tr>
                                <td>{{$type->name}}</td>
                                <td>{{$type->category}}</td>
                                <td>
                                    @foreach($type->tb as $tb)
                                        <li>{{$tb->brand->name}}</li>
                                    @endforeach
                                </td>
                                <td class="pull-right">
                                    <a href="{{url('/type/'.$type->id.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <button onclick="showModal({{$type->id}})" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deactivate record">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                    {!! Form::open(['method'=>'delete','action' => ['ProductTypeController@destroy',$type->id],'id'=>'del'.$type->id]) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="form-group pull-right">
                    <input type="checkbox" id="show"> Show deactivated records
                </div>
                <table id="dlist" class="table table-striped responsive hidden">
                    <thead>
                        <tr>
                            <th>Product Type</th>
                            <th>Brand(s)</th>
                            <th class="pull-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deactivate as $type)
                            <tr>
                                <td>{{$type->name}}</td>
                                <td>
                                    @foreach($type->tb as $tb)
                                        <li>{{$tb->brand->name}}</li>
                                    @endforeach
                                </td>
                                <td class="pull-right">
                                    <button onclick="show({{$type->id}})"type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Reactivate record">
                                        <i class="glyphicon glyphicon-refresh"></i>
                                    </button>
                                    {!! Form::open(['method'=>'patch','action' => ['ProductTypeController@reactivate',$type->id],'id'=>'reactivate'.$type->id]) !!}
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
    <script>
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
            $('#mi').addClass('active');
            $('#mType').addClass('active');
        });
        function showModal(id){
			deactivate = id;
			$('#deactivateModal').modal('show');
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