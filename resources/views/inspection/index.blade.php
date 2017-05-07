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
        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Inspection Form</h3>
                </div>
                <div id="items" class="box-body">
                    <div class="form-group">
                        {!! Form::label('name', 'Inspection Type') !!}<span>*</span>
                        {!! Form::input('text',null,null,[
                            'class' => 'form-control autocomplete',
                            'name' => 'name',
                            'placeholder'=>'Name',
                            'maxlength'=>'50',
                            'required'])
                        !!}
                    </div>
                    <div id="item" class="form-group">
                        {!! Form::label('name', 'Inspection Items') !!}<span>*</span>
                        <textarea class="hidden" name="inspectionForm[]" id="inspectionForm"></textarea>
                        {!! Form::input('text',null,null,[
                            'class' => 'form-control ',
                            'name'=>'item[]',
                            'placeholder'=>'Inspection Item',
                            'maxlength'=>'50',
                            'required']) 
                        !!}
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" id="save" class="btn btn-primary">Save</button>
                    <button id="addItem" type="button" class="btn btn-md btn-primary pull-right">
                        <i class="glyphicon glyphicon-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="forms" class="col-md-8">
            <div id="form" class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Item Components</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="build-wrap" id="build-wrap"></div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop

@section('script')
    <script src="{{ URL::asset('assets/formbuilder/form-builder.min.js') }}"></script>
    <script src="{{ URL::asset('assets/formbuilder/form-render.min.js') }}"></script>
    <script src="{{ URL::asset('js/inspection.js') }}"></script>
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
            $('#build-wrap').formBuilder(options);
        });
    </script>
@stop