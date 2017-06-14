var pList = $('#productList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
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

$(document).on('change', '#qty', function (){
    qty = $(this).val();
    if(qty=='' || qty==null || qty==0){
        qty = 1;
        $(this).val(1);
    }
    stack = $(this).parents('tr').find('#stack').val().replace(',','');
    price = this.title;
    price = eval(price+"*"+qty);
    final = eval($('#compute').val().replace(',','')+"-"+stack+"+"+price);
    $(this).parents('tr').find('#stack').val(price);
    $('#compute').val(final);
});

$(document).on('change', '#products', function(){
    $('#products option[value="'+this.value+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/purchase/product/"+this.value,
        dataType: "JSON",
        success:function(data){
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" title="'+data.product.price+'" class="form-control qty text-right" id="qty" name="qty[]" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<select id="'+data.product.id+'" name="modelId[]" class="select2 form-control">'+
                '<option value=""></option>' +
                '</select>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+data.product.price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="0" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(3).addClass('text-right');
            $(row).find('td').eq(4).addClass('text-right');
            if(JSON.stringify(data.product.vehicle)=='[]'){
                $('#'+data.product.id).addClass('hidden');
            }else{
                $.each(data.product.vehicle,function(key, value){
                    $('#'+data.product.id).append('<option value="'+value.model.id+'">'+value.model.make.name+' - '+value.model.year+' '+value.model.name+' ('+value.model.transmission+')'+'</option>');
                });
                $('#'+data.product.id).select2();
            }
            $('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: 100,
            });
            $(".price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
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
    stack = $(this).parents('tr').find('#stack').val().replace(',','');
    final = eval($('#compute').val().replace(',','')+"-"+stack);
    $('#compute').val(final);
    var row = rowFinder(this);
    $('#products').val('');
    $("#products").select2();
    pList.row(row).remove().draw();
});

function oldProduct(id,qty,model){
    $('#products option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/purchase/product/"+id,
        dataType: "JSON",
        success:function(data){
            price = data.product.price
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="qty" name="qty[]" value="'+qty+'" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<select id="'+data.product.id+'" name="modelId[]" class="select2 form-control">'+
                '<option value=""></option>' +
                '</select>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(3).addClass('text-right');
            $(row).find('td').eq(4).addClass('text-right');
            if(JSON.stringify(data.product.vehicle)=='[]'){
                $('#'+data.product.id).addClass('hidden');
            }else{
                $.each(data.product.vehicle,function(key, value){
                    if(value.model.id==model){
                        $('#'+data.product.id).append('<option value="'+value.model.id+'" selected>'+value.model.make.name+' - '+value.model.year+' '+value.model.name+' ('+value.model.transmission+')'+'</option>');
                    }else{
                        $('#'+data.product.id).append('<option value="'+value.model.id+'">'+value.model.make.name+' - '+value.model.year+' '+value.model.name+' ('+value.model.transmission+')'+'</option>');
                    }
                });
                $('#'+data.product.id).select2();
            }
            // price
            final =  eval($('#compute').val().replace(',','')+"+"+stack);
            $('#compute').val(final);
            $('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: 100,
            });
            $(".price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
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

function retrieveProduct(price,id,qty,model){
    $('#products option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/purchase/product/"+id,
        dataType: "JSON",
        success:function(data){
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="qty" name="qty[]" value="'+qty+'" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<select id="'+data.product.id+'" name="modelId[]" class="select2 form-control">'+
                '<option value=""></option>' +
                '</select>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(3).addClass('text-right');
            $(row).find('td').eq(4).addClass('text-right');
            if(JSON.stringify(data.product.vehicle)=='[]'){
                $('#'+data.product.id).addClass('hidden');
            }else{
                $.each(data.product.vehicle,function(key, value){
                    if(value.model.id==model){
                        $('#'+data.product.id).append('<option value="'+value.model.id+'" selected>'+value.model.make.name+' - '+value.model.year+' '+value.model.name+' ('+value.model.transmission+')'+'</option>');
                    }else{
                        $('#'+data.product.id).append('<option value="'+value.model.id+'">'+value.model.make.name+' - '+value.model.year+' '+value.model.name+' ('+value.model.transmission+')'+'</option>');
                    }
                });
                $('#'+data.product.id).select2();
            }
            // price
            final = eval($('#compute').val().replace(',','')+"+"+stack);
            $('#compute').val(final);
            $('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: 100,
            });
            $(".price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
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
        url: "/purchase/product/"+id,
        dataType: "JSON",
        success:function(data){
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            row = pList.row.add([
                qty,
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")"
            ]).draw().node();
            $(row).find('td').eq(0).addClass('text-right');
        }
    });
}