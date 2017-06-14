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
    if(qty=='' || qty==null){
        qty = 1;
        $(this).val(1);
    }
    stack = $(this).next('.hidden').val();
    price = this.title;
    price = eval(price+"*"+qty);
    final = eval($('#compute').val().replace(',','')+"-"+stack+"+"+price);
    $(this).next('.hidden').val(price);
    $('#compute').val(final);
});

$(document).on('click','.pushProduct', function (){
    $.ajax({
        type: "GET",
        url: "/package/product/"+this.id,
        dataType: "JSON",
        success:function(data){
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'">'+data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<input type="text" title="'+data.product.price+'" id="qty" class="form-control qty" name="qty[]" required><input type="hidden" class="hidden" value="0">',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            $('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: 100,
            });
        }
    });
    var row = rowFinder(this);
    products.row(row).remove().draw();
});

$(document).on('click','.pullProduct', function (){
    $.ajax({
        type: "GET",
        url: "/package/product/"+this.id,
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
    console.log($(this).parents().find('.hidden').val());
    final = eval($('#compute').val().replace(',','')+"-"+$(this).parents().find('.hidden').val());
    $('#compute').val(final);
    var row = rowFinder(this);
    pList.row(row).remove().draw();
});

$(document).on('click','.pushService', function (){
    $.ajax({
        type: "GET",
        url: "/package/service/"+this.id,
        dataType: "JSON",
        success:function(data){
            sList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">'+data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button id="'+data.service.id+'" type="button" class="btn btn-danger btn-sm pull-right pullService" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(',','')+"+"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder(this);
    services.row(row).remove().draw();
});

$(document).on('click','.pullService', function (){
    $.ajax({
        type: "GET",
        url: "/package/service/"+this.id,
        dataType: "JSON",
        success:function(data){
            services.row.add([
                data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button id="'+data.service.id+'" type="button" class="btn btn-primary btn-sm pull-right pushService" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(',','')+"-"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder(this);
    sList.row(row).remove().draw();
});

function retrieveProduct(id,qty){
    $.ajax({
        type: "GET",
        url: "/package/product/"+id,
        dataType: "JSON",
        success:function(data){
            var stack = eval(qty+"*"+data.product.price);
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'">'+data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<input type="text" title="'+data.product.price+'" id="qty" class="form-control qty" name="qty[]" required value="'+qty+'"><input type="hidden" class="hidden" value="'+stack+'">',
                '<button id="'+data.product.id+'" type="button" class="btn btn-danger btn-sm pull-right pullProduct" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            $('.qty').inputmask({ 
                alias: "integer",
                prefix: '',
                allowMinus: false,
                min: 1,
                max: 100,
            });
            // price
            final =  eval($('#compute').val().replace(',','')+"+"+stack);
            $('#compute').val(final);
        }
    });
    var row = rowFinder($('#products').find('button[id="'+id+'"]'));
    products.row(row).remove().draw();
}

function retrieveService(id){
    $.ajax({
        type: "GET",
        url: "/package/service/"+id,
        dataType: "JSON",
        success:function(data){
            sList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">'+data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button id="'+data.service.id+'" type="button" class="btn btn-danger btn-sm pull-right pullService" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(',','')+"+"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder($('#services').find('button[id="'+id+'"]'));
    services.row(row).remove().draw();
}