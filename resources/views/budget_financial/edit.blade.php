@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Orçamento {{ $budgetFinancial->month($budgetFinancial->month).'/'.$budgetFinancial->year }}</h3>
        <button class="btn btn-info" data-toggle="modal" data-target="#add_budget_financial_posting">Nova Despesa/Receita</button>
        <div class="col-4">
            <div class="form-group">
                <label for="initial_balance">Saldo Inicial</label>
                <input type="text" class="money form-control" id="initial_balance" name="initial_balance"
                       value="{{ isset($budgetFinancial) ? $budgetFinancial->initial_balance : 0 }}">
            </div>
        </div>

        <table id="table_expense" class="table table-bordered table-striped" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Fixa</th>
                <th>Valor</th>
                <th>Dia Vencimento</th>
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
            </tr>
            </tfoot>
        </table>
    </div>
    @include('budget_financial.modal_add_budget_financial_posting')
@endsection

@push('scripts')
<script>
    var url_data_table = '{!! route('budget_financial_posting.get', ['id' => $budgetFinancial->id]) !!}';
</script>

<script src="{{ asset('js/budget_financial/edit.js') }}"></script>
@endpush
