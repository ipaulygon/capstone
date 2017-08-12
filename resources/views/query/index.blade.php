@extends('layouts.master')

@section('title')
    {{"Queries"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"> </h3>
            </div>
            <div class="box-body dataTable_wrapper">
                <form action="">
                    <div class="form-group">
                        {!! Form::label('Query', 'Query Search') !!}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <select id="queryId" name="queryId" class="form-control">
                                <option value=""></option>
                                <option value="1">Most availed parts/supplies</option>
                                <option value="2">Most availed services</option>
                                <option value="3">Most jobs done by technician</option>
                                <option value="4">Most repaired vehicle</option>
                                <option value="5">Customers with pending payments</option>
                            </select>
                        </div>
                    </div>
                </form>
                <table id="list" class="table table-striped table-bordered responsive hidden">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Count</th>
                            <th>qwe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
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
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#queries').addClass('active');
        });
        $(document).on('change','#queryId',function(){
            id = $('#queryId').val();
            $.ajax({
                type: 'POST',
                url: '/query/load',
                data: {id : id},
                success: function(data){
                    console.log(data);
                    $('#list').DataTable({
                        'ajax': data.query,
                        'destroy': true,
                        'columns': [{
                            "className":      'details-control',
                            "orderable":      false,
                            'data': null,
                            'defaultContent': ''
                        },
                        {'data': 'count'},
                        {'data': 'product'},
                        ]
                    });
                },
            });
        });
    </script>
@stop