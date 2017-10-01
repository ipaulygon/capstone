@extends('layouts.master')

@section('title')
    {{"View Estimates"}}
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
            </div>
            <div class="box-body dataTable_wrapper">
                <table id="list" class="table table-striped table-bordered responsive">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Vehicle</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estimates as $estimate)
                            <tr>
                                <td>{{'ESTIMATE'.str_pad($estimate->estimateId, 5, '0', STR_PAD_LEFT)}}</td>
                                <td>
                                    <li>Plate: {{$estimate->plate}}</li>
                                    <?php $transmission = ($estimate->transmission ? 'MT' : 'AT')?>
                                    <li>Model: {{$estimate->make}} - {{$estimate->year}} {{$estimate->model}} - {{$transmission}}</li>
                                    @if($estimate->mileage!=null)
                                    <li>Mileage: {{$estimate->mileage}}</li>
                                    @endif
                                </td>
                                <td>
                                    <li>Name: {{$estimate->firstName}} {{$estimate->middleName}} {{$estimate->lastName}}</li>
                                    <li>Address: {{$estimate->street}} {{$estimate->brgy}} {{$estimate->city}}</li>
                                    <li>Contact No.: {{$estimate->contact}}</li>
                                    @if($estimate->email!=null)
                                    <li>Email: {{$estimate->email}}</li>
                                    @endif
                                    @if($estimate->card!=null)
                                    <li>Senior Citizen/PWD ID: {{$estimate->card}}</li>
                                    @endif
                                </td>
                                <td>{{date('F j, Y - H:i:s',strtotime($estimate->created_at))}}</td>
                                <td class="text-right">
                                    <button onclick="signatureModal('{{$estimate->estimateId}}','estimateView')" type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Generate PDF">
                                        <i class="glyphicon glyphicon-file"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @include('layouts.signatureModal')
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/signature/jSignature.min.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#tEstimate').addClass('active');
        });
        var signature = null;
        var signatureLink = null;
        function signatureModal(id,type){
            signature = id;
            signatureLink = type;
            $("#signatureCanvas").jSignature();
            $('#signatureCanvas').find('canvas').attr('height','143');
            $('#signatureCanvas').find('canvas').css('height','143px');
            $('#signatureCanvas').find('canvas').attr('width','570');
            $("#signatureCanvas").jSignature('reset');
            $('#signatureModal').modal('show');
        }
        $(document).on('click','#signatureReset',function(){
            $("#signatureCanvas").jSignature('reset');
        });
        $('#signature').on('click', function (){
            pic = $('.jSignature').get(0).toDataURL();
            $.ajax({
                type: 'POST',
                url: '/signature',
                data: {pic:pic},
                success:function(data){
                    window.location.replace('/'+signatureLink+'/pdf/'+signature);
                }
            })
        });
    </script>
@stop