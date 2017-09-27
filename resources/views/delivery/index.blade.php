@extends('layouts.master')

@section('title')
    {{"Receive Delivery"}}
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
                    <a href="{{ URL::to('/delivery/create') }}" class="btn btn-success btn-md">
                    <i class="glyphicon glyphicon-plus"></i> New Record</a>
                </div>
            </div>
            <div class="box-body dataTable_wrapper">
                <table id="list" class="table table-striped table-bordered responsive">
                    <thead>
                        <tr>
                            <th>Delivery Id</th>
                            <th>Supplier</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $delivery)
                            <tr>
                                <td>{{$delivery->id}}</td>
                                <td>{{$delivery->supplier->name}}</td>
                                <td>{{($delivery->isReceived ? 'Received':'Pending')}}</td>
                                <td class="text-right">
                                    @if(count($delivery->return)==0 && !$delivery->isReceived)
                                        <button onclick="updateAdmin('{{$delivery->id}}','delivery')" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Update record">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </button>
                                    @endif
                                    <a href="{{url('/delivery/pdf/'.$delivery->id)}}" target="_blank" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Generate PDF">
                                        <i class="glyphicon glyphicon-file"></i>
                                    </a>
                                    @if(!$delivery->isReceived)
                                        <button onclick="receiveModal('{{$delivery->id}}')" type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Receive record">
                                            <i class="glyphicon glyphicon-check"></i>
                                        </button>
                                        {!! Form::open(['method'=>'patch','action' => ['DeliveryController@receive',$delivery->id],'id'=>'rec'.$delivery->id]) !!}
                                        {!! Form::close() !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Receive --}}
                <div id="receiveModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h4 class="modal-title">Receive</h4>
                            </div>
                            <div class="modal-body">
                                <div style="text-align:center">Are you sure you want to receive this record?</div>
                                <br>
                                <div class="dataTable_wrapper">
                                    <table id="receiveList" class="table table-striped table-bordered responsive">
                                        <thead>
                                            <tr>
                                                <th width="10%" class="text-right">Quantity</th>
                                                <th>Item</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button id="receive" type="button" class="btn btn-success">Receive</button>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.updateAdmin')
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('js/deliveryIndex.js') }}"></script>
    <script src="{{ URL::asset('js/record.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#tDelivery').addClass('active');
        });
    </script>
@stop