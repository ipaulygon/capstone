$(document).on("click", "#addItem", function (){
    var value = $("#item").clone().prepend(
        '<button id="removeItem" type="button" class="btn btn-flat btn-danger btn-xs pull-right">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button>').appendTo('#items');
    $(value).find("input").val("");
    $(value).find("textarea").text("");
    $('#form').clone().appendTo('#forms');
    options = {
        dataType: 'json',
        disabledActionButtons: ['clear','save','data'],
        editOnAdd: true,
    }
    $('#build-wrap').formBuilder();
});

$(document).on("click", "#removeItem", function (){
    $(this).parent().remove();
});