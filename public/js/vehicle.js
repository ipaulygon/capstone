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
    $(this).parent().remove();
});

