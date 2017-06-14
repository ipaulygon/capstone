var pList = $('#productList').DataTable({
    responsive: true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
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
$("#compute").inputmask({ 
    alias: "currency",
    prefix: '',
    allowMinus: false,
    autoGroup: true,
    min: 0,
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
// AUTOCOMPLETE
$('#firstName').on('autocompleteselect',function(event, ui){
    name = ui.item.value
    $.ajax({
        type: "GET",
        url: "/job/customer/"+name,
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
            url: "/job/vehicle/"+name,
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
$(document).on('keyup', '#qty', function (){
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
        url: "/job/product/"+this.value,
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
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="qty" name="productQty[]" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+") "+discountString,
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="0" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $('.qty').inputmask({ 
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
    pList.row(row).remove().draw();
});

function oldProduct(id,qty){
    $('#products option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/job/product/"+id,
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
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
                min: 0,
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

function retrieveProduct(id,qty,price,discountString){
    $('#products option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/job/product/"+id,
        dataType: "JSON",
        success:function(data){
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="qty" name="productQty[]" value="'+qty+'" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+") "+discountString,
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
                min: 0,
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
// SERVICES
$(document).on('change', '#services', function(){
    $('#services option[value="'+this.value+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/job/service/"+this.value,
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
                '<button id="'+data.service.id+'" type="button" class="btn btn-danger btn-sm pull-right pullService" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
            });
        }
    });
    $('#services').val('');
    $("#services").select2();
});

$(document).on('click','.pullService', function(){
    $('#services option[value="'+this.id+'"]').attr('disabled',false);
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
        url: "/job/service/"+id,
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
                '<button id="'+data.service.id+'" type="button" class="btn btn-danger btn-sm pull-right pullService" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
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
        url: "/job/service/"+id,
        dataType: "JSON",
        success:function(data){
            stack = price;
            row = pList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">',
                data.service.name+" - "+data.service.size+" ("+data.service.category.name+") "+discountString,
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<button id="'+data.service.id+'" type="button" class="btn btn-danger btn-sm pull-right pullService" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
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
        url: "/job/package/"+this.value,
        dataType: "JSON",
        success:function(data){
            row = pList.row.add([
                '<input type="hidden" name="package[]" value="'+data.package.id+'"><input type="text" title="'+data.package.price+'" class="form-control qty text-right" id="qty" name="packageQty[]" required>',
                data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+data.package.price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="0" readonly></strong>',
                '<button id="'+data.package.id+'" type="button" class="btn btn-danger btn-sm pull-right pullPackage" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
            });
        }
    });
    $('#packages').val('');
    $("#packages").select2();
});

$(document).on('click','.pullPackage', function(){
    $('#packages option[value="'+this.id+'"]').attr('disabled',false);
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
        url: "/job/package/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(data.package.price+"*"+qty);
            row = pList.row.add([
                '<input type="hidden" name="package[]" value="'+data.package.id+'"><input type="text" title="'+data.package.price+'" class="form-control qty text-right" id="qty" name="packageQty[]" value="'+qty+'" required>',
                data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+data.package.price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.package.id+'" type="button" class="btn btn-danger btn-sm pull-right pullPackage" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
            });
        }
    });
}

function retrievePackage(id,qty,price){
    $('#packages option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/job/package/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="package[]" value="'+data.package.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="qty" name="packageQty[]" value="'+qty+'" required>',
                data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.package.id+'" type="button" class="btn btn-danger btn-sm pull-right pullPackage" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
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
        url: "/job/promo/"+this.value,
        dataType: "JSON",
        success:function(data){
            row = pList.row.add([
                '<input type="hidden" name="promo[]" value="'+data.promo.id+'"><input type="text" title="'+data.promo.price+'" class="form-control qty text-right" id="qty" name="promoQty[]" required>',
                data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+data.promo.price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="0" readonly></strong>',
                '<button id="'+data.promo.id+'" type="button" class="btn btn-danger btn-sm pull-right pullPromo" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
            });
        }
    });
    $('#promos').val('');
    $("#promos").select2();
});

$(document).on('click','.pullPromo', function(){
    $('#promos option[value="'+this.id+'"]').attr('disabled',false);
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
        url: "/job/promo/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(data.promo.price+"*"+qty);
            row = pList.row.add([
                '<input type="hidden" name="promo[]" value="'+data.promo.id+'"><input type="text" title="'+data.promo.price+'" class="form-control qty text-right" id="qty" name="promoQty[]" value="'+qty+'" required>',
                data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+data.promo.price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.promo.id+'" type="button" class="btn btn-danger btn-sm pull-right pullPromo" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
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
        url: "/job/promo/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="promo[]" value="'+data.promo.id+'"><input type="text" title="'+price+'" class="form-control qty text-right" id="qty" name="promoQty[]" value="'+qty+'" required>',
                data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                '<strong><input class="price" id="price" style="border: none!important;background: transparent!important" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack" id="stack" style="border: none!important;background: transparent!important" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.promo.id+'" type="button" class="btn btn-danger btn-sm pull-right pullPromo" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
            });
            $('.stack').inputmask({ 
                alias: "currency",
                prefix: '',
                allowMinus: false,
                min: 0,
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
        url: "/job/discount/"+this.value,
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
                '<button id="'+data.discount.id+'" type="button" class="btn btn-danger btn-sm pull-right pullDiscount" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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

$(document).on('click','.pullDiscount', function(){
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
    $('#discounts').val(id);
    $("#discounts").select2();
    $('#discounts').prop('disabled',true);
    $.ajax({
        type: "GET",
        url: "/job/discount/"+id,
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
                '<button id="'+data.discount.id+'" type="button" class="btn btn-danger btn-sm pull-right pullDiscount" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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
        url: "/job/discount/"+id,
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
                '<button id="'+data.discount.id+'" type="button" class="btn btn-danger btn-sm pull-right pullDiscount" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
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