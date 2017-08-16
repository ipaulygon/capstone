function popForm(typeId,typeName,itemId,itemName,form){
    if(!$('#panel'+typeId+'').length){
        $('#form-box').append(
            '<div id="panel'+typeId+'" class="panel panel-default">' +
            '<div class="panel-heading" role="tab" id="heading'+typeId+'" data-toggle="collapse" data-parent="#form-box" href="#collapse'+typeId+'" aria-expanded="true" aria-controls="collapse'+typeId+'">' +
            '<h2 class="panel-title" style="font-weight:bold!important;color:black!important">' +
            '<a role="button">'+typeName+'</a></h2>' +
            '</div>' +
            '<div id="collapse'+typeId+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'+typeId+'">' +
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
    formContainer.prepend('<label style="padding-left: 1.2%">'+itemName+':</label><br>');
    formContainer.append('<input type="text" class="hidden" name="typeId[]" id="typeId" value="'+typeId+'" required>');
    formContainer.append('<input type="text" class="hidden" name="typeName[]" id="typeName" value="'+typeName+'" required>');
    formContainer.append('<input type="text" class="hidden" name="itemId[]" id="itemId" value="'+itemId+'" required>');
    formContainer.append('<input type="text" class="hidden" name="itemName[]" id="itemName" value="'+itemName+'" required>');
    formContainer.append('<textarea class="hidden" name="form[]" id="form" required>'+formData+'</textarea>');
    formContainer.append('<hr>');
    formContainer.children('.form-group').addClass('col-md-3')
}

$(document).on('change', '.formed input', function(){
    valued = $(this).val();
    name = this.name;
    parent = $(this).parents('.formed');
    textarea = parent.find('#form');
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

function pdfForm(typeId,typeName,itemId,itemName,form){
    if(!$('#panel'+typeId+'').length){
        $('#form-box').append(
            '<div id="panel'+typeId+'" class="panel panel-primary">' +
            '<div class="panel-heading" role="tab" id="heading'+typeId+'" data-parent="#form-box" href="#collapse'+typeId+'" aria-expanded="true" aria-controls="collapse'+typeId+'">' +
            '<h2 class="panel-title" style="font-weight:bold!important;color:white!important">' +
            '<a role="button">'+typeName+'</a></h2>' +
            '</div>' +
            '<div id="collapse'+typeId+'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading'+typeId+'">' +
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
    formContainer.prepend('<label style="padding-left: 1.2%">'+itemName+':</label><br>');
    formContainer.children('.form-group').addClass('col-md-3')
}