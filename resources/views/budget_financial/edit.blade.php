@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Orçamento {{ $budgetFinancial->month($budgetFinancial->month).'/'.$budgetFinancial->year }}</h3>
        <button class="btn btn-info" data-toggle="modal" data-target="#add_budget_financial_posting">Nova Despesa/Receita</button>
        <form action="{{ routeTenant('budget_financial.updateinitialbalance', ['id' => $budgetFinancial->id]) }}" method="post">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="initial_balance">Saldo Inicial</label>
                        <input type="text" class="money2 form-control" id="initial_balance" name="initial_balance"
                               value="{{ isset($budgetFinancial) ? $budgetFinancial->initial_balance : 0 }}" disabled>
                        <a href="#" id="edit_initial_balance"><i class="fa fa-edit" style="position: absolute; top: 43px; right: 20px;"></i></a>
                    </div>
                </div>
                <div class="input-group-btn" style="padding-top: 30px ;">
                    <button class="btn btn-primary" id="save_initial_balance" disabled>Aplicar Saldo Informado</button>
                    <a class="btn btn-info" href="{{ routeTenant('budget_financial.last_month' , ['id' => $budgetFinancial->id]) }}">Aplicar Saldo do mês anterior</a>
                    <a class="btn btn-dark" href="{{ routeTenant('budget_financial.restart' , ['id' => $budgetFinancial->id]) }}">Reiniciar</a>
                </div>

            </div>
        </form>

        <table id="table_expense" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Fixa</th>
                <th>Valor</th>
                <th>Dia Vencimento</th>
                <th>Saldo</th>
                <th>Ação</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot align="right">
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
        </table>
    </div>
    @include('budget_financial.modal_add_budget_financial_posting')
@endsection

@push('scripts')
<script>
    var url_data_table = '{!! routeTenant('budget_financial_posting.get', ['id' => $budgetFinancial->id]) !!}';
</script>

<script src="{{ asset('js/budget_financial/edit.js') }}"></script>
@endpush
