<div class="col-md-8">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Product Information</h3>
        </div>
        <div class="box-body">
            <div class="row">
                {{-- Type, Brand, Variance --}}
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('type', 'Type') !!}<span>*</span>
                        <select id="pt" onchange="changeType(this.value)" name="typeId" class="select2 form-control" required>
                            @foreach($types as $type)
                                <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        {!! Form::label('type', 'Brand') !!}<span>*</span>
                        <select id="pb" name="brandId" class="select2 form-control" required>
                            @foreach($brands as $brand)
                                <option value="{{$brand->brand->id}}">{{$brand->brand->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        {!! Form::label('type', 'Variance') !!}<span>*</span>
                        <select id="pv" name="varianceId" class="select2 form-control" required>
                            @foreach($variances as $variance)
                                <option value="{{$variance->variance->id}}">{{$variance->variance->name}}</option>
                            @endforeach
                        </select>
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
                {{-- Main --}}
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('name', 'Product') !!}<span>*</span>
                        {!! Form::input('text','name',null,[
                            'class' => 'form-control autocomplete',
                            'id' => 'productName',
                            'placeholder'=>'Name',
                            'maxlength'=>'50']) 
                        !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('description', 'Description') !!}
                        {!! Form::textarea('description',null,[
                            'class' => 'form-control',
                            'placeholder'=>'Description',
                            'maxlength'=>'50',
                            'rows'=>'4']) 
                        !!}
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
                    <div class="form-group">
                        {!! Form::label('reorder', 'Reorder Level') !!}<span>*</span>
                        {!! Form::input('text','reorder',null,[
                            'class' => 'form-control',
                            'id' => 'reorder',
                            'placeholder'=>'Reorder Level',
                            'required']) 
                        !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
</div>
<div id="part" class="{{($types->first()->category=='category1' ? 'col-md-4' : 'col-md-4 hidden')}}">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Part Information</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('isOriginal', 'Part Type') !!}<span>*</span>
                <div class="row">
                    <div class="col-md-6">
                        <input id="isOriginal" type="radio" class="square-blue" name="isOriginal" value="type1"> {{$util->type1}}
                    </div>
                    <div class="col-md-6">
                        <input id="isOriginal" type="radio" class="square-blue" name="isOriginal" value="type2"> {{$util->type2}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('vehicle', 'Equipped in') !!}
                <select id="vehicle" name="vehicle[]" class="select2 form-control" multiple>
                    @foreach($autos as $vehicle)
                        <option value="{{$vehicle->id}},0">{{$vehicle->make}} - {{$vehicle->name}} - AT</option>
                    @endforeach
                    @foreach($manuals as $vehicle)
                        <option value="{{$vehicle->id}},1">{{$vehicle->make}} - {{$vehicle->name}} - MT</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>