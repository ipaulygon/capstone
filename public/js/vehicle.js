$(document).on("click", "#addModel", function (){
    var value = $("#model").clone().prepend(
        '<button id="removeModel" type="button" class="btn btn-flat btn-danger btn-xs pull-right">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button><br>').appendTo('#models');
    $(value).find('input').val('');
    $(value).find('select').val('AT');
    $('.year').inputmask({ 
        alias: "integer",
        prefix: '',
        allowMinus: false,
        min: 1900,
        max: (new Date()).getFullYear(),
    });
});

$(document).on("click", "#removeModel", function (){
    id = $(this).parent().find('input.hidden').val();
    context = $(this).parent();
    if(id!=null){
        $.ajax({
            type: "GET",
            url: "/vehicle/remove/"+id,
            dataType: "JSON",
            success:function(data){
                if(data.message==0){
                    $(context).remove();
                }else{
                    $('#notif').append(
                        '<div class="alert alert-danger alert-dismissible">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        '<h4><i class="icon fa fa-ban"></i> Something went wrong!</h4>' +
                        data.message +
                        '</div>'
                    );
                }
            }
        });
    }else{
        $(context).remove();
    }
});

