
<div class="form-group">
    <div class="row">
        <div class="col-4">
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" class="form-control" maxlength="100" required
                   value="{{ isset($bank_account) ?  $bank_account->name : ''}}">
        </div>
        <div class="col-2">
            <label for="agency">Agência</label>
            <input type="text" class="form-control number" required name="agency" id="agency" maxlength="10"
                   value="{{ isset($bank_account) ?  $bank_account->agency : ''}}">
        </div>
        <div class="col-2">
            <label for="agency">Operação</label>
            <input type="text" class="form-control number" name="operation" id="operation" maxlength="10"
                   value="{{ isset($bank_account) ?  $bank_account->operation : ''}}">
        </div>
        <div class="col-1">
            <label for="digit_agency">Digito</label>
            <input type="text" class="form-control number" name="digit_agency" id="digit_agency" maxlength="2"
                   value="{{ isset($bank_account) ?  $bank_account->digit_agency : ''}}">
        </div>

        <div class="col-2">
            <label for="number_account">Número Conta</label>
            <input type="text" class="form-control number" required name="number_account" id="number_account" maxlength="10"
                   value="{{ isset($bank_account) ?  $bank_account->number_account : ''}}">
        </div>
        <div class="col-1">
            <label for="digit_account">Digito</label>
            <input type="text" class="form-control number" name="digit_account" id="digit_account" maxlength="2"
                   value="{{ isset($bank_account) ?  $bank_account->digit_account : ''}}">
        </div>
    </div>
    <div class="row">
        @component('bank.select2',
                    ['col' => 'col-12',
                     'id_bank' => 'bank_id',
                     'id_selected' => isset($bank_account->bank_id) ? $bank_account->bank_id : null,
                     'name_selected' => isset($bank_account->bank_id) ? $bank_account->bank->name : null,
                     'required' => true])
        @endcomponent
    </div>
    <div class="row">
        <div class="col-4">
            <label for="balance">Saldo</label>
            <input type="text" class="form-control" name="account_balance" id="account_balance" {{ isset($bank_account) ? 'disabled' : '' }}
                   value="{{ isset($last_balance) ?  'R$: '.\App\Utilitarios::getFormatReal($last_balance) : ''}}">
        </div>
    </div>
</div>