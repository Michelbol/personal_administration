
<div class="form-group">
    <input type="hidden" id="id" name="id" value="{{ isset($expense) ?  $expense->id : ''}}">
    <div class="row">
        <div class="col-2">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="isFixed" name="isFixed"
                       {{ isset($expense) ?  $expense->isFixed ? 'checked' : '' : ''}}
                       value="true">
                <label class="custom-control-label" for="isFixed">Despesas Fixa</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label for="name">Nome</label>
            <input type="text" name="name" id="name" class="form-control" maxlength="100" required
                   value="{{ isset($expense) ?  $expense->name : ''}}">
        </div>
        <div class="col-2">
            <label for="amount">Valor</label>
            <input type="text" class="form-control money" required name="amount" id="amount" maxlength="10"
                   value="{{ isset($expense) ?  $expense->amount : ''}}">
        </div>

    </div>
</div>