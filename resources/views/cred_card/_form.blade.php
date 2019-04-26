
<div class="form-group">
    <div class="row">
        <div class="col-4">
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" class="form-control" maxlength="100" required
                   value="{{ isset($cred_card) ?  $cred_card->name : ''}}">
        </div>
        <div class="col-2">
            <label for="limit">Limite</label>
            <input type="text" class="form-control money" name="limit" id="limit"
                   value="{{ isset($cred_card) ?  $cred_card->limit : ''}}">
        </div>
        <div class="col-2">
            <label for="default_closing_date">Fechamento da Fatura</label>
            <input type="number" class="form-control" name="default_closing_date" id="default_closing_date" maxlength="10"
                   value="{{ isset($cred_card) ?  $cred_card->default_closing_date : ''}}">
        </div>
    </div>
</div>