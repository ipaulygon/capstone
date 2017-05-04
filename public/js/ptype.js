$(document).on("click", "#addBrand", function (){
    var value = $("#brand").clone().prepend(
        '<button id="removeBrand" type="button" class="btn btn-flat btn-danger btn-xs pull-right">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button>').appendTo('#brands');
    $(value).find("input").val("");
});

$(document).on("click", "#removeBrand", function (){
    $(this).parent().remove();
});

