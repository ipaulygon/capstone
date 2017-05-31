var pList = $('#productList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
// AUTOCOMPLETE
$('#firstName').on('autocompleteselect',function(event, ui){
    name = ui.item.value
    $.ajax({
        type: "GET",
        url: "/estimate/customer/"+name,
        dataType: "JSON",
        success:function(data){
            $('#firstName').val(data.customer.firstName);
            $('#middleName').val(data.customer.middleName);
            $('#lastName').val(data.customer.lastName);
            $('#contact').val(data.customer.contact);
            $('#email').val(data.customer.email);
            $('#address').text(data.customer.address);
        }
    });
});

$('#plate').on('change',function(){
    name = $(this).val().replace('_','');
    $.ajax({
        type: "GET",
        url: "/estimate/vehicle/"+name,
        dataType: "JSON",
        success:function(data){
            $('#plate').val(data.vehicle.plate);
            $('#model').val(data.vehicle.modelId);
            $('#mileage').val(data.vehicle.mileage);
            $('#model').select2();
        }
    });
});
// DATATABLE
function rowFinder(row){
    if($(row).closest('table').hasClass("collapsed")) {
        var child = $(row).parents("tr.child");
        row = $(child).prevAll(".parent");
    } else {
        row = $(row).parents('tr');
    }
    return row;
}

function discountReplenish(){
    if($('#discountStack').length!=0){
        discounted = eval($('#discountStack').val().replace(',','')+"*"+'-1');
        final = eval($('#compute').val().replace(',','')+"+"+discounted);
        $('#compute').val(final);
    }
}

function discountRecount(){
    if($('#discountStack').length!=0){
        final =  eval($('#compute').val().replace(',','')+"*"+($('#discountPrice').val().replace(' %','')/100));
        discountStack = 0-final;
        $('#discountStack').val(discountStack);
        final = eval($('#compute').val().replace(',','')+"-"+final);
        $('#compute').val(final);
    }
}
// QUANTITY
$(document).on('change', '#qty', function (){
    qty = $(this).val();
    if(qty=='' || qty==null || qty==0){
        qty = 1;
        $(this).val(1);
    }
    stack = $(this).parents('tr').find('#stack').val().replace(',','');
    price = this.title;
    price = eval(price+"*"+qty);
    discountReplenish();
    final = eval($('#compute').val().replace(',','')+"-"+stack+"+"+price);
    $(this).parents('tr').find('#stack').val(price);
    $('#compute').val(final);
    discountRecount();
});
// PRODUCTS
$(document).on('change', '#products', function(){
    $('#products option[value="'+this.value+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/product/"+this.value,
        dataType: "JSON",
        success:function(data){
            var discount = null;
            if(data.product.discount!=null){
                discount = (data.product.discount.header.rate);
            }
            if(discount!=null){
                price = (data.product.price)-(data.product.price*(discount/100));
                discountString = '['+discount+' % discount]';
            }else{
                price = data.product.price;
                discountString = '';
            }
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="prodqty'+data.product.id+'" name="productQty[]" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+") "+discountString,
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="0" readonly></strong>',
                '<button title="'+data.product.id+'" type="button" id="pullProduct" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            console.log(data.product);
            $('#prodqty'+data.product.id).inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 100,
            });
            $(".price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#products').val('');
    $("#products").select2();
});

$(document).on('click','#pullProduct', function(){
    $('#products option[value="'+this.title+'"]').attr('disabled',false);
    stack = $(this).parents('tr').find('#stack').val().replace(',','');
    final = eval($('#compute').val().replace(',','')+"-"+stack);
    $('#compute').val(final);
    var row = rowFinder(this);
    pList.row(row).remove().draw();
});

function oldProduct(id,qty){
    $('#products option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/product/"+id,
        dataType: "JSON",
        success:function(data){
            var discount = null;
            if(data.product.discount!=null){
                discount = (data.product.discount.header.rate);
            }
            if(discount!=null){
                price = (data.product.price)-(data.product.price*(discount/100));
                discountString = '['+discount+' % discount]';
            }else{
                price = data.product.price;
                discountString = '';
            }
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            stack = eval(price+"*"+qty);
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="qty" name="productQty[]" value="'+qty+'" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+") "+discountString,
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button title="'+data.product.id+'" type="button" id="pullProduct" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
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
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#products').val('');
    $("#products").select2();
}

function retrieveProduct(id,qty,price,discountString){
    $('#products option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/product/"+id,
        dataType: "JSON",
        success:function(data){
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="qty" name="productQty[]" value="'+qty+'" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+") "+discountString,
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button title="'+data.product.id+'" type="button" id="pullProduct" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
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
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#products').val('');
    $("#products").select2();
}
// SERVICES
$(document).on('change', '#services', function(){
    $('#services option[value="'+this.value+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/service/"+this.value,
        dataType: "JSON",
        success:function(data){
            var discount = null;
            if(data.service.discount!=null){
                discount = (data.service.discount.header.rate);
            }
            if(discount!=null){
                price = (data.service.price)-(data.service.price*(discount/100));
                discountString = '['+discount+' % discount]';
            }else{
                price = data.service.price;
                discountString = '';
            }
            stack = price;
            row = pList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">',
                data.service.name+" - "+data.service.size+" ("+data.service.category.name+") "+discountString,
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<button title="'+data.service.id+'" type="button" id="pullService" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            // price
            discountReplenish();
            final =  eval($('#compute').val().replace(',','')+"+"+stack);
            $('#compute').val(final);
            discountRecount();
            $(".price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#services').val('');
    $("#services").select2();
});

$(document).on('click','#pullService', function(){
    $('#services option[value="'+this.title+'"]').attr('disabled',false);
    stack = $(this).parents('tr').find('#stack').val().replace(',','');
    final = eval($('#compute').val().replace(',','')+"-"+stack);
    $('#compute').val(final);
    var row = rowFinder(this);
    pList.row(row).remove().draw();
});

function oldService(id){
    $('#services option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/service/"+id,
        dataType: "JSON",
        success:function(data){
            var discount = null;
            if(data.service.discount!=null){
                discount = (data.service.discount.header.rate);
            }
            if(discount!=null){
                price = (data.service.price)-(data.service.price*(discount/100));
                discountString = '['+discount+' % discount]';
            }else{
                price = data.service.price;
                discountString = '';
            }
            stack = price;
            row = pList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">',
                data.service.name+" - "+data.service.size+" ("+data.service.category.name+") "+discountString,
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<button title="'+data.service.id+'" type="button" id="pullService" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            // price
            discountReplenish();
            final =  eval($('#compute').val().replace(',','')+"+"+stack);
            $('#compute').val(final);
            discountRecount();
            $(".price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#services').val('');
    $("#services").select2();
}

function retrieveService(id,price,discountString){
    $('#services option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/service/"+id,
        dataType: "JSON",
        success:function(data){
            stack = price;
            row = pList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">',
                data.service.name+" - "+data.service.size+" ("+data.service.category.name+") "+discountString,
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<button title="'+data.service.id+'" type="button" id="pullService" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            // price
            final =  eval($('#compute').val().replace(',','')+"+"+stack);
            $('#compute').val(final);
            $(".price").inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#services').val('');
    $("#services").select2();
}
// PACKAGES
$(document).on('change', '#packages', function(){
    $('#packages option[value="'+this.value+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/package/"+this.value,
        dataType: "JSON",
        success:function(data){
            row = pList.row.add([
                '<input type="hidden" name="package[]" value="'+data.package.id+'"><input type="text" title="'+data.package.price+'" class="form-control qty text-right" id="qty" name="packageQty[]" required>',
                data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+data.package.price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="0" readonly></strong>',
                '<button title="'+data.package.id+'" type="button" id="pullPackage" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.package.product,function(key,value){
                part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.package.service,function(key,value){
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
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
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#packages').val('');
    $("#packages").select2();
});

$(document).on('click','#pullPackage', function(){
    $('#packages option[value="'+this.title+'"]').attr('disabled',false);
    stack = $(this).parents('tr').find('#stack').val().replace(',','');
    final = eval($('#compute').val().replace(',','')+"-"+stack);
    $('#compute').val(final);
    var row = rowFinder(this);
    pList.row(row).remove().draw();
});

function oldPackage(id,qty){
    $('#packages option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/package/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(data.package.price+"*"+qty);
            row = pList.row.add([
                '<input type="hidden" name="package[]" value="'+data.package.id+'"><input type="text" title="'+data.package.price+'" class="form-control qty text-right" id="qty" name="packageQty[]" value="'+qty+'" required>',
                data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+data.package.price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button title="'+data.package.id+'" type="button" id="pullPackage" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.package.product,function(key,value){
                part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.package.service,function(key,value){
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
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
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
}

function retrievePackage(id,qty,price){
    $('#packages option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/package/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="package[]" value="'+data.package.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="qty" name="packageQty[]" value="'+qty+'" required>',
                data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button title="'+data.package.id+'" type="button" id="pullPackage" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.package.product,function(key,value){
                part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.package.service,function(key,value){
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
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
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#packages').val('');
    $("#packages").select2();
}
// PROMOS
$(document).on('change', '#promos', function(){
    $('#promos option[value="'+this.value+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/promo/"+this.value,
        dataType: "JSON",
        success:function(data){
            row = pList.row.add([
                '<input type="hidden" name="promo[]" value="'+data.promo.id+'"><input type="text" title="'+data.promo.price+'" class="form-control qty text-right" id="qty" name="promoQty[]" required>',
                data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+data.promo.price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="0" readonly></strong>',
                '<button title="'+data.promo.id+'" type="button" id="pullPromo" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.promo.product,function(key,value){
                part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.promo.service,function(key,value){
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
            $('#promoItems'+data.promo.id).append(
                '<label>Free:</label>'
            );
            $.each(data.promo.free_product,function(key,value){
                part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.promo.free_service,function(key,value){
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
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
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#promos').val('');
    $("#promos").select2();
});

$(document).on('click','#pullPromo', function(){
    $('#promos option[value="'+this.title+'"]').attr('disabled',false);
    stack = $(this).parents('tr').find('#stack').val().replace(',','');
    final = eval($('#compute').val().replace(',','')+"-"+stack);
    $('#compute').val(final);
    var row = rowFinder(this);
    pList.row(row).remove().draw();
});

function oldPromo(id,qty){
    $('#promos option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/promo/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(data.promo.price+"*"+qty);
            row = pList.row.add([
                '<input type="hidden" name="promo[]" value="'+data.promo.id+'"><input type="text" title="'+data.promo.price+'" class="form-control qty text-right" id="qty" name="promoQty[]" value="'+qty+'" required>',
                data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+data.promo.price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button title="'+data.promo.id+'" type="button" id="pullPromo" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.promo.product,function(key,value){
                part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.promo.service,function(key,value){
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
            $('#promoItems'+data.promo.id).append(
                '<label>Free:</label>'
            );
            $.each(data.promo.free_product,function(key,value){
                part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.promo.free_service,function(key,value){
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
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
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#promos').val('');
    $("#promos").select2();
}

function retrievePromo(id,qty,price){
    $('#promos option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/promo/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="promo[]" value="'+data.promo.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="qty" name="promoQty[]" value="'+qty+'" required>',
                data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button title="'+data.promo.id+'" type="button" id="pullPromo" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.promo.product,function(key,value){
                part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.promo.service,function(key,value){
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
            $('#promoItems'+data.promo.id).append(
                '<label>Free:</label>'
            );
            $.each(data.promo.free_product,function(key,value){
                part = (value.product.isOriginal!=null ? ' - '+value.product.isOriginal : '')
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.promo.free_service,function(key,value){
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
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
                max: 1000000,
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
                max: 10000000,
            });
        }
    });
    $('#promos').val('');
    $("#promos").select2();
}
// DISCOUNTS
$(document).on('change', '#discounts', function(){
    $('#discounts').prop('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/discount/"+this.value,
        dataType: "JSON",
        success:function(data){
            final =  eval($('#compute').val().replace(',','')+"*"+(data.discount.rate/100));
            discountStack = 0-final;
            final = eval($('#compute').val().replace(',','')+"-"+final);
            $('#compute').val(final);
            row = pList.row.add([
                '<input type="hidden" name="discount[]" value="'+data.discount.id+'">',
                data.discount.name+" - DISCOUNT",
                '<strong><input class="discountPrice" id="discountPrice" style="border: none!important;background: transparent!important" type="text" value="'+data.discount.rate+'" readonly></strong>',
                '<strong><input class="discountStack" id="discountStack" style="border: none!important;background: transparent!important" type="text" value="'+discountStack+'" readonly></strong>',
                '<button title="'+data.discount.id+'" type="button" id="pullDiscount" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $("#discountPrice").inputmask({ 
                alias: "percentage",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 100,
            });
            $('#discountStack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: true,
                min: -10000000,
                max: 10000000,
            });
        }
    });
});

$(document).on('click','#pullDiscount', function(){
    $('#discounts').prop('disabled',false);
    $('#discounts').val('');
    $("#discounts").select2();
    discounted = eval($('#discountStack').val().replace(',','')+"*"+'-1');
    final = eval($('#compute').val().replace(',','')+"+"+discounted);
    $('#compute').val(final);
    var row = rowFinder(this);
    pList.row(row).remove().draw();
});

function oldDiscount(id){
    $('#discounts').prop('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/discount/"+id,
        dataType: "JSON",
        success:function(data){
            final =  eval($('#compute').val().replace(',','')+"*"+(data.discount.rate/100));
            discountStack = 0-final;
            final = eval($('#compute').val().replace(',','')+"-"+final);
            $('#compute').val(final);
            row = pList.row.add([
                '<input type="hidden" name="discount[]" value="'+data.discount.id+'">',
                data.discount.name+" - DISCOUNT",
                '<strong><input class="discountPrice" id="discountPrice" style="border: none!important;background: transparent!important" type="text" value="'+data.discount.rate+'" readonly></strong>',
                '<strong><input class="discountStack" id="discountStack" style="border: none!important;background: transparent!important" type="text" value="'+discountStack+'" readonly></strong>',
                '<button title="'+data.discount.id+'" type="button" id="pullDiscount" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $("#discountPrice").inputmask({ 
                alias: "percentage",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 100,
            });
            $('#discountStack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: true,
                min: -10000000,
                max: 10000000,
            });
        }
    });
}

function retrieveDiscount(id,rate){
    $('#discounts').val(id);
    $("#discounts").select2();
    $('#discounts').prop('disabled',true);
    $.ajax({
        type: "GET",
        url: "/estimate/discount/"+id,
        dataType: "JSON",
        success:function(data){
            final =  eval($('#compute').val().replace(',','')+"*"+(rate/100));
            discountStack = 0-final;
            final = eval($('#compute').val().replace(',','')+"-"+final);
            $('#compute').val(final);
            row = pList.row.add([
                '<input type="hidden" name="discount[]" value="'+data.discount.id+'">',
                data.discount.name+" - DISCOUNT",
                '<strong><input class="discountPrice" id="discountPrice" style="border: none!important;background: transparent!important" type="text" value="'+rate+'" readonly></strong>',
                '<strong><input class="discountStack" id="discountStack" style="border: none!important;background: transparent!important" type="text" value="'+discountStack+'" readonly></strong>',
                '<button title="'+data.discount.id+'" type="button" id="pullDiscount" class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $("#discountPrice").inputmask({ 
                alias: "percentage",
                prefix: '',
                allowMinus: false,
                autoGroup: true,
                min: 0,
                max: 100,
            });
            $('#discountStack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: true,
                min: -10000000,
                max: 10000000,
            });
        }
    });
}