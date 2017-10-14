var pList = $('#productList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
$('#date').datepicker({
    format: 'mm/dd/yyyy',
    endDate: new Date,
    startDate: '-'+backlog+'d',
    autoclose: true,
    todayHighlight: true,
});
$('#date').inputmask("99/99/9999");
$('#date').on('show', function(e){
    console.debug('show', e.date, $(this).data('stickyDate'));
    if ( e.date ) {
         $(this).data('stickyDate', e.date);
    }
    else {
         $(this).data('stickyDate', null);
    }
});
$('#date').on('hide', function(e){
    console.debug('hide', e.date, $(this).data('stickyDate'));
    var stickyDate = $(this).data('stickyDate');
    if ( !e.date && stickyDate ) {
        console.debug('restore stickyDate', stickyDate);
        $(this).datepicker('setDate', stickyDate);
        $(this).data('stickyDate', null);
    }
});
$("#compute").inputmask({ 
    alias: "currency",
    prefix: '',
    allowMinus: false,
    autoGroup: true,
    min: 0,
});

function rowFinder(row){
    if($(row).closest('table').hasClass("collapsed")) {
        var child = $(row).parents("tr.child");
        row = $(child).prevAll(".parent");
    } else {
        row = $(row).parents('tr');
    }
    return row;
}

$(document).on('keyup', '#qty', function (){
    qty = $(this).val();
    if(qty=='' || qty==null || qty==0){
        $(this).val(1);
        qty = $(this).val();
    }
    // }else if(qty>maxValue){
    //     qty = maxValue;
    //     $(this).val(maxValue);
    //     $(this).popover({
    //         trigger: 'manual',
    //         content: function(){
    //             var content = "Oops! Your input exceeds the max number of items. The max value will be set.";
    //             return content;
    //         },
    //         placement: function(){
    //             var placement = 'top';
    //             return placement;
    //         },
    //         template: '<div class="popover alert-danger" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
    //     });
    //     $(this).popover('show');
    //     pop = $(this);
    //     setTimeout(function(){
    //         pop.popover('hide');
    //     },2000);
    // }
    stack = $(this).parents('tr').find('#stack').val().replace(/,/g,'');
    price = $(this).attr('data-price').replace(/,/g,'');
    price = eval(price+"*"+qty);
    final = eval($('#compute').val().replace(/,/g,'')+"-"+stack+"+"+price);
    $(this).parents('tr').find('#stack').val(price);
    $('#compute').val(final);
});

$(document).on('keyup', '.price', function(){
    if(Number($(this).val().replace(/,/g,''))>=1000000){
        $(this).val(1000000);
    }
    inputQty = rowFinder(this).find('#qty').attr('data-price',$(this).val());
    if(inputQty.val()!='' || inputQty.val()!=null){
        stack = $(inputQty).parents('tr').find('#stack').val().replace(/,/g,'');
        price = $(inputQty).attr('data-price').replace(/,/g,'');
        price = eval(price+"*"+qty);
        final = eval($('#compute').val().replace(/,/g,'')+"-"+stack+"+"+price);
        $(this).parents('tr').find('#stack').val(price);
        $('#compute').val(final);
    }
});

$(document).on('change', '#products', function(){
    $('#products option[value="'+this.value+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/product/"+this.value,
        dataType: "JSON",
        success:function(data){
            if(data.product.isOriginal!=null){
                part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
            }else{
                part = '';
            }
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" data-price="0" class="form-control qty text-right" id="qty" name="qty[]" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<select id="'+data.product.id+'" name="modelId[]" class="select2 form-control">'+
                '<option value=""></option>' +
                '</select>',
                '<strong><input class="price form-control text-right" name="price[]" id="price" type="text"></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="0" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(3).addClass('text-right');
            $(row).find('td').eq(4).addClass('text-right');
            if(JSON.stringify(data.product.vehicle)=='[]'){
                $('#'+data.product.id).addClass('hidden');
            }else{
                $.each(data.product.vehicle,function(key, value){
                    tr = (value.isManual ? 'MT' : 'AT');
                    $('#'+data.product.id).append('<option value="'+value.model.id+','+value.isManual+'">'+value.model.make.name+' - '+value.model.name+' ('+tr+')'+'</option>');
                });
                $('#'+data.product.id).select2();
            }
            $('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
            });
            $(".price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 1000000
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
            });
        }
    });
    $('#products').val('');
    $("#products").select2();
});

