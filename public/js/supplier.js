$(document).on("click", "#addPerson", function (){
    var value = $("#person").clone().prepend(
        '<button id="removePerson" type="button" class="btn btn-flat btn-danger btn-xs pull-right">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button>').appendTo('#persons');
    $(value).find("input").val("");
});

$(document).on("click", "#removePerson", function (){
    $(this).parent().remove();
});

$(document).on("click", "#addNumber", function (){
    var value = $("#number").clone().prepend(
        '<button id="removeNumber" type="button" class="btn btn-flat btn-danger btn-xs pull-right">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button>').appendTo('#numbers');
    $(value).find("input").val("");
    $(value).find("input").inputmask("(+639)99-9999-999");
});

$(document).on("click", "#removeNumber", function (){
    $(this).parent().remove();
});

$(document).on('keypress','.contact',function(){
    if($(this).val()[4]=='9'){
        $(this).inputmask("(+639)99-9999-999");
    }else if($(this).val()[4]=='2'){
        $(this).inputmask("(+639)999-9999");
    }else{
        $(this).inputmask("(+639) ERROR");
    }
});