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

$(document).on('focus','#plate',function(){
    $(this).popover({
        trigger: 'manual',
        content: function(){
            var content = '<button type="button" id="po" class="btn btn-primary btn-block">ABC 123</button><button type="button" id="pn" class="btn btn-primary btn-block">ABC 1234</button><button type="button" id="ph" class="btn btn-primary btn-block">For Registration</button>';
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
$(document).on('click','#ph',function(){
    $('#plate').val('');
    $('#plate').inputmask();
    $('#plate').val("For Registration");
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

function popForm(typeId,typeName,itemId,itemName,form){
    if(!$('#panel'+typeId+'').length){
        $('#form-box').append(
            '<div class="col-md-6">' +
            '<div id="panel'+typeId+'" class="panel panel-default">' +
            '<div class="panel-heading">' +
            '<h2 class="panel-title" style="font-weight:bold!important">'+typeName+'</h2>' +
            '</div>' +
            '<div id="body'+typeId+'" class="panel-body"></div>' +
            '</div>' +
            '</div>'
        );
    }
    $('#body'+typeId+'').append(
        '<div id="item'+itemId+'" class="row formed"></div>'
    );
    var formContainer = $('#item'+itemId+'');
    var formData = form;
    var formRenderOpts = {
        dataType: 'json',
        formData: formData
    };
    formContainer.formRender(formRenderOpts);
    formContainer.prepend('<span style="color:black!important;padding-left: 3%">'+itemName+':</span><br>');
    formContainer.append('<input type="text" class="hidden" name="typeId[]" id="typeId" value="'+typeId+'" required>');
    formContainer.append('<input type="text" class="hidden" name="typeName[]" id="typeName" value="'+typeName+'" required>');
    formContainer.append('<input type="text" class="hidden" name="itemId[]" id="itemId" value="'+itemId+'" required>');
    formContainer.append('<input type="text" class="hidden" name="itemName[]" id="itemName" value="'+itemName+'" required>');
    formContainer.append('<textarea class="hidden" name="form[]" id="form" required>'+formData+'</textarea>');
    formContainer.append('<hr>');
    formContainer.children('.form-group').addClass('col-md-6')
}

$(document).on('change', '.formed input', function(){
    valued = $(this).val();
    name = this.name;
    parent = $(this).parents('.formed');
    textarea = parent.find('textarea');
    form = JSON.parse(textarea.text());
    $.each(form,function(key,value){
        if(name==value.name){
            if(value.type=="radio-group"){
                $.each(value.values,function(subkey,subvalue){
                    if(subvalue.value==valued){
                        form[key].values[subkey].selected = true;
                    }else{
                        delete form[key].values[subkey].selected;
                    }
                });
            }//autocomplete
            else if(value.type=="autocomplete"){
                $.each(value.values,function(subkey,subvalue){
                    if(subvalue.value==valued){
                        form[key].values[subkey].selected = true;
                    }else{
                        delete form[key].values[subkey].selected;
                    }
                });
            }//checkbox
            else if(value.type=="checkbox-group"){
                $.each(value.values,function(subkey,subvalue){
                    if(subvalue.value==valued){
                        form[key].values[subkey].selected = true;
                    }else{
                        delete form[key].values[subkey].selected;
                    }
                });
            }
            else if(value.type=="paragraph"){
                form[key].label = valued;
            }
            else{
                form[key].value = valued;
            }
        }
    });
    textarea.text(JSON.stringify(form));
});

$('#firstName').on('autocompleteselect',function(event, ui){
    name = ui.item.value
    $.ajax({
        type: "GET",
        url: "/inspect/customer/"+name,
        dataType: "JSON",
        success:function(data){
            $('#firstName').val(data.customer.firstName);
            $('#middleName').val(data.customer.middleName);
            $('#lastName').val(data.customer.lastName);
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
    if(name[4]<10){
        $.ajax({
            type: "GET",
            url: "/inspect/vehicle/"+name,
            dataType: "JSON",
            success:function(data){
                $('#plate').val(data.vehicle.plate);
                $('#model').val(data.vehicle.modelId);
                $('#mileage').val(data.vehicle.mileage);
                $('#model').select2();
            }
        });
    }
});