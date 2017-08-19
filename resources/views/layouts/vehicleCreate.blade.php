<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('type', 'Technician(s) Assigned') !!}<span>*</span>
            <select id="technician" name="technician[]" class="select2 form-control" style="width:100%" multiple required>
                @foreach($technicians as $technician)
                    <option value="{{$technician->id}}">{{$technician->firstName}} {{$technician->middleName}} {{$technician->lastName}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('rack', 'Rack') !!}<span>*</span><br>
            <select id="rack" name="rackId" class="select2 form-control" required>
                @foreach($racks as $rack)
                    <option value="{{$rack->id}}">{{$rack->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        {!! Form::label('plate', 'Vehicle Plate') !!}<span>*</span>
        {!! Form::input('text','plate',null,[
            'class' => 'form-control autocomplete',
            'id' => 'plate',
            'placeholder'=>'Vehicle Plate',
            'required']) 
        !!}
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('modelId', 'Vehicle Model') !!}<span>*</span>
        <select id="model" name="modelId" class="select2 form-control" style="width:100%" required>
            @foreach($autos as $model)
                <option value="{{$model->id}},0">{{$model->make}} - {{$model->year}} {{$model->name}} - AT</option>
            @endforeach
            @foreach($manuals as $model)
                <option value="{{$model->id}},1">{{$model->make}} - {{$model->year}} {{$model->name}} - MT</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('mileage', 'Mileage') !!}
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-tachometer"></i></span>
            {!! Form::input('text','mileage',null,[
                'class' => 'form-control',
                'id' => 'mileage',
                'placeholder'=>'Mileage']) 
            !!}
        </div>
    </div>
</div>