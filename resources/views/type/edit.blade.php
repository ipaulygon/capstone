@extends('layouts.master')

@section('title')
    {{"Product Type"}}
@stop

@section('content')
    {!! Form::model($type , ['method' => 'patch', 'action' => ['ProductTypeController@update',$type->id]]) !!}
    <div class="col-md-4">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Product Type Information</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('name', 'Product Type') !!}<span>*</span>
                    {!! Form::input('text','name',null,[
                        'class' => 'form-control',
                        'placeholder'=>'Name',
                        'maxlength'=>'50',
                        'required']) 
                    !!}
                </div>
            </div>
            <div class="box-footer">
                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
            </div>
        </div>
    </div>
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
                @foreach($type->tb as $tb)
                    <div id="brand" class="form-group">
                        @if($loop->index!=0)
                            <button id="removeBrand" type="button" class="btn btn-flat btn-danger btn-xs pull-right">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button>
                        @endif
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
            </div>
            <div class="box-footer">
                <button id="addBrand" type="button" class="btn btn-sm btn-primary pull-right">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('js/ptype.js') }}"></script>
    <script>
        var options = {
            source: [
                @foreach($brands as $brand)
                    "{{$brand->name}}",
                @endforeach
            ]
        }
        $(document).ready(function (){
            $('#mi').attr('class','treeview active');
            $('#mType').attr('class','active');
        });
        $(document).on("focus", ".autocomplete", function (){
            $(this).autocomplete(options);
        });
    </script>
@stop