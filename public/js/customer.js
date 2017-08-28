var signature = null;
var signatureLink = null;
$('#email').inputmask("email");
$("#mileage").inputmask({ 
    alias: "decimal",
    prefix: '',
    suffix: ' km',
    allowMinus: false,
    autoGroup: true,
    min: 0,
    max: 1000000
});
function signatureModal(id,type){
    signature = id;
    signatureLink = type;
    $("#signatureCanvas").jSignature();
    $('#signatureCanvas').find('canvas').attr('height','143');
    $('#signatureCanvas').find('canvas').css('height','143px');
    $('#signatureCanvas').find('canvas').attr('width','570');
    $("#signatureCanvas").jSignature('reset');
    $('#signatureModal').modal('show');
}

$(document).on('click','#signatureReset',function(){
    $("#signatureCanvas").jSignature('reset');
});

$('#signature').on('click', function (){
    pic = $('.jSignature').get(0).toDataURL();
    $.ajax({
        type: 'POST',
        url: '/signature',
        data: {pic:pic},
        success:function(data){
            window.location.replace('/'+signatureLink+'/pdf/'+signature);
        }
    })
});

$(document).on('focus','#contact',function(){
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
$(document).on('focusout','#contact',function(){
    $(this).popover('hide');
});
$(document).on('click','#cp',function(){
    $('#contact').val('');
    $('#contact').inputmask("+63 999 9999 999");
});

$(document).on('click','#tp',function(){
    $('#contact').val('');
    $('#contact').inputmask("(02) 999 9999");
});
$(document).on('click','#tpl',function(){
    $('#contact').val('');
    $('#contact').inputmask("(02) 999 9999 loc. 9999");
});

$(document).on('focus','#plate',function(){
    $(this).popover({
        trigger: 'manual',
        content: function(){
            var content = '<button type="button" id="po" class="btn btn-primary btn-block">ABC 123</button><button type="button" id="ps" class="btn btn-primary btn-block">ABC 45</button><button type="button" id="pn" class="btn btn-primary btn-block">ABC 1234</button><button type="button" id="pnn" class="btn btn-primary btn-block">AB 1234</button><button type="button" id="pvip" class="btn btn-primary btn-block">VIP</button><button type="button" id="ph" class="btn btn-primary btn-block">For Registration</button>';
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
$(document).on('focusout','#plate',function(){
    $(this).popover('hide');
});
$(document).on('click','#po',function(){
    $('#plate').val('');
    $('#plate').inputmask("AAA 999");
});
$(document).on('click','#pn',function(){
    $('#plate').val('');
    $('#plate').inputmask("AAA 9999");
});
$(document).on('click','#ps',function(){
    $('#plate').val('');
    $('#plate').inputmask("AAA 99");
});
$(document).on('click','#pnn',function(){
    $('#plate').val('');
    $('#plate').inputmask("AA 9999");
});
$(document).on('click','#pvip',function(){
    $('#plate').val('');
    $('#plate').inputmask("99");
});
$(document).on('click','#ph',function(){
    $('#plate').val('');
    $('#plate').inputmask();
    $('#plate').val("For Registration");
});

$('#firstName').on('autocompleteselect',function(event, ui){
    name = ui.item.value
    $.ajax({
        type: "GET",
        url: "/item/customer/"+name,
        dataType: "JSON",
        success:function(data){
            $('#firstName').val(data.customer.firstName);
            $('#middleName').val(data.customer.middleName);
            $('#lastName').val(data.customer.lastName);
            if(data.customer.contact[2] == '2' && data.customer.contact.length >= 17){
                $('#contact').inputmask("(02) 999 9999 loc. 9999");
            }else if(data.customer.contact[2] == '2'){
                $('#contact').inputmask("(02) 999 9999");
            }else{
                $('#contact').inputmask("+63 999 9999 999");
            }
            $('#contact').val(data.customer.contact);
            $('#email').val(data.customer.email);
            $('#street').text(data.customer.street);
            $('#brgy').text(data.customer.brgy);
            $('#city').text(data.customer.city);
        }
    });
});

$('#plate').on('change',function(){
    name = $(this).val();
    if(name.length>2 && name.length!=16){
        $.ajax({
            type: "GET",
            url: "/item/vehicle/"+name,
            dataType: "JSON",
            success:function(data){
                $('#plate').val(data.vehicle.plate);
                $('#model').val(data.vehicle.modelId+','+data.vehicle.isManual);
                $('#mileage').val(data.vehicle.mileage);
                $('#model').select2();
            }
        });
    }else if(name.length==2){
        if(Number(name)>16){
            $('#plate').val('16');
        }
    }
});
