@extends('layouts.master')

@section('title')
    {{"Supplier"}}
@stop

@section('content')
    {!! Form::model($supplier , ['method' => 'patch', 'action' => ['SupplierController@update',$supplier->id]]) !!}
    @include('layouts.required')
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Supplier Information</h3>
            </div>
            <div class="box-body">
                @include('supplier.form')
                <div id="numbers">
                    @foreach($supplier->number as $key=>$number)
                        <div id="number" class="form-group">
                            @if($loop->index!=0)
                                <button id="removeNumber" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Remove">
                                    <i class="glyphicon glyphicon-remove"></i>
                                </button>
                            @endif
                            {!! Form::label('scNo', 'Contact Number') !!}<span>*</span>
                            {!! Form::input('text','scNo',$number->scNo,[
                                'class' => 'form-control contact',
                                'id' => $key,
                                'name' => 'scNo[]',
                                'placeholder'=>'Number',
                                'required'])
                            !!}
                        </div>
                    @endforeach
                </div>
                <button id="addNumber" type="button" class="btn btn-sm btn-primary pull-right" data-toggle="tooltip" data-placement="top" title="Add">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </div>
            <div class="box-footer">
                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
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
                @foreach($supplier->person as $person)
                    <div id="person" class="form-group">
                        @if($loop->index!=0)
                            <button id="removePerson" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Add">
                                <i class="glyphicon glyphicon-remove"></i>
                            </button><br>
                        @endif
                        <div class="row">
                        <div class="col-md-6">
                            {!! Form::label('spName', 'Main Contact Person') !!}<span>*</span>
                            {!! Form::input('text',null,$person->spName,[
                                'class' => 'form-control',
                                'name' => 'spName[]',
                                'placeholder'=>'Name',
                                'maxlength'=>'100',
                                'required'])
                            !!}
                        </div>
                        <div class="col-md-6">
                            {!! Form::label('spContact', 'Contact No.') !!}
                            {!! Form::input('text',null,$person->spContact,[
                                'class' => 'form-control contact',
                                'name' => 'spContact[]',
                                'placeholder'=>'Number',
                                'required'])
                            !!}
                        </div>
                    </div>
                    </div>
                @endforeach
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
        });
        @foreach($supplier->number as $number)
            @if($number->scNo[2] == '2' && strlen($number->scNo) >= 17)
                $('#numbers .contact:eq({{$loop->index}})').inputmask("(02) 999 9999 loc. 9999");
            @elseif($number->scNo[2] == '2')
                $('#numbers .contact:eq({{$loop->index}})').inputmask("(02) 999 9999");
            @else
                $('#numbers .contact:eq({{$loop->index}})').inputmask("+63 999 9999 999");
            @endif
        @endforeach
        @foreach($supplier->person as $person)
            @if($person->spContact[2] == '2' && strlen($person->spContact) >= 17)
                $('#persons .contact:eq({{$loop->index}})').inputmask("(02) 999 9999 loc. 9999");
            @elseif($person->spContact[2] == '2')
                $('#persons .contact:eq({{$loop->index}})').inputmask("(02) 999 9999");
            @else
                $('#persons .contact:eq({{$loop->index}})').inputmask("+63 999 9999 999");
            @endif
        @endforeach
    </script>
@stop