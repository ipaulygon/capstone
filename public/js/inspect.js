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
    name = $(this).val().replace('_','');
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
});