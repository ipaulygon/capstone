<div id="notif">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" data-toggle="tooltip" title="Close">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
            {!! implode('', $errors->all(
                '<li>:message</li>'
            )) !!}
        </div>
    @endif
    @if(Session::has('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" data-toggle="tooltip" title="Close">&times;</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            {{ Session::get('success') }}
        </div>
    @endif
    @if(Session::has('warranty'))
        <div class="alert alert-success alert-dismissible fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" data-toggle="tooltip" title="Close">&times;</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            {{ Session::get('warranty') }}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" data-toggle="tooltip" title="Close">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>
            {{ Session::get('error') }}
        </div>
    @endif
</div>