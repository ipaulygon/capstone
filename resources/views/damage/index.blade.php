@extends('layouts.master')

@section('title')
    {{"Dispose Products"}}
@stop

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/datatables/datatables-responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/pace/pace.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}">
@stop

@section('content')
    {!! Form::open(['url' => 'damage']) !!}
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Inventory Information</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="dataTable_wrapper">
                    <table id="inventoryList" class="table table-striped table-bordered responsive">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-right">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventory as $product)
                            <?php
                                if($product->isOriginal!=null){
                                    $type = ($product->isOriginal=="type1" ? $util->type1 : $util->type2);
                                }else{
                                    $type = "";
                                }
                            ?>
                            <tr>
                                <td>{{$product->brand}} - {{$product->product}} {{$type}} ({{$product->variance}})</td>
                                <td class="text-right">{{number_format($product->quantity)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Dispose</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('products', 'Product Search') !!}<span>*</span>
                            <select id="products" name="productId" class="select2 form-control" style="width:100%">
                                <option value="" selected></option>
                                @foreach($inventory as $product)
                                    @if($product->isOriginal!=null)
                                        <?php $type = ($product->isOriginal=="type1" ? '- '.$util->type1 : '- '.$util->type2); ?>
                                    @else
                                        <?php $type = ''; ?>
                                    @endif
                                    <option data-max="{{$product->quantity}}" value="{{$product->id}}">{{$product->brand}} - {{$product->product}} {{$type}} ({{$product->variance}})</option>
                                @endforeach
                            </select>  
                        </div>
                    </div>
                    <div class="col-md-6">
                        {!! Form::label('quantity', 'Quantity to Dispose:') !!}<span>*</span>
                        {!! Form::input('text','quantity',1,[
                            'class' => 'form-control',
                            'id' => 'quantity',
                            'placeholder'=>'Quantity',
                            'required']) 
                        !!}
                    </div>
                    <div class="col-md-6">
                        {!! Form::label('remarks', 'Remarks:') !!}
                        {!! Form::textarea('remarks',null,[
                            'class' => 'form-control',
                            'id' => 'street',
                            'placeholder'=>'Remarks',
                            'maxlength'=>'140',
                            'rows' => '4']) 
                        !!}
                    </div>
                </div>
            </div>
            <div class="box-footer">
                {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script src="{{ URL::asset('assets/datatables/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/datatables/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.numeric.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/inputmask.phone.extensions.js')}}"></script>
    <script src="{{ URL::asset('assets/plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ URL::asset('js/damage.js') }}"></script>
    <script>
        $(document).ready(function (){
            $('#list').DataTable({
                responsive: true,
            });
            $('#dlist').DataTable({
                responsive: true,
            });
            $(".select2").select2();
        });
    </script>
@stop