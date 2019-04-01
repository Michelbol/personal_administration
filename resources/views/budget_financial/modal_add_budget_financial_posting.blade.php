<div class="modal" tabindex="-1" role="dialog" id="add_budget_financial_posting">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Lançamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <div class="container-fluid">
                    <form action="{{ route('bank_account_posting.store') }}" method="post" id="form_bank_acount_posting">
                        {{csrf_field()}}
                        <div class="row">
                            <input type="hidden" id="id" name="id" value="">
                            <div class="col-4 form-group">
                                <label for="type_posting">Tipo Lançamento</label>
                                <select class="form-control" id="type_posting" name="type_posting" >
                                    <option value="income">Receita</option>
                                    <option value="expense">Despesa</option>
                                </select>
                            </div>
                            <div class="col-4 form-group" id="income_id_div">
                                <label for="income_id">Receitas</label>
                                <select class="form-control" id="income_id" name="income_id">
                                    <option value="">Informe uma receita</option>
                                    @foreach($incomes as $income)
                                        <option value="{{ $income->id }}"> {{ $income->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 form-group" id="expense_id_div" style="display: none">
                                <label for="expense_id">Despesas</label>
                                <select class="form-control" id="expense_id" name="expense_id">
                                    <option value="">Informe uma despesa</option>
                                    @foreach($expenses as $expense)
                                        <option value="{{ $expense->id }}"> {{ $expense->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 form-group">
                                <label for="amount">Valor</label>
                                <input type="text" class="form-control money" id="amount" name="amount">
                            </div>
                            <div class="col-3 form-group">
                                <label for="due_date">Data Vencimento</label>
                                <input class="form-control" type="text" id="due_date" name="due_date">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="$('#form_bank_acount_posting').submit()">Save changes</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let year = '{!! $budgetFinancial->year !!}';
    let month = '{!! $budgetFinancial->month !!}';
    let date = moment('01/'+month+'/'+year, 'DD/M/YYYY');
    let config = configDataRangePicker();
    config.singleDatePicker = true;
    config.locale.format= "DD/MM/YYYY";
    config.minDate = date.startOf('month').format("DD/MM/YYYY");
    config.maxDate = date.endOf('month').format("DD/MM/YYYY");
    $('#due_date').daterangepicker(config);
    $('#type_posting').on('change', function () {
       if($('#type_posting').val() === 'income'){
           $('#income_id_div').css('display', 'block');
           $('#expense_id_div').css('display', 'none');
           $('#expense_id').val(null);
       }else{
           $('#income_id_div').css('display', 'none');
           $('#expense_id_div').css('display', 'block');
           $('#income_id').val(null);
       }
    });
</script>
@endpush