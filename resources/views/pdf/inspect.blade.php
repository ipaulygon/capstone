<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$util->name}} | Basic Inspection Form</title>
    <!-- Styles -->
    {{-- <link rel="stylesheet" href="{{ URL::asset('assets/style.css') }}"> --}}
    <link rel="stylesheet" href="{{ URL::asset('assets/jquery/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/bootstrap/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/bootstrap/css/ionicons.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/formbuilder/form-builder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/formbuilder/form-render.min.css') }}">
</head>
<body id="hello">
    <center>
        <h2>{{$util->name}}</h2>
        <h4>BASIC INSPECTION FORM</h4>
    </center>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <center><h2 class="panel-title">Customer Details</h2></center>
                    </div>
                    <div class="panel-body">
                        <div class="row" style="width:100%">
                            <div class="col-md-6" style="float:left;width:50%">
                                Customer: {{$inspect->customer->firstName}} {{$inspect->customer->middleName}} {{$inspect->customer->lastName}}<br>
                                Address: {{$inspect->customer->street}} {{$inspect->customer->brgy}} {{$inspect->customer->city}}<br>
                                Phone Number: {{$inspect->customer->contact}}<br>
                                Email: {{$inspect->customer->email}}<br>
                                <br>
                                <br>
                            </div>
                            <div class="col-md-6" style="float:right:width:50%">
                                <?php $transmission = ($inspect->vehicle->isManual ? 'MT' : 'AT')?>
                                Plate: {{$inspect->vehicle->plate}}<br>
                                Make: {{$inspect->vehicle->model->make->name}}<br>
                                Model: {{$inspect->vehicle->model->name}}<br>
                                Year: {{$inspect->vehicle->model->year}}<br>
                                Transmission: {{$transmission}}<br>
                                Mileage: {{$inspect->vehicle->mileage}}<br>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="form-box"></div>
            </div>            
        </div>
    </div>
    <script src="{{ URL::asset('assets/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/jquery/jquery-ui.min.js') }}"></script>
    <script src="{{ URL::asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/formbuilder/form-builder.min.js') }}"></script>
    <script src="{{ URL::asset('assets/formbuilder/form-render.min.js') }}"></script>
    <script src="{{ URL::asset('js/inspect.js') }}"></script>
    <script src="{{ URL::asset('assets/jspdf/jspdf.js') }}"></script>
    <script src="{{ URL::asset('assets/jspdf/from_html.js') }}"></script>
    <script src="{{ URL::asset('assets/jspdf/split_text_to_size.js') }}"></script>
    <script src="{{ URL::asset('assets/jspdf/standard_fonts_metrics.js') }}"></script>
    @foreach($inspect->detail as $detail)
        <script>
            form = JSON.stringify({!! $detail->remarks !!});
            pdfForm({{$detail->item->typeId}},"{{$detail->item->type->type}}",{{$detail->itemId}},"{{$detail->item->name}}",form)
        </script>
    @endforeach
</body>
</html>