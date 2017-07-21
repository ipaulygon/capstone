var textarea = $('#inspectionForm').first();
var form;
$(document).on("click", "#addItem", function (){
    var value = $("#item").clone().prepend(
        '<button id="removeItem" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Remove">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button>'
    ).appendTo('#items');
    $(value).find("input").val("");
    $(value).find("textarea").text("");
});

$(document).on("click", "#addItemUpdate", function (){
    var value = $("#item").clone().prepend(
        '<button id="removeItem" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Remove">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button>'
    ).appendTo('#items');
    $(value).find("input").val("");
    $(value).find("textarea").text("");
    $(value).find("input.hidden").val("");
});

$(document).on('click', '#pushItem', function (){
    item = $(this).parent().find('#inspectionItem');
    if(item.val()==null || item.val()==''){
        item.popover({
            trigger: 'manual',
            content: function(){
                var content = 'Inspection Item is required';
                return content;
            },
            html: true,
            placement: function(){
                var placement = 'auto';
                return placement;
            },
            template: '<div class="popover alert-danger" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        });
        item.popover('show');
        setTimeout(function(){
            item.popover('hide');
        },2000);
    }else{
        $('#itemComponent').text(item.val());
        if($('#form').length){
            var data = form.actions.getData('json');
            $(textarea).text(data);
        }
        textarea = $(this).parent().find('#inspectionForm');
        if(textarea.text()!=''){
            var formData = textarea.text();
            options = {
                dataType: 'json',
                disabledActionButtons: ['clear','save','data'],
                editOnAdd: true,
                formData: formData
            }
        }else{
            options = {
                dataType: 'json',
                disabledActionButtons: ['clear','save','data'],
                editOnAdd: true,
                defaultFields: [
                    {//radio
                        "type": "radio-group",
                        "required": true,
                        "label": "Rating",
                        "inline": true,
                        "className": "",
                        "values": [
                            {"label": "😃","value": "1"},
                            {"label": "😐","value": "2"},
                            {"label": "☹️","value": "3"}
                        ]
                    },//end of radio
                    {
                        "type": "textarea",
                        "label": "Condition",
                        "placeholder": "Condition",
                        "className": "form-control",
                        "subtype": "text",
                        "maxlength": "100",
                    }
                ]//end of defaultFields
            }
        }
        $('#form').remove();
        $('#body').append('<div id="form"></div>');
        form = $('#form').formBuilder(options);
    }
});

$(document).on("click", "#removeItemUpdate", function (){
    id = Number($(this).parent().find('input.hidden').val());
    context = $(this).parent();
    $.ajax({
        type: "GET",
        url: "/inspection/remove/"+id,
        dataType: "JSON",
        success:function(data){
            if(data.message==0){
                $(context).remove();
                if($(textarea).parent()[0]==$(this).parent()[0]){
                    $('#form').remove();
                    textarea = "";
                }
                $('.itemComponent').text('Item Components');
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
});

$(document).on("click", "#removeItem", function (){
    context = $(this).parent();
    if($(textarea).parent()[0]==$(this).parent()[0]){
        $('#form').remove();
        textarea = "";
    }
    $('.itemComponent').text('Item Components');
    $(context).remove();
});

$(document).on('click', '#save', function (){
    if(form!=null){
        var data = form.actions.getData('json');
        $(textarea).text(data);
    }
    $('#submit').submit();
});