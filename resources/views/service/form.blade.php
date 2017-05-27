<div class="col-md-5">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Service Information</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name', 'Service') !!}<span>*</span>
                {!! Form::input('text','name',null,[
                    'class' => 'form-control',
                    'placeholder'=>'Name',
                    'maxlength'=>'50',
                    'required']) 
                !!}
            </div>
            <div class="form-group">
                {!! Form::label('category', 'Category') !!}<span>*</span>
                <select id="sc" name="categoryId" class="form-control select2" required>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                {!! Form::label('size', 'Size') !!}<span>*</span><br>
                <div class="row">
                    <div class="col-md-6">
                        <input id="sizeId" type="radio" class="flat-red" name="size" value="Sedan" required> Sedan
                    </div>
                    <div class="col-md-6">
                        <input id="sizeId" type="radio" class="flat-red" name="size" value="Large" required> Large Vehicle
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('price', 'Price') !!}<span>*</span>
                <div class="input-group">
                    <span class="input-group-addon">PhP</span>
                    {!! Form::input('text','price',null,[
                        'class' => 'form-control',
                        'id' => 'price',
                        'placeholder'=>'Price',
                        'required']) 
                    !!}
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
</div>