<div class="col-md-8">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Product Information</h3>
        </div>
        <div class="box-body">
            <div class="row">
                {{-- Main --}}
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('name', 'Product') !!}<span>*</span>
                        {!! Form::input('text','name',null,[
                            'class' => 'form-control',
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
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
</div>
@if($types->first()->category=='Parts')
<div id="part" class="col-md-4">
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
                        <input id="isOriginal" type="radio" class="square-blue" name="isOriginal" value="Original"> Original
                    </div>
                    <div class="col-md-6">
                        <input id="isOriginal" type="radio" class="square-blue" name="isOriginal" value="Replacement"> Replacement
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('vehicle', 'Equipped in') !!}<span>*</span>
                <select id="vehicle" name="vehicle[]" class="select2 form-control" multiple>
                    @foreach($vehicles as $vehicle)
                        <option value="{{$vehicle->id}}">{{$vehicle->make}} - {{$vehicle->year}} {{$vehicle->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
@endif