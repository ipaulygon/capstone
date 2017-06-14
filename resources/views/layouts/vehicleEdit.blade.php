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
</div>
<div class="row">
    <div class="form-group col-md-4">
        {!! Form::label('plate', 'Vehicle Plate') !!}<span>*</span>
        {!! Form::input('text','vehicle[plate]',null,[
            'class' => 'form-control',
            'name' => 'plate',
            'id' => 'plate',
            'placeholder'=>'Vehicle Plate',
            'required']) 
        !!}
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('modelId', 'Vehicle Model') !!}<span>*</span>
        <select id="model" name="modelId" class="select2 form-control" style="width:100%" required>
            @foreach($models as $model)
                <option value="{{$model->id}}">{{$model->make}} - {{$model->year}} {{$model->name}} ({{$model->transmission}})</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('mileage', 'Mileage') !!}
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-tachometer"></i></span>
            {!! Form::input('text','vehicle[mileage]',null,[
                'class' => 'form-control',
                'name' => 'mileage',
                'id' => 'mileage',
                'placeholder'=>'Mileage']) 
            !!}
        </div>
    </div>
</div>