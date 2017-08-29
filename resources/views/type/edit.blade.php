@extends('layouts.master')

@section('title')
    {{"Product Type"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
    {!! Form::model($type , ['method' => 'patch', 'action' => ['ProductTypeController@update',$type->id]]) !!}
    @include('layouts.required')
    @include('type.form')
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Brand(s)</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div id="brands" class="box-body">
                @if(count($type->tb)>0)
                    @foreach($type->tb as $tb)
                        <div id="brand" class="form-group">
                            @if($loop->index!=0)
                                <button id="removeBrand" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Remove">
                                    <i class="glyphicon glyphicon-remove"></i>
                                </button>
                            @endif
                            <input type="hidden" class="hidden" value="{{$tb->brandId}}">
                            {!! Form::label('brand', 'Brand') !!}<span>*</span>
                            {!! Form::input('text','brand',$tb->brand->name,[
                                'class' => 'form-control autocomplete',
                                'name' => 'brand[]',
                                'placeholder'=>'Name',
                                'maxlength'=>'50',
                                'required'])
                            !!}
                        </div>
                    @endforeach
                @else
                    <div id="brand" class="form-group">
                        {!! Form::label('brand', 'Brand') !!}<span>*</span>
                        {!! Form::input('text',null,null,[
                            'class' => 'form-control autocomplete',
                            'name' => 'brand[]',
                            'placeholder'=>'Name',
                            'maxlength'=>'50',
                            'required'])
                        !!}
                    </div>
                @endif 
            </div>
            <div class="box-footer">
                <button id="addBrand" type="button" class="btn btn-sm btn-primary pull-right" data-toggle="tooltip" data-placement="top" title="Add">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('js/ptype.js') }}"></script>
    <script>
        var options = {
            source: [
                @foreach($brands as $brand)
                    "{{$brand->name}}",
                @endforeach
            ]
        }
        $('.square-blue').iCheck('check');
        $("#category[value={{$type->category}}]").prop('checked',true);
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#mi').addClass('active');
            $('#mType').addClass('active');
        });
        $(document).on("focus", ".autocomplete", function (){
            $(this).autocomplete(options);
        });
    </script>
@stop