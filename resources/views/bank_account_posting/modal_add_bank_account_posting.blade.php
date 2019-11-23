<div class="modal" tabindex="-1" role="dialog" id="add_bank_account_posting">
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
                    <form action="{{ routeTenant('bank_account_posting.store') }}" method="post" id="form_bank_acount_posting">
                        {{csrf_field()}}
                        <div class="row">
                            <input type="hidden" id="bank_account_id" name="bank_account_id" value="{{$bankAccount->id}}">
                            <input type="hidden" id="id" name="id" value="">
                            <div class="col-4 form-group">
                                <label for="document">Documento</label>
                                <input type="text" class="form-control" id="document" name="document" maxlength="15-">
                            </div>
                            <div class="col-4 form-group">
                                <label for="posting_date">Data Lançamento</label>
                                <input type="text" class="form-control" id="posting_date" name="posting_date">
                            </div>
                            <div class="col-4 form-group">
                                <label for="amount">Valor</label>
                                <input type="text" class="form-control money" id="amount" name="amount">
                            </div>
                            <div class="col-3 form-group">
                                <label for="type">Tipo</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="C">Crédito</option>
                                    <option value="D">Débito</option>
                                </select>
                            </div>
                            <div class="col-4 form-group">
                                <label for="type_bank_account_posting_id">Tipo de Lançamento</label>
                                <select name="type_bank_account_posting_id" id="type_bank_account_posting_id" class="form-control">
                                    <option value="">Informe um tipo</option>
                                    @foreach($filterTypeBankAccountPostings as $typeBankAccountPosting)
                                        <option value="{{$typeBankAccountPosting->id}}">{{$typeBankAccountPosting->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 form-group" id="income_id_div">
                                <label for="income_id">Receitas
                                    <a href="#" id="add_new_income"><i class="fas fa-plus-circle"></i>Nova Receita</a></label>
                                <select class="form-control" id="income_id" name="income_id">
                                    <option value="">Informe uma receita</option>
                                    @foreach($incomes as $income)
                                        <option value="{{ $income->id }}"> {{ $income->name }}</option>
                                    @endforeach
                                </select>
                                <input class="form-control" placeholder="Digite o nome da receita" type="text"
                                       id="new_income" name="new_income" style="display: none">
                            </div>
                            <div class="col-4 form-group" id="expense_id_div" style="display: none">
                                <label for="expense_id">Despesas
                                    <a href="#" id="add_new_expense"><i class="fas fa-plus-circle"></i>Nova Despesa</a></label>
                                <select class="form-control" id="expense_id" name="expense_id">
                                    <option value="">Informe uma despesa</option>
                                    @foreach($expenses as $expense)
                                        <option value="{{ $expense->id }}"> {{ $expense->name }}</option>
                                    @endforeach
                                </select>
                                <input class="form-control" placeholder="Digite o nome da despesa" type="text"
                                       id="new_expense" name="new_expense" style="display: none">
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
