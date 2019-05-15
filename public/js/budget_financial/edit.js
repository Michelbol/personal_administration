var $MODAL_BUDGET_FINANCIAL_POSTING = $('#add_budget_financial_posting');

$(document).ready(function(){
    $('#initial_balance').trigger('input')
});
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
            { data: 'account_balance', name: 'account_balance' },
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

            $( api.column( 4 ).footer() ).html("R$: "+$('.money').masked(amountTotal.toFixed(2)));
            }
        });

$HTML.on('click', '.open-modal-budget-financial-posting', function(){
    let budgetfinancialposting = datatable.row($(this).closest('tr')[0]).data();
    fillModal(budgetfinancialposting);
    $MODAL_BUDGET_FINANCIAL_POSTING.modal('show');
    if($(this).attr('title') === 'Editar'){
        $('#form_budget_financial_posting').prop('action', URLBASE()+'/budget_financial_posting/'+budgetfinancialposting.id);
        $('#_method').val('put');
    }else{
        $('#form_budget_financial_posting').prop('action', URLBASE()+'/budget_financial_posting/');
        $('#_method').val('post');
    }
});

$('#edit_initial_balance').on('click', function(){
   $('#initial_balance').prop('disabled', false);
   $('#save_initial_balance').prop('disabled', false);
});

function fillModal(budgetFinancialPosting){
    $('#id').val(budgetFinancialPosting.id);
    $('#budget_financial_id').val(budgetFinancialPosting.budget_financial_id);
    if(budgetFinancialPosting.income_id === null){
        $('#type_posting').val('expense');
    }else{
        $('#type_posting').val('income');
    }
    $('#income_id').val(budgetFinancialPosting.income_id);
    $('#expense_id').val(budgetFinancialPosting.expense_id);
    $('#amount').val(convertBrasilianAmountToFloat(budgetFinancialPosting.amount.replace('-', '')));
    $('#posting_date').val(budgetFinancialPosting.posting_date);
}

$('#add_new_income').on('click', function(){
    $('#income_id').toggle();
    $('#new_income').toggle();
});
$('#add_new_expense').on('click', function(){
    $('#expense_id').toggle();
    $('#new_expense').toggle();
});