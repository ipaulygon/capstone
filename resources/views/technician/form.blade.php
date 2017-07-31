<div class="col-md-12">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Technician Information</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-8">
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
                            !!}<br>
                            <label id="labelAge">Age: </label>
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
                    <div class="row">
                        <div class="form-group col-md-4">
                            {!! Form::label('street', 'No. & St./Bldg.') !!}<span>*</span>
                            {!! Form::textarea('street',null,[
                                'class' => 'form-control',
                                'placeholder'=>'No. & St./Bldg.',
                                'maxlength'=>'140',
                                'rows' => '1',
                                'required']) 
                            !!}
                        </div>
                        <div class="form-group col-md-4">
                            {!! Form::label('brgy', 'Brgy./Subd.') !!}<span>*</span>
                            {!! Form::textarea('brgy',null,[
                                'class' => 'form-control',
                                'placeholder'=>'Brgy./Subd.',
                                'maxlength'=>'140',
                                'rows' => '1',
                                'required']) 
                            !!}
                        </div>
                        <div class="form-group col-md-4">
                            {!! Form::label('city', 'City/Municipality') !!}<span>*</span>
                            {!! Form::textarea('city',null,[
                                'class' => 'form-control',
                                'placeholder'=>'City/Municipality',
                                'maxlength'=>'140',
                                'rows' => '1',
                                'required']) 
                            !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('skill', 'Technician Skill(s)') !!}<span>*</span>
                        <select id="ts" name="skill[]" class="select2 form-control" multiple required>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
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
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
</div>