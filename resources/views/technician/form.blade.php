<div class="col-md-8 col-md-offset-2">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Technician Information</h3>
        </div>
        <div class="box-body">
            <center><img class="img-responsive" id="tech-pic" src="{{ URL::asset($image)}}" style="max-width:150px; background-size: contain" /></center>
            <center>
                {!! Form::label('pic', 'Technician Picture') !!}
                {!! Form::file('image',[
                    'class' => 'form-control',
                    'name' => 'image',
                    'class' => 'btn btn-success btn-sm',
                    'onchange' => 'readURL(this)']) 
                !!}
            </center>
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('firstName', 'First Name') !!}<span>*</span>
                    {!! Form::input('text','firstName',null,[
                        'class' => 'form-control',
                        'placeholder'=>'First Name',
                        'maxlength'=>'100',
                        'required']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('middleName', 'Middle Name') !!}
                    {!! Form::input('text','middleName',null,[
                        'class' => 'form-control',
                        'placeholder'=>'Middle Name',
                        'maxlength'=>'100']) 
                    !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('lastName', 'Last Name') !!}<span>*</span>
                    {!! Form::input('text','lastName',null,[
                        'class' => 'form-control',
                        'placeholder'=>'Last Name',
                        'maxlength'=>'100',
                        'required']) 
                    !!}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('birthdate', 'Birthdate') !!}<span>*</span>
                    {!! Form::input('text','birthdate',$date,[
                        'class' => 'form-control',
                        'id' => 'bday',
                        'placeholder'=>'Birthdate',
                        'required']) 
                    !!}
                </div>
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
                        'placeholder'=>'Email']) 
                    !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('address', 'Address') !!}<span>*</span>
                {!! Form::textarea('address',null,[
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
        </div>
    </div>
</div>