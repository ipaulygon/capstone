<div class="col-md-4">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Product Type Information</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name', 'Product Type') !!}<span>*</span>
                {!! Form::input('text','name',null,[
                    'class' => 'form-control',
                    'placeholder'=>'Name',
                    'maxlength'=>'50',
                    'required']) 
                !!}
            </div>
            <div class="form-group">
                {!! Form::label('category', 'Category') !!}<span>*</span>
                <div class="row">
                    <div class="col-md-6">
                        <input id="category" type="radio" class="square-blue" name="category" value="category1" required> {{$util->category1}}
                    </div>
                    <div class="col-md-6">
                        <input id="category" type="radio" class="square-blue" name="category" value="category2" required> {{$util->category2}}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
</div>