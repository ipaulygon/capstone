<div class="col-md-12">
    {{-- CUSTOMER --}}
    <div class="box box-primary box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Customer Information</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('firstName', 'First Name') !!}<span>*</span>
                    {!! Form::input('text','customer[firstName]',null,[
                        'class' => 'form-control',
                        'name' => 'firstName',
                        'id' => 'firstName',
                        'placeholder'=>'First Name',
                        'maxlength'=>'100',
                        'required']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('middleName', 'Middle Name') !!}
                    {!! Form::input('text','customer[middleName]',null,[
                        'class' => 'form-control',
                        'name' => 'middleName',
                        'id' => 'middleName',
                        'placeholder'=>'Middle Name',
                        'maxlength'=>'100']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('lastName', 'Last Name') !!}<span>*</span>
                    {!! Form::input('text','customer[lastName]',null,[
                        'class' => 'form-control',
                        'name' => 'lastName',
                        'id' => 'lastName',
                        'placeholder'=>'Last Name',
                        'maxlength'=>'100',
                        'required']) 
                    !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    {!! Form::label('contact', 'Contact No.') !!}<span>*</span>
                    {!! Form::input('text','customer[contact]',null,[
                        'class' => 'form-control',
                        'name' => 'contact',
                        'id' => 'contact',
                        'placeholder'=>'Contact',
                        'required']) 
                    !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::input('text','customer[email]',null,[
                        'class' => 'form-control',
                        'name' => 'email',
                        'id' => 'email',
                        'placeholder'=>'Email']) 
                    !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('street', 'No. & St./Bldg.') !!}
                    {!! Form::textarea('customer[street]',null,[
                        'class' => 'form-control',
                        'name' => 'street',
                        'id' => 'street',
                        'placeholder'=>'No. & St./Bldg.',
                        'maxlength'=>'140',
                        'rows' => '1',
                        'required']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('brgy', 'Brgy./Subd.') !!}
                    {!! Form::textarea('customer[brgy]',null,[
                        'class' => 'form-control',
                        'name' => 'brgy',
                        'id' => 'brgy',
                        'placeholder'=>'Brgy./Subd.',
                        'maxlength'=>'140',
                        'rows' => '1',
                        'required']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('city', 'City/Municipality') !!}<span>*</span>
                    {!! Form::textarea('customer[city]',null,[
                        'class' => 'form-control',
                        'name' => 'city',
                        'id' => 'city',
                        'placeholder'=>'City/Municipality',
                        'maxlength'=>'140',
                        'rows' => '1',
                        'required']) 
                    !!}
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Inspection Details</h3>
        </div>
        <div id="body" class="box-body">
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('plate', 'Vehicle Plate') !!}<span>*</span>
                    {!! Form::input('text','vehicle[plate]',null,[
                        'class' => 'form-control',
                        'name' => 'plate',
                        'id' => 'plate',
                        'placeholder'=>'Vehicle Plate',
                        'required']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('modelId', 'Vehicle Model') !!}<span>*</span>
                    <select id="model" name="modelId" class="select2 form-control" required>
                        @foreach($models as $model)
                            <option value="{{$model->id}}">{{$model->make}} - {{$model->year}} {{$model->name}} ({{$model->transmission}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('mileage', 'Mileage') !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-tachometer"></i></span>
                        {!! Form::input('text','vehicle[mileage]',null,[
                            'class' => 'form-control',
                            'name' => 'mileage',
                            'id' => 'mileage',
                            'placeholder'=>'Mileage']) 
                        !!}
                    </div>
                </div>
            </div>
            <div id="form-box" class="row">
            </div>
        </div>
        <div class="box-footer">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('type', 'Technician(s) Assigned') !!}<span>*</span>
                        <select id="technician" name="technician[]" class="select2 form-control" multiple required>
                            @foreach($technicians as $technician)
                                <option value="{{$technician->id}}">{{$technician->firstName}} {{$technician->middleName}} {{$technician->lastName}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('remarks', 'Remarks') !!}
                        {!! Form::textarea('remarks',null,[
                            'class' => 'form-control',
                            'placeholder'=>'Remarks',
                            'maxlength'=>'140',
                            'rows' => '1']) 
                        !!}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
            </div>
        </div>
    </div>
</div>