$(document).on('click','.pullProduct', function(){
    $('#products option[value="'+this.id+'"]').attr('disabled',false);
    stack = $(this).parents('tr').find('#stack').val().replace(/,/g,'');
    final = eval($('#compute').val().replace(/,/g,'')+"-"+stack);
    $('#compute').val(final);
    var row = rowFinder(this);
    $('#products').val('');
    $("#products").select2();
    pList.row(row).remove().draw();
});

function oldProduct(id,qty,model,price){
    $('#products option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/product/"+id,
        dataType: "JSON",
        success:function(data){
            if(data.product.isOriginal!=null){
                part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
            }else{
                part = '';
            }
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" data-price="'+price+'" class="form-control qty text-right" id="qty" name="qty[]" value="'+qty+'" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<select id="'+data.product.id+'" name="modelId[]" width="100%" class="select2 form-control">'+
                '<option value=""></option>' +
                '</select>',
                '<strong><input class="price form-control text-right" name="price[]" id="price" type="text" value="'+price+'"></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(3).addClass('text-right');
            $(row).find('td').eq(4).addClass('text-right');
            if(JSON.stringify(data.product.vehicle)=='[]'){
                $('#'+data.product.id).addClass('hidden');
            }else{
                $.each(data.product.vehicle,function(key, value){
                    tr = (value.isManual ? 'MT' : 'AT');
                    if((value.model.id+','+value.isManual)==model){
                        $('#'+data.product.id).append('<option value="'+value.model.id+','+value.isManual+'" selected>'+value.model.make.name+' - '+value.model.name+' ('+tr+')'+'</option>');
                    }else{
                        $('#'+data.product.id).append('<option value="'+value.model.id+','+value.isManual+'">'+value.model.make.name+' - '+value.model.name+' ('+tr+')'+'</option>');
                    }
                });
                $('#'+data.product.id).select2();
            }
            // price
            final =  eval($('#compute').val().replace(/,/g,'')+"+"+stack);
            $('#compute').val(final);
            $('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
            });
            $(".price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 1000000
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
            });
        }
    });
    $('#products').val('');
    $("#products").select2();
}

function retrieveProduct(price,id,qty,delivered,model,manual){
    $('#products option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/product/"+id,
        dataType: "JSON",
        success:function(data){
            if(data.product.isOriginal!=null){
                part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
            }else{
                part = '';
            }
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" data-price="'+price+'" class="form-control qty text-right" id="qty" name="qty[]" value="'+qty+'" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<select id="'+data.product.id+'" name="modelId[]" width="100%" class="select2 form-control">'+
                '<option value=""></option>' +
                '</select>',
                '<strong><input class="price form-control text-right" name="price[]" id="price" type="text" value="'+price+'"></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(3).addClass('text-right');
            $(row).find('td').eq(4).addClass('text-right');
            if(JSON.stringify(data.product.vehicle)=='[]'){
                $('#'+data.product.id).addClass('hidden');
            }else{
                $.each(data.product.vehicle,function(key, value){
                    tr = (value.isManual ? 'MT' : 'AT');
                    if((value.model.id+','+value.isManual)==(model+','+manual)){
                        $('#'+data.product.id).append('<option value="'+value.model.id+','+value.isManual+'" selected>'+value.model.make.name+' - '+value.model.name+' ('+tr+')'+'</option>');
                    }else{
                        $('#'+data.product.id).append('<option value="'+value.model.id+','+value.isManual+'">'+value.model.make.name+' - '+value.model.name+' ('+tr+')'+'</option>');
                    }
                });
                $('#'+data.product.id).select2();
            }
            // price
            final = eval($('#compute').val().replace(/,/g,'')+"+"+stack);
            $('#compute').val(final);
            $(row).find('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: delivered,
            });
            $(".price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 1000000
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
            });
        }
    });
    $('#products').val('');
    $("#products").select2();
}

function detailProduct(id,qty){
    $.ajax({
        type: "GET",
        url: "/item/product/"+id,
        dataType: "JSON",
        success:function(data){
            if(data.product.isOriginal!=null){
                part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
            }else{
                part = '';
            }
            row = pList.row.add([
                qty,
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")"
            ]).draw().node();
            $(row).find('td').eq(0).addClass('text-right');
        }
    });
}