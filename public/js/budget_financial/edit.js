var $MODAL_BUDGET_FINANCIAL_POSTING = $('#add_budget_financial_posting');

var datatable = $('#table_expense').DataTable({
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
            { data: 'id', name: 'budget_financial_postings.id' },
            { data: 'name', name: 'name' },
            { data: 'type', name: 'type' },
            { data: 'isFixed', name: 'isFixed' },
            { data: 'amount', name: 'amount' },
            { data: 'posting_date', name: 'posting_date' },
            { data: 'actions', name: 'actions' }
        ],
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api();

            var amountTotal = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return convertBrasilianAmountToFloat(a) + convertBrasilianAmountToFloat(b);
                }, 0 );

            $( api.column( 4 ).footer() ).html("R$: "+$('.money').masked(amountTotal));
            }
        });

$HTML.on('click', '.open-modal-budget-financial-posting', function(){
    $MODAL_BUDGET_FINANCIAL_POSTING.modal('show');

});