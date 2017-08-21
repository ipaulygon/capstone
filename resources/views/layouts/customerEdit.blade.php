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
                    'maxlength'=>'45',
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
                    'maxlength'=>'45']) 
                !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('lastName', 'Last Name') !!}<span>*</span>
                {!! Form::input('text','customer[lastName]',null,[
                    'class' => 'form-control',
                    'name' => 'lastName',
                    'id' => 'lastName',
                    'placeholder'=>'Last Name',
                    'maxlength'=>'45',
                    'required']) 
                !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                {!! Form::label('contact', 'Contact No.') !!}<span>*</span>
                {!! Form::input('text','customer[contact]',null,[
                    'class' => 'form-control',
                    'name' => 'contact',
                    'id' => 'contact',
                    'placeholder'=>'Contact',
                    'required']) 
                !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('email', 'Email') !!}
                {!! Form::input('text','customer[email]',null,[
                    'class' => 'form-control',
                    'name' => 'email',
                    'id' => 'email',
                    'maxlength'=>'45',
                    'placeholder'=>'Email']) 
                !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('card', 'Senior Citizen/PWD ID') !!}
                {!! Form::input('text','customer[card]',null,[
                    'class' => 'form-control',
                    'id' => 'card',
                    'maxlength' => '45',
                    'placeholder'=>'Senior Citizen/PWD ID']) 
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
                    'rows' => '1']) 
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
                    'rows' => '1']) 
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