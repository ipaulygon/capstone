<div class="form-group">
    {!! Form::label('name', 'Supplier') !!}<span>*</span>
    {!! Form::input('text','name',null,[
        'class' => 'form-control',
        'placeholder'=>'Name',
        'maxlength'=>'75',
        'required']) 
    !!}
</div>
{!! Form::label('address', 'Address') !!}
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