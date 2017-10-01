$("#price").inputmask({ 
    alias: "currency",
    prefix: '',
    allowMinus: false,
    autoGroup: true,
    min: 0,
    max: 1000000,
});
$("#compute").inputmask({ 
    alias: "currency",
    prefix: '',
    allowMinus: false,
    autoGroup: true,
    min: 0,
});

var products = $('#products').DataTable({
    responsive: true,
    "paging": false,
    "retrieve": true,
});
var services = $('#services').DataTable({
    responsive: true,
    "paging": false,
    "retrieve": true,
});
var pList = $('#productList').DataTable({
    responsive: true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
var sList = $('#serviceList').DataTable({
    responsive: true,
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

$(document).on('keyup', '#qty', function (){
    qty = $(this).val();
    if(qty=='' || qty==null || qty<1){
        qty = 1;
        $(this).val(1);
    }else if(qty>100){
        qty = 100;
        $(this).val(100);
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
    }else{
        $(this).popover('hide');
    }
    stack = $(this).nextAll('.stack').first().val();
    price = $(this).attr('data-price');
    price = eval(price+"*"+qty);
    final = eval($('#compute').val().replace(/,/g,'')+"-"+stack+"+"+price);
    $(this).nextAll('.stack').first().val(price);
    $('#compute').val(final);
});

$(document).on('focusout','#qty',function(){
    $(this).popover('hide');
});

$(document).on('click','.pushProduct', function (){
    $.ajax({
        type: "GET",
        url: "/item/product/"+this.id,
        dataType: "JSON",
        success:function(data){
            if(data.product.isOriginal!=null){
                part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
            }else{
                part = '';
            }
            pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'">'+data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<input type="text" data-price="'+data.product.price+'" id="qty" class="form-control qty" name="qty[]" required><input type="hidden" class="hidden stack" value="0">',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            $('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: maxValue
            });
        }
    });
    var row = rowFinder(this);
    products.row(row).remove().draw();
});

$(document).on('click','.pullProduct', function (){
    $.ajax({
        type: "GET",
        url: "/item/product/"+this.id,
        dataType: "JSON",
        success:function(data){
            products.row.add([
                data.product.brand.name+" - "+data.product.name,
                '<li>'+data.product.type.name+'</li><li>'+data.product.variance.name+'</li>',
                '<button id="'+data.product.id+'" type="button" class="btn btn-primary btn-sm pull-right pushProduct" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw();
        }
    });
    // price
    final = eval($('#compute').val().replace(/,/g,'')+"-"+$(this).parents().find('.stack').val());
    $('#compute').val(final);
    var row = rowFinder(this);
    pList.row(row).remove().draw();
});

$(document).on('click','.pushService', function (){
    $.ajax({
        type: "GET",
        url: "/item/service/"+this.id,
        dataType: "JSON",
        success:function(data){
            sList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">'+data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button id="'+data.service.id+'" type="button" class="btn btn-danger btn-sm pull-right pullService" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(/,/g,'')+"+"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder(this);
    services.row(row).remove().draw();
});

$(document).on('click','.pullService', function (){
    $.ajax({
        type: "GET",
        url: "/item/service/"+this.id,
        dataType: "JSON",
        success:function(data){
            services.row.add([
                data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button id="'+data.service.id+'" type="button" class="btn btn-primary btn-sm pull-right pushService" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(/,/g,'')+"-"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder(this);
    sList.row(row).remove().draw();
});

function retrieveProduct(id,qty){
    $.ajax({
        type: "GET",
        url: "/item/product/"+id,
        dataType: "JSON",
        success:function(data){
            var stack = eval(qty+"*"+data.product.price);
            if(data.product.isOriginal!=null){
                part = (data.product.isOriginal == 'type1' ? ' - '+type1 : type2)
            }else{
                part = '';
            }
            pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'">'+data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<input type="text" data-price="'+data.product.price+'" id="qty" class="form-control qty" name="qty[]" required value="'+qty+'"><input type="hidden stack" class="hidden" value="'+stack+'">',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            $('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: maxValue
            });
            // price
            final =  eval($('#compute').val().replace(/,/g,'')+"+"+stack);
            $('#compute').val(final);
        }
    });
    var row = rowFinder($('#products').find('button[id="'+id+'"]'));
    products.row(row).remove().draw();
}

function retrieveService(id){
    $.ajax({
        type: "GET",
        url: "/item/service/"+id,
        dataType: "JSON",
        success:function(data){
            sList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">'+data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button id="'+data.service.id+'" type="button" class="btn btn-danger btn-sm pull-right pullService" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(/,/g,'')+"+"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder($('#services').find('button[id="'+id+'"]'));
    services.row(row).remove().draw();
}