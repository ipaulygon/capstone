$("#compute").inputmask({ 
    alias: "currency",
    prefix: '',
    allowMinus: false,
    autoGroup: true,
    min: 0,
});
var pList = $('#productList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
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
        discounted = eval($('#discountStack').val().replace(/,/g,'')+"*"+'-1');
        final = eval($('#compute').val().replace(/,/g,'')+"+"+discounted);
        $('#compute').val(final);
    }
}

function discountRecount(){
    if($('#discountStack').length!=0){
        final =  eval($('#compute').val().replace(/,/g,'')+"*"+($('#discountPrice').val().replace(' %','')/100));
        discountStack = 0-final;
        $('#discountStack').val(discountStack);
        final = eval($('#compute').val().replace(/,/g,'')+"-"+final);
        $('#compute').val(final);
    }
}

function vatReplenish(){
    if(isVat){
        final = eval($('#vatSales').val().replace(/,/g,'')+"+"+$('#vatStack').val().replace(/,/g,''));
        $('#compute').val(final);
    }
}

function vatRecount(){
    if(isVat){
        vat = 100 / (100+vatRate);
        final = eval($('#compute').val().replace(/,/g,'')+"*"+vat);
        $('#vatSales').val(final);
        $('#vatStack').val(eval($('#compute').val().replace(/,/g,'')+"-"+final));
        if(Number($('#discountExempt').val())==1){
            $('#vatExempt').val(-Number($('#vatStack').val().replace(/,/g,'')));
            $('#compute').val($('#vatSales').val().replace(/,/g,''));
        }else{
            $('#vatExempt').val(0);
        }
    }
}

function pullItem(item){
    discountReplenish();
    vatReplenish();
    stack = $(item).parents('tr').find('#stack').val().replace(/,/g,'');
    final = eval($('#compute').val().replace(/,/g,'')+"-"+stack);
    $('#compute').val(final);
    vatRecount();
    discountRecount();
    var row = rowFinder(item);
    pList.row(row).remove().draw();
}

function recount(stack){
    discountReplenish();
    vatReplenish();
    final =  eval($('#compute').val().replace(/,/g,'')+"+"+stack);
    $('#compute').val(final);
    vatRecount();
    discountRecount();
}

// QUANTITY
$(document).on('keyup', '#qty', function (){
    qty = $(this).val();
    if(qty=='' || qty==null || qty<1){
        $(this).val(1);
        qty = $(this).val();
    }else if(qty>maxValue){
        qty = maxValue;
        $(this).val(maxValue);
        $(this).popover({
            trigger: 'manual',
            content: function(){
                var content = "Oops! Your input exceeds the max number of items. The max value will be set.";
                return content;
            },
            placement: function(){
                var placement = 'top';
                return placement;
            },
            template: '<div class="popover alert-danger" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>',
        });
        $(this).popover('show');
        pop = $(this);
        setTimeout(function(){
            pop.popover('hide');
        },2000);
    }
    stack = $(this).parents('tr').find('#stack').val().replace(/,/g,'');
    price = $(this).attr('data-price');
    price = eval(price+"*"+qty);
    discountReplenish();
    vatReplenish();
    final = eval($('#compute').val().replace(/,/g,'')+"-"+stack+"+"+price);
    $(this).parents('tr').find('#stack').val(price);
    $('#compute').val(final);
    vatRecount();
    discountRecount();
});

$(document).on('focusout','#qty',function(){
    $(this).popover('hide');
});
// PRODUCTS
$(document).on('change', '#products', function(){
    $('#products option[value="'+this.value+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/product/"+this.value,
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
            if(data.product.isOriginal!=null){
                part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
            }else{
                part = '';
            }
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" data-price="'+price+'" class="form-control qty text-right" id="qty" name="productQty[]" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+") "+discountString,
                '<strong><input class="price no-border-input" id="price" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="0" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $(row).find('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: maxValue,
            });
            masking();
        }
    });
    $('#products').val('');
    $("#products").select2();
});

$(document).on('click','.pullProduct', function(){
    $('#products option[value="'+this.id+'"]').attr('disabled',false);
    pullItem(this);
});

function oldProduct(id,qty){
    $('#products option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/product/"+id,
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
            if(data.product.isOriginal!=null){
                part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
            }else{
                part = '';
            }
            stack = eval(price+"*"+qty);
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" data-price="'+price+'" class="form-control qty text-right" id="qty" name="productQty[]" value="'+qty+'" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+") "+discountString,
                '<strong><input class="price no-border-input" id="price" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            recount(stack);
            $(row).find('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: maxValue,
            });
            masking();
        }
    });
    $('#products').val('');
    $("#products").select2();
}

