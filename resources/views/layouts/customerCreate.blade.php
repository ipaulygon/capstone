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
                {!! Form::input('text','firstName',null,[
                    'class' => 'form-control autocomplete',
                    'id' => 'firstName',
                    'placeholder'=>'First Name',
                    'maxlength'=>'45',
                    'required']) 
                !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('middleName', 'Middle Name') !!}
                {!! Form::input('text','middleName',null,[
                    'class' => 'form-control',
                    'id' => 'middleName',
                    'placeholder'=>'Middle Name',
                    'maxlength'=>'45']) 
                !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('lastName', 'Last Name') !!}<span>*</span>
                {!! Form::input('text','lastName',null,[
                    'class' => 'form-control',
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
                {!! Form::input('text','contact',null,[
                    'class' => 'form-control',
                    'id' => 'contact',
                    'placeholder'=>'Contact',
                    'required']) 
                !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('email', 'Email') !!}
                {!! Form::input('text','email',null,[
                    'class' => 'form-control',
                    'id' => 'email',
                    'maxlength' => '45',
                    'placeholder'=>'Email']) 
                !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('card', 'Senior Citizen/PWD ID') !!}
                {!! Form::input('text','card',null,[
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
                {!! Form::textarea('street',null,[
                    'class' => 'form-control',
                    'id' => 'street',
                    'placeholder'=>'No. & St./Bldg.',
                    'maxlength'=>'140',
                    'rows' => '1']) 
                !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('brgy', 'Brgy./Subd.') !!}
                {!! Form::textarea('brgy',null,[
                    'class' => 'form-control',
                    'id' => 'brgy',
                    'placeholder'=>'Brgy./Subd.',
                    'maxlength'=>'140',
                    'rows' => '1']) 
                !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('city', 'City/Municipality') !!}<span>*</span>
                {!! Form::textarea('city',null,[
                    'class' => 'form-control',
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