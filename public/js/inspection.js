var textarea = $('#inspectionForm').first();
var form;
$(document).on("click", "#addItem", function (){
    var value = $("#item").clone().prepend(
        '<button id="removeItem" type="button" class="btn btn-flat btn-danger btn-xs pull-right">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button>'
    ).appendTo('#items');
    $(value).find("input").val("");
    $(value).find("textarea").text("");
});

$(document).on("click", "#addItemUpdate", function (){
    var value = $("#item").clone().appendTo('#items');
    $(value).find("input").val("");
    $(value).find("textarea").text("");
});

$(document).on('click', '#pushItem', function (){
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
                    "type": "text",
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
});

$(document).on("click", "#removeItem", function (){
    console.log($(textarea).parent()[0]);
    console.log($(this).parent()[0]);
    if($(textarea).parent()[0]==$(this).parent()[0]){
        $('#form').remove();
        textarea = "";
    }
    $(this).parent().remove();
});

$(document).on('click', '#save', function (){
    var data = form.actions.getData('json');
    $(textarea).text(data);
    $('#submit').submit();
});