function retrieveProduct(id,qty,price,completed,discountString){
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
            hidden = (completed>0 ? 'hidden' : '');
            stack = eval(price+'*'+qty);
            row = pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'"><input type="text" data-price="'+price+'" class="form-control qty text-right" id="qty" name="productQty[]" value="'+qty+'" required>',
                data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+") "+discountString,
                '<strong><input class="price no-border-input" id="price" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.product.id+'" type="button" class="'+hidden+' btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            recount(stack);
            masking();
            minValue = (completed==0 ? 1 : completed);
            $(row).find('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: minValue,
                max: maxValue,
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
        url: "/item/service/"+this.value,
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
                '<strong><input class="price no-border-input" id="price" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+price+'" readonly></strong>',
                '<button id="'+data.service.id+'" type="button" class="btn btn-danger btn-sm pull-right pullService" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            recount(stack);
            masking();
        }
    });
    $('#services').val('');
    $("#services").select2();
});

$(document).on('click','.pullService', function(){
    $('#services option[value="'+this.id+'"]').attr('disabled',false);
    pullItem(this);
});

function oldService(id){
    $('#services option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/service/"+id,
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
                '<strong><input class="price no-border-input" id="price" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+price+'" readonly></strong>',
                '<button id="'+data.service.id+'" type="button" class="btn btn-danger btn-sm pull-right pullService" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            recount(stack);
            masking();
        }
    });
    $('#services').val('');
    $("#services").select2();
}

function retrieveService(id,price,completed,discountString){
    $('#services option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/service/"+id,
        dataType: "JSON",
        success:function(data){
            hidden = (completed>0 ? 'hidden' : '');
            stack = price;
            row = pList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">',
                data.service.name+" - "+data.service.size+" ("+data.service.category.name+") "+discountString,
                '<strong><input class="price no-border-input" id="price" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+price+'" readonly></strong>',
                '<button id="'+data.service.id+'" type="button" class="'+hidden+' btn btn-danger btn-sm pull-right pullService" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            recount(stack);
            masking();
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
        url: "/item/package/"+this.value,
        dataType: "JSON",
        success:function(data){
            row = pList.row.add([
                '<input type="hidden" name="package[]" value="'+data.package.id+'"><input type="text" data-price="'+data.package.price+'" class="form-control qty text-right" id="qty" name="packageQty[]" required>',
                data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                '<strong><input class="price no-border-input" id="price" type="text" value="'+data.package.price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="0" readonly></strong>',
                '<button id="'+data.package.id+'" type="button" class="btn btn-danger btn-sm pull-right pullPackage" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.package.product,function(key,value){
                if(value.product.isOriginal!=null){
                    part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                }else{
                    part = '';
                }  
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.package.service,function(key,value){
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
            $(row).find('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: maxValue,
            });
            masking();
        }
    });
    $('#packages').val('');
    $("#packages").select2();
});

$(document).on('click','.pullPackage', function(){
    $('#packages option[value="'+this.id+'"]').attr('disabled',false);
    pullItem(this);
});

function oldPackage(id,qty){
    $('#packages option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/package/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(data.package.price+"*"+qty);
            row = pList.row.add([
                '<input type="hidden" name="package[]" value="'+data.package.id+'"><input type="text" data-price="'+data.package.price+'" class="form-control qty text-right" id="qty" name="packageQty[]" value="'+qty+'" required>',
                data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                '<strong><input class="price no-border-input" id="price" type="text" value="'+data.package.price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.package.id+'" type="button" class="btn btn-danger btn-sm pull-right pullPackage" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.package.product,function(key,value){
                if(value.product.isOriginal!=null){
                    part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                }else{
                    part = '';
                }  
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.package.service,function(key,value){
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
            recount(stack);
            $(row).find('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: maxValue,
            });
            masking();
        }
    });
}

function retrievePackage(id,qty,price,completed){
    $('#packages option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/package/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(price+'*'+qty);
            hidden = (completed>0 ? 'hidden' : '');
            row = pList.row.add([
                '<input type="hidden" name="package[]" value="'+data.package.id+'"><input type="text" data-price="'+price+'" class="form-control qty text-right" id="qty" name="packageQty[]" value="'+qty+'" required>',
                data.package.name+'<br><div id="packageItems'+data.package.id+'"></div>',
                '<strong><input class="price no-border-input" id="price" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.package.id+'" type="button" class="'+hidden+' btn btn-danger btn-sm pull-right pullPackage" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.package.product,function(key,value){
                if(value.product.isOriginal!=null){
                    part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                }else{
                    part = '';
                }  
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.quantity+' pcs. </li>'
                );
            });
            $.each(data.package.service,function(key,value){
                $('#packageItems'+data.package.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
            recount(stack);
            minValue = (completed==0 ? 1 : completed);
            $(row).find('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: minValue,
                max: maxValue,
            });
            masking();
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
        url: "/item/promo/"+this.value,
        dataType: "JSON",
        success:function(data){
            row = pList.row.add([
                '<input type="hidden" name="promo[]" value="'+data.promo.id+'"><input type="text" data-price="'+data.promo.price+'" class="form-control qty text-right" id="qty" name="promoQty[]" required>',
                data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                '<strong><input class="price no-border-input" id="price" type="text" value="'+data.promo.price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="0" readonly></strong>',
                '<button id="'+data.promo.id+'" type="button" class="btn btn-danger btn-sm pull-right pullPromo" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.promo.product,function(key,value){
                if(value.product.isOriginal!=null){
                    part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                }else{
                    part = '';
                }  
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
                if(value.product.isOriginal!=null){
                    part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                }else{
                    part = '';
                }  
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.freeQuantity+' pcs. </li>'
                );
            });
            $.each(data.promo.free_service,function(key,value){
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
            $(row).find('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: data.promo.stock,
            });
            masking();
        }
    });
    $('#promos').val('');
    $("#promos").select2();
});

