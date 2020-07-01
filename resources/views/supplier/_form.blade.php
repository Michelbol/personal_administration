
<div class="form-group">
    <input type="hidden" id="id" name="id" value="{{ isset($supplier) ?  $supplier->id : ''}}">
    <div class="row">
        <div class="col-4">
            <label for="fantasy_name">Nome Fantasia</label>
            <input type="text" name="fantasy_name" id="fantasy_name" class="form-control" maxlength="100" required
                   value="{{ isset($supplier) ?  $supplier->fantasy_name : ''}}">
        </div>
        <div class="col-4">
            <label for="company_name">Razão Social</label>
            <input type="text" name="company_name" id="company_name" class="form-control" maxlength="100" required
                   value="{{ isset($supplier) ?  $supplier->company_name : ''}}">
        </div>
        <div class="col-4">
            <label for="cnpj">CNPJ</label>
            <input type="text" name="cnpj" id="cnpj" class="form-control cnpj" maxlength="100" required
                   value="{{ isset($supplier) ?  $supplier->cnpj : ''}}">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-5">
            <label for="address">Endereço</label>
            <input type="text" name="address" id="address" class="form-control" maxlength="100" required
                   value="{{ isset($supplier) ?  $supplier->address : ''}}">
        </div>
        <div class="col-2">
            <label for="address_number">Número</label>
            <input type="text" name="address_number" id="address_number" class="form-control" maxlength="100" required
                   value="{{ isset($supplier) ?  $supplier->address_number : ''}}">
        </div>
        <div class="col-5">
            <label for="neighborhood">Bairro</label>
            <input type="text" name="neighborhood" id="neighborhood" class="form-control" maxlength="100" required
                   value="{{ isset($supplier) ?  $supplier->neighborhood : ''}}">
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <label for="state">Estado</label>
            <input type="text" name="state" id="state" class="form-control" maxlength="100" required
                   value="{{ isset($supplier) ?  $supplier->state : ''}}">
        </div>
        <div class="col-6">
            <label for="city">Cidade</label>
            <input type="text" name="city" id="city" class="form-control" maxlength="100" required
                   value="{{ isset($supplier) ?  $supplier->city : ''}}">
        </div>
    </div>
</div>
