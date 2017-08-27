<div class="col-md-12">
    <button id="backNew" type="button" class="btn btn-success btn-md"><i class="fa fa-angle-double-left"></i> Back</button><br><br>
    @include('layouts.customerCreate')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Job Order Details</h3>
        </div>
        <div id="body" class="box-body">
            @include('layouts.vehicleCreate')
            <h4>Item List <i id="infoInventory" class="fa fa-question-circle"></i></h4>
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