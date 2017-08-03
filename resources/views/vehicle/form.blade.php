<div class="col-md-4">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Vehicle Information</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name', 'Vehicle Make') !!}<span>*</span>
                {!! Form::input('text','name',null,[
                    'class' => 'form-control make',
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