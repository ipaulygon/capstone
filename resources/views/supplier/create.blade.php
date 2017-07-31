@extends('layouts.master')

@section('title')
    {{"Supplier"}}
@stop

@section('content')
    {!! Form::open(['url' => 'supplier']) !!}
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Supplier Information</h3>
            </div>
            <div class="box-body">
                @include('supplier.form')
                {!! Form::label('contact', 'Contact') !!}
                <div id="numbers">
                    {{-- For retrieving input --}}
                    @if(old('scNo'))
                        @foreach(old('scNo') as $scNo)
                            <div id="number" class="form-group">
                                @if($loop->index!=0)
                                    <button id="removeNumber" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Remove">
                                        <i class="glyphicon glyphicon-remove"></i>
                                    </button>
                                @endif
                                {!! Form::label('scNo', 'Contact Number') !!}<span>*</span>
                                {!! Form::input('text',null,$scNo,[
                                    'class' => 'form-control contact',
                                    'name' => 'scNo[]',
                                    'placeholder'=>'Number',
                                    'required'])
                                !!}
                            </div>
                        @endforeach
                    {{-- starting/load page --}}
                    @else
                        <div id="number" class="form-group">
                            {!! Form::label('scNo', 'Contact Number') !!}<span>*</span>
                            {!! Form::input('text',null,null,[
                                'class' => 'form-control contact',
                                'name' => 'scNo[]',
                                'placeholder'=>'Number',
                                'required'])
                            !!}
                        </div>
                    @endif
                </div>
                <button id="addNumber" type="button" class="btn btn-sm btn-primary pull-right" data-toggle="tooltip" data-placement="top" title="Add">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </div>
            <div class="box-footer">
                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
                @include('layouts.required')
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Contact Person(s)</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div id="persons" class="box-body">
                {{-- For retrieving input --}}
                @if(old('spName'))
                    @foreach(old('spName') as $key=>$spName)
                        <div id="person" class="form-group">
                            @if($loop->index!=0)
                                <button id="removePerson" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Remove">
                                    <i class="glyphicon glyphicon-remove"></i>
                                </button><br>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('spName', 'Contact Person') !!}<span>*</span>
                                    {!! Form::input('text',null,$spName,[
                                        'class' => 'form-control',
                                        'name' => 'spName[]',
                                        'placeholder'=>'Name',
                                        'maxlength'=>'100',
                                        'required'])
                                    !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('spContact', 'Contact No.') !!}
                                    {!! Form::input('text',null,old('spContact.'.$key),[
                                        'class' => 'form-control contact',
                                        'name' => 'spContact[]',
                                        'placeholder'=>'Number',
                                        'required'])
                                    !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                {{-- starting/load page --}}
                @else
                    <div id="person" class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('spName', 'Contact Person') !!}<span>*</span>
                                {!! Form::input('text',null,null,[
                                    'class' => 'form-control',
                                    'name' => 'spName[]',
                                    'placeholder'=>'Name',
                                    'maxlength'=>'100',
                                    'required'])
                                !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::label('spContact', 'Contact No.') !!}<span>*</span>
                                {!! Form::input('text',null,null,[
                                    'class' => 'form-control contact',
                                    'name' => 'spContact[]',
                                    'placeholder'=>'Number',
                                    'required'])
                                !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="box-footer">
                <button id="addPerson" type="button" class="btn btn-sm btn-primary pull-right" data-toggle="tooltip" data-placement="top" title="Add">
                    <i class="glyphicon glyphicon-plus"></i>
                </button><br>
                <i>Note: First person is set as the primary contact person</i>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.phone.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('js/supplier.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#maintenance').addClass('active');
            $('#mi').addClass('active');
            $('#mSupplier').addClass('active');
            @if(!old('scNo'))
                $('#numbers .contact').inputmask("+63 999 9999 999");
            @else
                @foreach(old('scNo') as $scNo)
                    @if($scNo[2] == '2' && strlen($scNo) >= 17)
                        $('#numbers .contact:eq({{$loop->index}})').inputmask("(02) 999 9999 loc. 9999");
                    @elseif($scNo[2] == '2')
                        $('#numbers .contact:eq({{$loop->index}})').inputmask("(02) 999 9999");
                    @else
                        $('#numbers .contact:eq({{$loop->index}})').inputmask("+63 999 9999 999");
                    @endif
                @endforeach
            @endif
            @if(!old('spContact'))
                $('#persons .contact').inputmask("+63 999 9999 999");
            @else
                @foreach(old('spContact') as $spContact)
                    @if($spContact[2] == '2' && strlen($spContact) >= 17)
                        $('#persons .contact:eq({{$loop->index}})').inputmask("(02) 999 9999 loc. 9999");
                    @elseif($spContact[2] == '2')
                        $('#persons .contact:eq({{$loop->index}})').inputmask("(02) 999 9999");
                    @else
                        $('#persons .contact:eq({{$loop->index}})').inputmask("+63 999 9999 999");
                    @endif
                @endforeach
            @endif
        });
    </script>
@stop