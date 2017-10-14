$(document).on("click", "#addModel", function (){
    var value = $("#model").clone().prepend(
        '<button id="removeModel" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Remove">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button><br>').appendTo('#models');
    $(value).find('input.form-control').val('');
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

$(document).on('change','.check',function(){
    current = $(this);
    row = $(this).parents('.transmission');
    if(current.prop('checked')==false){
        current.next('input').val(0);
        auto = row.find('input')[0];
        manual = row.find('input')[2];
        if($(auto).prop('checked')==false && $(manual).prop('checked')==false){
            check = (current.context==auto ? manual : auto);
            $(check).prop('checked',true);
            $(check).next().val(1);
        }
    }else{
        current.next().val(1);
    }
});