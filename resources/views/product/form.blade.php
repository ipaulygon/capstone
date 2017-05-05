<div class="col-md-10">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Product Information</h3>
        </div>
        <div class="box-body">
            <div class="row">
                {{-- Main --}}
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', 'Product') !!}<span>*</span>
                        {!! Form::input('text','name',null,[
                            'class' => 'form-control',
                            'placeholder'=>'Name',
                            'maxlength'=>'50',
                            'required']) 
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
                </div>
                {{-- Type, Brand, Variance --}}
                <div class="col-md-4">
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
                {{-- Price, Reorder --}}
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('price', 'Price') !!}<span>*</span>
                        <div class="input-group">
                            <span class="input-group-addon">PhP</span>
                            {!! Form::input('text','price',null,[
                                'class' => 'form-control',
                                'placeholder'=>'Price',
                                'maxlength'=>'8',
                                'required']) 
                            !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('reorder', 'Reorder Level') !!}<span>*</span>
                        {!! Form::input('text','reorder',null,[
                            'class' => 'form-control',
                            'placeholder'=>'Reorder Level',
                            'maxlength'=>'3',
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