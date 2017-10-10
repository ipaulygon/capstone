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
                        <input id="sizeId" type="radio" class="square-blue" name="size" value="Sedan" required> Sedan
                    </div>
                    <div class="col-md-6">
                        <input id="sizeId" type="radio" class="square-blue" name="size" value="Large" required> Large Vehicle
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
            <div class="{{($util->isWarranty ? '' : 'hidden')}}">
                <fieldset>
                    {!! Form::label('isWarranty', 'Warranty Details:') !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{--  {!! Form::label('hasWarranty', 'Warranty') !!}<span>*</span><br>  --}}
                                @php 
                                    if(!isset($product)){
                                        $warrantyChecked = 'checked';
                                    }else{
                                        $warrantyChecked = ($product->isWarranty ? 'checked':'');
                                    }
                                @endphp
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="warranty" name="hasWarranty" value="1" {{$warrantyChecked}}> Warranty
                                    <input type="hidden" id="isWarranty" name="isWarranty" value="1">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('year', 'Year') !!}
                                {!! Form::input('text','year',null,[
                                    'class' => 'form-control',
                                    'id' => 'year',
                                    'placeholder'=>'Year']) 
                                !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('month', 'Month') !!}
                                {!! Form::input('text','month',null,[
                                    'class' => 'form-control',
                                    'id' => 'month',
                                    'placeholder'=>'Month']) 
                                !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('day', 'Day') !!}
                                {!! Form::input('text','day',null,[
                                    'class' => 'form-control',
                                    'id' => 'day',
                                    'placeholder'=>'Day']) 
                                !!}
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
</div>