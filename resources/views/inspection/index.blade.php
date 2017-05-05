@extends('layouts.master')

@section('title')
    {{"Inspection"}}
@stop

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('assets/formbuilder/form-builder.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/formbuilder/form-render.min.css') }}">
@stop

@section('content')
    <div class="col-md-12">
        {!! Form::open(['url' => 'inspection']) !!}
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Inspection Form</h3>
                </div>
                <div class="box-body">
                    
                </div>
                <div class="box-footer">
                    <button type="button" id="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <textarea class="hidden" name="inspectionForm" id="inspectionForm"></textarea>
                    <div id="build-wrap"></div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/formbuilder/form-builder.min.js') }}"></script>
    <script src="{{ URL::asset('assets/formbuilder/form-render.min.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#ms').attr('class','treeview active');
            $('#mInspection').attr('class','active');
            //formBuilder
            options = {
                dataType: 'json',
                disabledActionButtons: ['clear','save','data'],
                editOnAdd: true,
            }
            var formBuilder = $('#build-wrap').formBuilder(options);
            $('#save').on('click', function(){
                $('#inspectionForm').text(formBuilder.actions.getData('json'));
            });
        });
    </script>
@stop