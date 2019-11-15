$('input[name="dates"]').daterangepicker(configDataRangePicker());
$('#dates').val(null);

$('#dates').on('cancel.daterangepicker', function(ev, picker) {
    //do something, like clearing an input
    $('#dates').val('');
});

$('html').on('click', '.modal-edit', function(){
    let request = $.ajax({
        url: URLBASE()+"/bank_account_posting/show/"+$(this).attr('data-id'),
        method: "GET"
    });
    request.done(function (data) {
       console.log(data);
       fillModal(data.bankAccountPosting);
        $('#add_bank_account_posting').modal('show');
    });
});
$('#add_bank_account_posting').on('hide.bs.modal', function(){
    cleanModal();
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
    { data: 'account_balance', name: 'account_balance' },
    { data: 'actions', name: 'actions' }
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

function fillModal(bank_account_posting){
    $('#id')                            .val(bank_account_posting.id);
    $('#document')                      .val(bank_account_posting.document);
    $('#posting_date')                  .val(moment(bank_account_posting.posting_date, "YYYY-MM-DD HH:mm:ss").format('DD/MM/YYYY HH:mm'));
    $('#amount')                        .val(bank_account_posting.amount);
    $('#type')                          .val(bank_account_posting.type);
    $('#type_bank_account_posting_id')  .val(bank_account_posting.type_bank_account_posting_id);
}

function cleanModal(){
    $('#id').val('');
    $('#document').val('');
    $('#posting_date').val(moment().format('DD/MM/YYYY HH:mm'));
    $('#amount').val('');
    $('#type').val('C');
    $('#type_bank_account_posting_id').val('');
}

$('#type').on('change', function () {
    if($(this).val() === 'C'){
        $('#income_id_div').css('display', 'block');
        $('#expense_id_div').css('display', 'none');
        $('#expense_id').val(null);
    }else{
        $('#income_id_div').css('display', 'none');
        $('#expense_id_div').css('display', 'block');
        $('#income_id').val(null);
    }
});
