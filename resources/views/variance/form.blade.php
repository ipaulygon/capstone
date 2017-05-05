<div class="col-md-6">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Variance Information</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name', 'Variance') !!}<span>*</span>
                {!! Form::input('text','name',null,[
                    'class' => 'form-control',
                    'placeholder'=>'Name',
                    'maxlength'=>'50',
                    'required']) 
                !!}
            </div>
            <div class="form-group">
                {!! Form::label('isOriginal', 'Variance Type') !!}<span>*</span><br>
                <div class="row">
                    <div class="col-md-6">
                        <input id="isOriginal" type="radio" class="flat-red" name="isOriginal" value="1" required> Original
                    </div>
                    <div class="col-md-6">
                        <input id="isOriginal" type="radio" class="flat-red" name="isOriginal" value="0" required> Replacement
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('type', 'Product Type(s)') !!}<span>*</span>
                <select id="pt" name="type[]" class="select2 form-control" multiple required>
                    @foreach($types as $type)
                        <option id="type{{$type->id}}" value="{{$type->id}}">{{$type->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
</div>