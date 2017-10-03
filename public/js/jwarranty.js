var jList = $('#jobList').DataTable({
    'responsive': true,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

var jProduct = $('#jobProduct').DataTable({
    'responsive': true,
    "ordering" : false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

var jService = $('#jobService').DataTable({
    'responsive': true,
    "ordering" : false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});

var jPackage = $('#jobPackage').DataTable({
    'responsive': true,
    "ordering" : false,
    "searching": false,
    "paging": false,
    "info": false,
    "retrieve": true,
});
var jPromo = $('#jobPromo').DataTable({
    'responsive': true,
    "ordering" : false,
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
