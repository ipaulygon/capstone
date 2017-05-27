<div class="col-md-4">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Discount Information</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name', 'Discount') !!}<span>*</span>
                {!! Form::input('text','name',null,[
                    'class' => 'form-control',
                    'placeholder'=>'Name',
                    'maxlength'=>'50',
                    'required']) 
                !!}
            </div>
            <div class="form-group">
                {!! Form::label('rate', 'Rate') !!}<span>*</span>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                    {!! Form::input('text','rate',null,[
                        'class' => 'form-control',
                        'id' => 'rate',
                        'placeholder'=>'Rate',
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

<div class="col-md-4">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Product</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('product', 'Product(s)') !!}
                <select id="product" name="product[]" class="select2 form-control" multiple>
                    @foreach($products as $product)
                        <option value="{{$product->id}}">{{$product->brand}} - {{$product->name}} ({{$product->variance}})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Service</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('service', 'Service(s)') !!}
                <select id="service" name="service[]" class="select2 form-control" multiple>
                    @foreach($services as $service)
                        <option value="{{$service->id}}">{{$service->name}} - {{$service->size}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
