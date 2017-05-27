$(document).on("click", "#addDimension", function (){
    var value = $('#dimension').clone().prepend(
        '<button id="removeDimension" type="button" class="btn btn-flat btn-danger btn-xs pull-right">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button>').appendTo('#dimensions');
    var select = $('#unit').closest();
    $('#dimension').find('#unit option').clone().appendTo(select);
    $(value).find('input').val('');
    $(".dim").inputmask({ 
        alias: "integer",
        prefix: '',
        allowMinus: false,
        min: 0,
        max: 10000,
    });
});

$(document).on("click", "#removeDimension", function (){
    $(this).parent().remove();
});

