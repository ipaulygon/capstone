var start = moment(businessDate);
var end = moment();
function cb(start, end) {
    $('#datepicker input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}
$('#datepicker').daterangepicker({
    opens: 'left',
    minDate: start,
    maxDate: end,
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
$('#datepicker').inputmask('99/99/9999-99/99/9999');

$(document).on('change','#reportId',function(){
    reportId = $(this).val();
    $('.panel').addClass('hidden');
    $('.pan'+reportId).removeClass('hidden');
    if(reportId=="1"){
        jList.ajax.reload();
    }else if(reportId=="2"){
        sList.ajax.reload();
    }else if(reportId=="3"){
        iList.ajax.reload();
    }else if(reportId=="4"){
        serviceList.ajax.reload();
    }
});
$(document).on('change','#datepicker',function(){
    $('#dateLabel').text($(this).val());
    reportId = $('#reportId').val();
    if(reportId=="1"){
        jList.ajax.reload();
    }else if(reportId=="2"){
        sList.ajax.reload();
    }else if(reportId=="3"){
        iList.ajax.reload();
    }else if(reportId=="4"){
        serviceList.ajax.reload();
    }
});


var jList = $('#jobsTable').DataTable({
    'responsive': true,
    "ajax" : {
        "url": '/report/filter',
        "type": 'POST',
        "data": function ( d ) {
            return {reportId: "1",date: $('#datepicker').val()};
            }
    },
    "columns": [
        { "data": "id"},
        { "data": "customer"},
        { "data": "plate",
            render:  function(data,type,row,meta){
                var transmission = (row.isManual ? 'MT' : 'AT');
                return row.plate+" | "+row.make+" "+row.model+" - ("+transmission+')';
            } 
        },
        { "data": "cash",
            render: $.fn.dataTable.render.number( ',', '.', 2, '' ),
            className: "text-right" 
        },
        { "data": "credit",
            render: $.fn.dataTable.render.number( ',', '.', 2, '' ),
            className: "text-right" 
        },
        { "data": "paid",
            render: $.fn.dataTable.render.number( ',', '.', 2, '' ),
            className: "text-right",
        },
        { "data": "balance",
            className: "text-right",
            render: function(data,type,row,meta){
                var numFormat = $.fn.dataTable.render.number( ',', '.', 2, '' ).display;
                var balance = Number(row.total)-Number(row.paid);
                return (row.paid==row.total ? 'Paid' : 'Bal: '+numFormat(balance));
            },
        },
    ],
    "footerCallback": function( row, data, start, end, display ) {
        var api = this.api();
        var numFormat = $.fn.dataTable.render.number( ',', '.', 2, '' ).display;
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,:a-zA-Z]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };
        // Total over all pages
        totalCash = api
            .column( 3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        totalCredit = api
            .column( 4 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        totalAll = api
            .column( 5 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        totalBal = api
            .column( 6 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // Update footer
        $( api.column( 3 ).footer() ).html(
            numFormat(totalCash)
        );
        $( api.column( 4 ).footer() ).html(
            numFormat(totalCredit)
        );
        $( api.column( 5 ).footer() ).html(
            numFormat(totalAll)
        );
        $( api.column( 6 ).footer() ).html(
            'Balance: '+numFormat(totalBal)
        );
    }
});

var sList = $('#salesTable').DataTable({
    'responsive': true,
    "ajax" : {
        "url": '/report/filter',
        "type": 'POST',
        "data": function ( d ) {
            return {reportId: "2",date: $('#datepicker').val()};
        }
    },
    "columns": [
        { "data": "id"},
        { "data": "customer"},
        { "data": "total",
            render: $.fn.dataTable.render.number( ',', '.', 2, '' ),
            className: "text-right" 
        }
    ],
    "footerCallback": function( row, data, start, end, display ) {
        var api = this.api();
        var numFormat = $.fn.dataTable.render.number( ',', '.', 2, '' ).display;
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,:a-zA-Z]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };
        // Total over all pages
        totalCash = api
            .column( 2 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
        // Update footer
        $( api.column( 2 ).footer() ).html(
            numFormat(totalCash)
        );
    }
});

var iList = $('#inventoryTable').DataTable({
    'responsive': true,
    "ajax" : {
        "url": '/report/filter',
        "type": 'POST',
        "data": function ( d ) {
                return {reportId: "3",date: $('#datepicker').val()};
            }
    },
    "columns": [
        { "data": "pId"},
        { "data": "product",
            render: function(data,type,row,meta){
                if(row.original!=null){
                    var part = (row.original == 'type1' ? ' - '+type1 : type2)
                }else{
                    var part = '';
                }
                return row.brand+" - "+row.product+part+" ("+row.variance+")";
            }
        },
        { "data": "currentStart",
            render: $.fn.dataTable.render.number( ',', '.', 0, '' ),
            className: "text-right"
        },
        { "data": "deliveredEnd",
            render: $.fn.dataTable.render.number( ',', '.', 0, '' ),
            className: "text-right" 
        },
        { "data": "returnedEnd",
            render: $.fn.dataTable.render.number( ',', '.', 0, '' ),
            className: "text-right" 
        },
        { "data": "totalEnd",
            render: $.fn.dataTable.render.number( ',', '.', 0, '' ),
            className: "text-right",
        },
        { "data": "currentEnd",
            render: $.fn.dataTable.render.number( ',', '.', 0, '' ),
            className: "text-right",
        },
        { "data": "reorder",
            render: $.fn.dataTable.render.number( ',', '.', 0, '' ),
            className: "text-right",
        },
        { "data": "reorder",
            render: function(data,type,row,meta){
                return (Number(row.currentEnd)<=row.reorder ? 'Warning' : 'Stable');
            },
        },
        { "data": "rank",
            render: $.fn.dataTable.render.number( ',', '.', 0, '' ),
            className: "text-right",
        },
    ],
});

var serviceList = $('#serviceTable').DataTable({
    'responsive': true,
    "ajax" : {
        "url": '/report/filter',
        "type": 'POST',
        "data": function ( d ) {
                return {reportId: "4",date: $('#datepicker').val()};
            }
    },
    "columns": [
        { "data": "s_sId"},
        { "data": "s_service",
            render: function(data,type,row,meta){
                return row.s_service+" - "+row.s_size+" ("+row.s_category+")";
            }
        },
        { "data": "total",
            className: "text-right",
            render: function(data,type,row,meta){
                var numFormat = $.fn.dataTable.render.number( ',', '.', 0, '' ).display;
                return (row.total==null ? numFormat(0) : numFormat(row.total));
            },
        },
        { "data": "rank",
            render: $.fn.dataTable.render.number( ',', '.', 0, '' ),
            className: "text-right",
        },
    ],
});