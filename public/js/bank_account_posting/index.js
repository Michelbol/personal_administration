$('input[name="dates"]').daterangepicker(configDataRangePicker());
$('#dates').val(null);

$('#dates').on('cancel.daterangepicker', function(ev, picker) {
    //do something, like clearing an input
    $('#dates').val('');
});


let config = configDataRangePicker();
config.singleDatePicker = true;
config.timePicker = true;
config.timePicker24Hour = true;
config.locale.format= "DD/MM/YYYY HH:mm";
config.startDate = moment();
$('#posting_date').daterangepicker(config);

var datatable = $('#table_bank_account_posting').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: url_data_table,
        data: function (s) {
            s.type_name     = $('#filter_type_bank_account_postings').val();
            s.type          = $('#filter_type').val();
            s.posting_date  = $('#dates').val();
        }
    },
    columns: [
    { data: 'id', name: 'id' },
    { data: 'document', name: 'document' },
    { data: 'posting_date', name: 'posting_date' },
    { data: 'amount', name: 'amount' },
    { data: 'type', name: 'type' },
    { data: 'type_name', name: 'type_bank_account_postings.name' },
    { data: 'account_balance', name: 'account_balance' }
],
    createdRow: function( row, data, dataIndex ) {
    if ( data['type'] === "Crédito" ) {
        $(row).css('background', 'darkseagreen' );
    }else{
        $(row).css('background', 'indianred' );
    }
}
});

$('#search').on('click', function () {
    datatable.draw();
});