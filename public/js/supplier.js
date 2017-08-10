var contact;
$(document).on("click", "#addPerson", function (){
    var value = $("#person").clone().prepend(
        '<button id="removePerson" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Remove">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button><br>').appendTo('#persons');
    $(value).find("input").val('');
    $(value).find(".contact").inputmask("+63 999 9999 999");
    $(value).find('label').eq(0).text('Contact Person');
});

$(document).on("click", "#removePerson", function (){
    $(this).parent().remove();
});

$(document).on("click", "#addNumber", function (){
    var value = $("#number").clone().prepend(
        '<button id="removeNumber" type="button" class="btn btn-flat btn-danger btn-xs pull-right" data-toggle="tooltip" data-placement="top" title="Remove">' +
        '<i class="glyphicon glyphicon-remove"></i>' +
        '</button>').appendTo('#numbers');
    $(value).find("input").val('');
    $(value).find("input").inputmask("+63 999 9999 999");
});

$(document).on("click", "#removeNumber", function (){
    $(this).parent().remove();
});

// $(document).on('keypress','.contact',function(){
//     if($(this).val()[4]=='9'){
//         $(this).inputmask("+63 999 9999 999");
//     }else if($(this).val()[4]=='2'){
//         $(this).inputmask("+63 9 999 9999");
//     }else{
//         $(this).inputmask("+63 9 ERROR");
//     }
// });

$(document).on('focus','.contact',function(){
    contact = $(this);
    $(this).popover({
        trigger: 'manual',
        content: function(){
            var content = '<button type="button" id="cp" class="btn btn-primary btn-block">Mobile No.</button><button type="button" id="tp" class="btn btn-primary btn-block">Telephone No.</button><button type="button" id="tpl" class="btn btn-primary btn-block">Telephone No. w/ Local</button>';
            return content;
        },
        html: true,
        placement: function(){
            var placement = 'top';
            return placement;
        },
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        title: function(){
            var title = 'Choose a format:';
            return title;
        }
    });
    $(this).popover('show');
});
$(document).on('focusout','.contact',function(){
    $(this).popover('hide');
});
$(document).on('click','#cp',function(){
    contact.val('');
    contact.inputmask("+63 999 9999 999");
});

$(document).on('click','#tp',function(){
    contact.val('');
    contact.inputmask("(02) 999 9999");
});
$(document).on('click','#tpl',function(){
    contact.val('');
    contact.inputmask("(02) 999 9999 loc. 9999");
});