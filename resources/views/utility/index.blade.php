@extends('layouts.master')

@section('title')
    {{"Utilities"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
@stop

@section('content')
     {!! Form::open(['method'=>'patch','action' => ['UtilitiesController@update',1],'files' => true]) !!}
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">General Settings</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <center><img class="img-responsive" id="util-pic" src="{{ URL::asset($utilities->image)}}" style="max-width:150px; background-size: contain" /></center>
                <center>
                    {!! Form::label('pic', 'Business Icon') !!}
                    {!! Form::file('image',[
                        'class' => 'form-control',
                        'name' => 'image',
                        'class' => 'btn btn-success btn-sm',
                        'onchange' => 'readURL(this)']) 
                    !!}
                </center>
                <div class="form-group">
                    {!! Form::label('name', 'Business Name') !!}<span>*</span>
                    {!! Form::input('text','name',$utilities->name,[
                        'class' => 'form-control',
                        'placeholder'=>'Name',
                        'maxlength'=>'20',
                        'required']) 
                    !!}
                </div>
                <div class="form-group">
                    {!! Form::label('address', 'Business Address') !!}<span>*</span>
                    {!! Form::textarea('address',$utilities->address,[
                        'class' => 'form-control',
                        'placeholder'=>'Address',
                        'maxlength'=>'140',
                        'rows' => '2',
                        'required']) 
                    !!}
                </div>
            </div>
            <div class="box-footer">
                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
                @include('layouts.required')
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">System Settings</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    {!! Form::label('category', 'Product Type Categories:') !!}<span>*</span>
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::input('text','category1',$utilities->category1,[
                                'class' => 'form-control',
                                'placeholder'=>'Category',
                                'maxlength'=>'50',
                                'required']) 
                            !!}
                        </div>
                        <div class="col-md-6">
                            <label><span>*</span><i>Allows Vehicle Type on Product</i></label>
                        </div>                        
                    </div><br>
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::input('text','category2',$utilities->category2,[
                                'class' => 'form-control',
                                'placeholder'=>'Category',
                                'maxlength'=>'50',
                                'required']) 
                            !!}
                        </div>
                        <div class="col-md-6">
                            <label></label>
                        </div>
                    </div>
                    <br>
                    {!! Form::label('type', 'Part Types:') !!}<span>*</span>
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::input('text','type1',$utilities->type1,[
                                'class' => 'form-control',
                                'placeholder'=>'Part Type',
                                'maxlength'=>'50',
                                'required']) 
                            !!}
                        </div>
                        <div class="col-md-6">
                            <label><span>*</span><i>Authentic/Original</i></label>
                        </div>               
                    </div><br>
                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::input('text','type2',$utilities->type2,[
                                'class' => 'form-control',
                                'placeholder'=>'Part Type',
                                'maxlength'=>'50',
                                'required']) 
                            !!}
                        </div>
                        <div class="col-md-6">
                            <label><span>*</span><i>Replacement/Low Quality</i></label>
                        </div>  
                    </div><br>
                    <div class="form-group">
                        {!! Form::label('max', 'Max Value for Numeric') !!}<span>*</span>
                        {!! Form::input('text','max',$utilities->max,[
                            'class' => 'form-control',
                            'id' => 'max',
                            'placeholder'=>'Max',
                            'required']) 
                        !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('backlog', 'Max Days for Backlog') !!}<span>*</span>
                        {!! Form::input('text','backlog',$utilities->backlog,[
                            'class' => 'form-control',
                            'id' => 'backlog',
                            'placeholder'=>'backlog',
                            'required']) 
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('script')
    <script src="{{ URL::asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#utility').addClass('active');
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#util-pic')
                        .attr('src', e.target.result)
                        .width(180);
                    };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#max").inputmask({ 
            alias: "integer",
            prefix: '',
            allowMinus: false,
            autoGroup: true,
            min: 20,
            max: 200
        });
        $("#backlog").inputmask({ 
            alias: "integer",
            prefix: '',
            allowMinus: false,
            autoGroup: true,
            min: 1,
            max: 30
        });
    </script>
@endsection