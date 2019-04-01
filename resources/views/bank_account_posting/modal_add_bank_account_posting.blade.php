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
                    <form action="{{ route('bank_account_posting.store') }}" method="post" id="form_bank_acount_posting">
                        {{csrf_field()}}
                        <div class="row">
                            <input type="hidden" id="bank_account_id" name="bank_account_id" value="{{$bankAccount->id}}">
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
                                    <option value="0">Informe um tipo</option>
                                    @foreach($filter_type_bank_account_postings as $type_bank_account_postings)
                                        <option value="{{$type_bank_account_postings->id}}">{{$type_bank_account_postings->name}}</option>
                                    @endforeach
                                </select>
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