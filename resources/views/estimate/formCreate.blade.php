<div class="col-md-12">
    @include('layouts.customerCreate')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Estimation Details</h3>
        </div>
        <div id="body" class="box-body">
            @include('layouts.vehicleCreate')
            <h4>Estimate Information</h4>
            @include('layouts.itemList')
        </div>
        <div class="box-footer">
            {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
            <div class="form-inline pull-right">
                {!! Form::label('computed', 'Total Price') !!}
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