$(document).on('click','.pullPromo', function(){
    $('#promos option[value="'+this.id+'"]').attr('disabled',false);
    pullItem(this);
});

function oldPromo(id,qty){
    $('#promos option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/promo/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(data.promo.price+"*"+qty);
            row = pList.row.add([
                '<input type="hidden" name="promo[]" value="'+data.promo.id+'"><input type="text" data-price="'+data.promo.price+'" class="form-control qty text-right" id="qty" name="promoQty[]" value="'+qty+'" required>',
                data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                '<strong><input class="price no-border-input" id="price" type="text" value="'+data.promo.price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.promo.id+'" type="button" class="btn btn-danger btn-sm pull-right pullPromo" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.promo.product,function(key,value){
                if(value.product.isOriginal!=null){
                    part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                }else{
                    part = '';
                }  
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
                if(value.product.isOriginal!=null){
                    part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                }else{
                    part = '';
                }  
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.freeQuantity+' pcs. </li>'
                );
            });
            $.each(data.promo.free_service,function(key,value){
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
            recount(stack);
            $(row).find('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: data.promo.stock,
            });
            masking();
        }
    });
    $('#promos').val('');
    $("#promos").select2();
}

function retrievePromo(id,qty,price,completed){
    $('#promos option[value="'+id+'"]').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/promo/"+id,
        dataType: "JSON",
        success:function(data){
            stack = eval(price+'*'+qty);
            hidden = (completed>0 ? 'hidden' : '');
            row = pList.row.add([
                '<input type="hidden" name="promo[]" value="'+data.promo.id+'"><input type="text" data-price="'+price+'" class="form-control qty text-right" id="qty" name="promoQty[]" value="'+qty+'" required>',
                data.promo.name+'<br><div id="promoItems'+data.promo.id+'"></div>',
                '<strong><input class="price no-border-input" id="price" type="text" value="'+price+'" readonly></strong>',
                '<strong><input class="stack no-border-input" id="stack" type="text" value="'+stack+'" readonly></strong>',
                '<button id="'+data.promo.id+'" type="button" class="'+hidden+' btn btn-danger btn-sm pull-right pullPromo" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button>'
            ]).draw().node();
            $(row).find('td').eq(2).addClass('text-right');
            $(row).find('td').eq(3).addClass('text-right');
            $.each(data.promo.product,function(key,value){
                if(value.product.isOriginal!=null){
                    part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                }else{
                    part = '';
                }  
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
                if(value.product.isOriginal!=null){
                    part = (value.product.isOriginal == 'type1' ? ' - '+type1 : type2)
                }else{
                    part = '';
                }  
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.product.brand.name+" - "+value.product.name+part+" ("+value.product.variance.name+") x "+value.freeQuantity+' pcs. </li>'
                );
            });
            $.each(data.promo.free_service,function(key,value){
                $('#promoItems'+data.promo.id).append(
                    '<li>'+value.service.name+" - "+value.service.size+" ("+value.service.category.name+')</li>'
                );
            });
            recount(stack);
            minValue = (completed==0 ? 1 : completed);
            $(row).find('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: minValue,
                max: data.promo.stock,
            });
            masking();
        }
    });
    $('#promos').val('');
    $("#promos").select2();
}

