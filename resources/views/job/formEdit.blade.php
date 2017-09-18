<div class="col-md-12">
    <a href="{{url('/job')}}" type="button" class="btn btn-success btn-md"><i class="fa fa-angle-double-left"></i> Back</a><br><br>
    @include('layouts.customerEdit')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Job Order Details</h3>
        </div>
        <div id="body" class="box-body">
            @include('layouts.vehicleEdit')
            <label>Item List <i id="infoInventory" class="fa fa-question-circle"></i></label>
            @include('layouts.itemList')
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
            <div class="form-inline pull-right">
                {!! Form::label('computed', 'Total Price',[
                    'style' => 'font-size:18px']) !!}
                <div class="input-group">
                    <span class="input-group-addon" style="border: none!important">PhP</span>
                    <strong>{!! Form::input('text','computed',0,[
                        'class' => 'form-control',
                        'id' => 'compute',
                        'style' => 'border: none!important;background: transparent!important',
                        'readonly']) 
                    !!}</strong>
                </div>
            </div>
        </div>
    </div>
</div>