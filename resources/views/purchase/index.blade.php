@extends('layouts.master')

@section('title')
    {{"Purchase Order"}}
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
                    <a href="{{ URL::to('/purchase/create') }}" class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> Add New</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <table id="list" class="table table-striped responsive">
                    <thead>
                        <tr>
                            <th>Purchase Id</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th class="pull-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <td>{{$purchase->id}}</td>
                                <td>{{$purchase->supplier}}</td>
                                <td>
                                    @if(!$purchase->isFinalize)
                                    {{"Not yet finalized"}}
                                    @elseif($purchase->isFinalize && !$purchase->isDelivered)
                                    {{"Finalized"}}
                                    @elseif($purchase->isDelivered)
                                    {{"All items delivered"}}
                                    @endif
                                </td>
                                <td class="pull-right">
                                    @if(!$purchase->isFinalize)
                                        <button onclick="finalizeModal('{{$purchase->id}}')" type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Finalize record">
                                            <i class="glyphicon glyphicon-ok"></i>
                                        </button>
                                        <a href="{{url('/purchase/'.$purchase->id.'/edit')}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                        <button onclick="showModal('{{$purchase->id}}')" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Discard record">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </button>
                                        {!! Form::open(['method'=>'delete','action' => ['PurchaseController@destroy',$purchase->id],'id'=>'del'.$purchase->id]) !!}
                                        {!! Form::close() !!}
                                        {!! Form::open(['method'=>'patch','action' => ['PurchaseController@finalize',$purchase->id],'id'=>'fin'.$purchase->id]) !!}
                                        {!! Form::close() !!}
                                    @else
                                        <a href="{{url('/purchase/pdf/'.$purchase->id)}}" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View PDF">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Finalize --}}
                <div id="finalizeModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Finalize</h4>
                            </div>
                            <div class="modal-body" style="text-align:center">
                                Are you sure you want to finalize this record?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button id="finalize" type="button" class="btn btn-success">Finalize</button>
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
                                <h4 class="modal-title">Discard</h4>
                            </div>
                            <div class="modal-body" style="text-align:center">
                                Are you sure you want to discard this record?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button id="deactivate" type="button" class="btn btn-danger">Discard</button>
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
        var finalize = null;
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#tPurchase').addClass('active');
        });
        function showModal(id){
			deactivate = id;
			$('#deactivateModal').modal('show');
		}
		$('#deactivate').on('click', function (){
			$('#del'+deactivate).submit();
		});
        function finalizeModal(id){
			finalize = id;
			$('#finalizeModal').modal('show');
		}
		$('#finalize').on('click', function (){
			$('#fin'+finalize).submit();
		});
    </script>
@stop