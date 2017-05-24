var start = moment();
var end = moment();

function cb(start, end) {
    $('#date input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}

$('#date').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
        'Today': [moment(), moment()],
        'Last for 7 Days': [moment(), moment().add(6, 'days')],
        'Last for 30 Days': [moment(), moment().add(29, 'days')],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
    }
}, cb);

cb(start, end);

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
var frees = $('#frees').DataTable({
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
var fList = $('#freeList').DataTable({
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

$(document).on('change', '#qty', function (){
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

$(document).on('click','#pushFreeProduct', function (){
    $.ajax({
        type: "GET",
        url: "/promo/product/"+this.title,
        dataType: "JSON",
        success:function(data){
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            fList.row.add([
                '<input type="hidden" name="freeProduct[]" value="'+data.product.id+'">'+data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<input type="text" title="'+data.product.price+'" id="qty" class="form-control qty" name="freeQty[]" required><input type="hidden" class="hidden" value="0">',
                '<button title="'+data.product.id+'" type="button" id="pullFreeProduct" class="btn btn-danger btn-sm pull-right"><i class="fa fa-angle-double-left"></i></button>'
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
    frees.row(row).remove().draw();
});

$(document).on('click','#pullFreeProduct', function (){
    $.ajax({
        type: "GET",
        url: "/promo/product/"+this.title,
        dataType: "JSON",
        success:function(data){
            frees.row.add([
                data.product.brand.name+" - "+data.product.name,
                '<li>'+data.product.type.name+'</li><li>'+data.product.variance.name+'</li>',
                '<button title="'+data.product.id+'" type="button" id="pushFreeProduct" class="btn btn-primary btn-sm pull-right"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw();
        }
    });
    // price
    final = eval($('#compute').val().replace(',','')+"-"+$(this).parents().find('.hidden').val());
    $('#compute').val(final);
    var row = rowFinder(this);
    fList.row(row).remove().draw();
});

$(document).on('click','#pushProduct', function (){
    $.ajax({
        type: "GET",
        url: "/promo/product/"+this.title,
        dataType: "JSON",
        success:function(data){
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'">'+data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<input type="text" title="'+data.product.price+'" id="qty" class="form-control qty" name="qty[]" required><input type="hidden" class="hidden" value="0">',
                '<button title="'+data.product.id+'" type="button" id="pullProduct" class="btn btn-danger btn-sm pull-right"><i class="fa fa-angle-double-left"></i></button>'
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

$(document).on('click','#pullProduct', function (){
    $.ajax({
        type: "GET",
        url: "/promo/product/"+this.title,
        dataType: "JSON",
        success:function(data){
            products.row.add([
                data.product.brand.name+" - "+data.product.name,
                '<li>'+data.product.type.name+'</li><li>'+data.product.variance.name+'</li>',
                '<button title="'+data.product.id+'" type="button" id="pushProduct" class="btn btn-primary btn-sm pull-right"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw();
        }
    });
    // price
    final = eval($('#compute').val().replace(',','')+"-"+$(this).parents().find('.hidden').val());
    $('#compute').val(final);
    var row = rowFinder(this);
    pList.row(row).remove().draw();
});

$(document).on('click','#pushFreeService', function (){
    $.ajax({
        type: "GET",
        url: "/promo/service/"+this.title,
        dataType: "JSON",
        success:function(data){
            fList.row.add([
                '<input type="hidden" name="freeService[]" value="'+data.service.id+'">'+data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button title="'+data.service.id+'" type="button" id="pullFreeService" class="btn btn-danger btn-sm pull-right"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(',','')+"+"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder(this);
    frees.row(row).remove().draw();
});

$(document).on('click','#pullFreeService', function (){
    $.ajax({
        type: "GET",
        url: "/promo/service/"+this.title,
        dataType: "JSON",
        success:function(data){
            frees.row.add([
                data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button title="'+data.service.id+'" type="button" id="pushFreeService" class="btn btn-primary btn-sm pull-right"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(',','')+"-"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder(this);
    fList.row(row).remove().draw();
});

$(document).on('click','#pushService', function (){
    $.ajax({
        type: "GET",
        url: "/promo/service/"+this.title,
        dataType: "JSON",
        success:function(data){
            sList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">'+data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button title="'+data.service.id+'" type="button" id="pullService" class="btn btn-danger btn-sm pull-right"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(',','')+"+"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder(this);
    services.row(row).remove().draw();
});

$(document).on('click','#pullService', function (){
    $.ajax({
        type: "GET",
        url: "/promo/service/"+this.title,
        dataType: "JSON",
        success:function(data){
            services.row.add([
                data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button title="'+data.service.id+'" type="button" id="pushService" class="btn btn-primary btn-sm pull-right"><i class="fa fa-angle-double-right"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(',','')+"-"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder(this);
    sList.row(row).remove().draw();
});

function retrieveFreeProduct(id,qty){
    $.ajax({
        type: "GET",
        url: "/promo/product/"+id,
        dataType: "JSON",
        success:function(data){
            if(qty=='' && isNaN(qty) && qty<0){
                qty = 0;
            }
            var stack = eval(qty+"*"+data.product.price);
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            fList.row.add([
                '<input type="hidden" name="freeProduct[]" value="'+data.product.id+'">'+data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<input type="text" title="'+data.product.price+'" id="qty" class="form-control qty" name="freeQty[]" required value="'+qty+'"><input type="hidden" class="hidden" value="'+stack+'">',
                '<button title="'+data.product.id+'" type="button" id="pullFreeProduct" class="btn btn-danger btn-sm pull-right"><i class="fa fa-angle-double-left"></i></button>'
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
    var row = rowFinder($('#frees').find('button#pushFreeProduct[title="'+id+'"]'));
    frees.row(row).remove().draw();
}

function retrieveProduct(id,qty){
    $.ajax({
        type: "GET",
        url: "/promo/product/"+id,
        dataType: "JSON",
        success:function(data){
            if(qty=='' && isNaN(qty) && qty<0){
                qty = 0;
            }
            var stack = eval(qty+"*"+data.product.price);
            part = (data.product.isOriginal!=null ? ' - '+data.product.isOriginal : '')
            pList.row.add([
                '<input type="hidden" name="product[]" value="'+data.product.id+'">'+data.product.brand.name+" - "+data.product.name+part+" ("+data.product.variance.name+")",
                '<input type="text" title="'+data.product.price+'" id="qty" class="form-control qty" name="qty[]" required value="'+qty+'"><input type="hidden" class="hidden" value="'+stack+'">',
                '<button title="'+data.product.id+'" type="button" id="pullProduct" class="btn btn-danger btn-sm pull-right"><i class="fa fa-angle-double-left"></i></button>'
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
    var row = rowFinder($('#products').find('button[title="'+id+'"]'));
    products.row(row).remove().draw();
}

function retrieveFreeService(id){
    $.ajax({
        type: "GET",
        url: "/promo/service/"+id,
        dataType: "JSON",
        success:function(data){
            fList.row.add([
                '<input type="hidden" name="freeService[]" value="'+data.service.id+'">'+data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button title="'+data.service.id+'" type="button" id="pullFreeService" class="btn btn-danger btn-sm pull-right"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(',','')+"+"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder($('#frees').find('button#pushFreeService[title="'+id+'"]'));
    frees.row(row).remove().draw();
}

function retrieveService(id){
    $.ajax({
        type: "GET",
        url: "/promo/service/"+id,
        dataType: "JSON",
        success:function(data){
            sList.row.add([
                '<input type="hidden" name="service[]" value="'+data.service.id+'">'+data.service.name+" - "+data.service.size,
                data.service.category.name,
                '<button title="'+data.service.id+'" type="button" id="pullService" class="btn btn-danger btn-sm pull-right"><i class="fa fa-angle-double-left"></i></button>'
            ]).draw();
            // price
            final = eval($('#compute').val().replace(',','')+"+"+data.service.price);
            $('#compute').val(final);
        }
    });
    var row = rowFinder($('#services').find('button[title="'+id+'"]'));
    services.row(row).remove().draw();
}