function masking(){
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


// DISCOUNTS
$(document).on('change', '#discounts', function(){
    $('#discounts').prop('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/discount/"+this.value,
        dataType: "JSON",
        success:function(data){
            vatExempt(data.discount.isVatExempt);
            final =  eval($('#compute').val().replace(/,/g,'')+"*"+(data.discount.rate/100));
            discountStack = 0-final;
            final = eval($('#compute').val().replace(/,/g,'')+"-"+final);
            $('#compute').val(final);
            $('#tFoot').append(
                '<tr id="discountRow">' +
                '<th><input type="hidden" id="discountExempt" value="'+data.discount.isVatExempt+'"><input type="hidden" name="discount[]" value="'+data.discount.id+'"></th>' +
                '<th>'+data.discount.name+" - DISCOUNT</th>" +
                '<th class="text-right"><strong><input class="discountPrice no-border-input" id="discountPrice" type="text" value="'+data.discount.rate+'" readonly></strong></th>' +
                '<th class="text-right"><strong><input class="discountStack no-border-input" id="discountStack" type="text" value="'+discountStack+'" readonly></strong></th>' +
                '<th><button id="'+data.discount.id+'" type="button" class="btn btn-danger btn-sm pull-right pullDiscount" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button></th>' +
                '</tr>'
            );
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
    discounted = eval($('#discountStack').val().replace(/,/g,'')+"*"+'-1');
    final = eval($('#compute').val().replace(/,/g,'')+"+"+discounted);
    $('#compute').val(final);
    $('#discountRow').remove();
    vatReplenish();
    vatRecount();
});

function oldDiscount(id){
    $('#discounts').val(id);
    $("#discounts").select2();
    $('#discounts').prop('disabled',true);
    $.ajax({
        type: "GET",
        url: "/item/discount/"+id,
        dataType: "JSON",
        success:function(data){
            vatExempt(data.discount.isVatExempt);
            final =  eval($('#compute').val().replace(/,/g,'')+"*"+(data.discount.rate/100));
            discountStack = 0-final;
            final = eval($('#compute').val().replace(/,/g,'')+"-"+final);
            $('#compute').val(final);
            $('#tFoot').append(
                '<tr id="discountRow">' +
                '<th><input type="hidden" name="discount[]" value="'+data.discount.id+'"></th>' +
                '<th>'+data.discount.name+" - DISCOUNT</th>" +
                '<th class="text-right"><strong><input class="discountPrice no-border-input" id="discountPrice" type="text" value="'+data.discount.rate+'" readonly></strong></th>' +
                '<th class="text-right"><strong><input class="discountStack no-border-input" id="discountStack" type="text" value="'+discountStack+'" readonly></strong></th>' +
                '<th><button id="'+data.discount.id+'" type="button" class="btn btn-danger btn-sm pull-right pullDiscount" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button></th>' +
                '</tr>'
            );
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
        url: "/item/discount/"+id,
        dataType: "JSON",
        success:function(data){
            vatExempt(data.discount.isVatExempt);
            final =  eval($('#compute').val().replace(/,/g,'')+"*"+(rate/100));
            discountStack = 0-final;
            final = eval($('#compute').val().replace(/,/g,'')+"-"+final);
            $('#compute').val(final);
            $('#tFoot').append(
                '<tr id="discountRow">' +
                '<th><input type="hidden" name="discount[]" value="'+data.discount.id+'"></th>' +
                '<th>'+data.discount.name+" - DISCOUNT</th>" +
                '<th class="text-right"><strong><input class="discountPrice no-border-input" id="discountPrice" type="text" value="'+rate+'" readonly></strong></th>' +
                '<th class="text-right"><strong><input class="discountStack no-border-input" id="discountStack" type="text" value="'+discountStack+'" readonly></strong></th>' +
                '<th><button id="'+data.discount.id+'" type="button" class="btn btn-danger btn-sm pull-right pullDiscount" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-remove"></i></button></th>' +
                '</tr>'
            );
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

function vatExempt(isVatExempt){
    if(isVat){
        if(isVatExempt==1){
            $('#vatExempt').val(-Number($('#vatStack').val().replace(/,/g,'')));
            $('#compute').val($('#vatSales').val().replace(/,/g,''));
        }
    }
}