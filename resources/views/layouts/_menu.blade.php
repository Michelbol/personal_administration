<div class="navbar-nav mr-auto">
    @auth
        <div class="btn-group mr-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Financeiro
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ routeTenant('bank_accounts.index') }}">C/C</a>
                <a class="dropdown-item" href="{{ routeTenant('budget_financial.index') }}">Orçamento Financeiro</a>
                <a class="dropdown-item" href="{{ routeTenant('expense.index') }}">Despesas</a>
                <a class="dropdown-item" href="{{ routeTenant('income.index') }}">Receitas</a>
                <a class="dropdown-item" href="{{ routeTenant('cred_card.index') }}">Cartões de Crédito</a>
            </div>
        </div>
        <div class="btn-group mr-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Carro
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ routeTenant('cars.index') }}">Carros</a>
            </div>
        </div>
        <div class="btn-group mr-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Faturamento
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ routeTenant('product.index') }}">Produtos</a>
                <a class="dropdown-item" href="{{ routeTenant('supplier.index') }}">Fornecedores</a>
                <a class="dropdown-item" href="{{ routeTenant('invoice.index') }}">Notas Fiscais</a>
            </div>
        </div>
    @endauth
</div>
