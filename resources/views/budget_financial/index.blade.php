@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h3>Orçamento Financeiro</h3>
        </div>
        <form action="">
            <div class="row">
                <div class="col-md-4">
                    <label for="year">Ano do Orçamento</label>
                    <input type="text" id="year" name="year" class="form-control"
                           value="{{ isset($budgedFinancialYear) ? $budgedFinancialYear : 0 }}">
                </div>
                <div class="col-md-6">
                    <label for="user_id">Usuário</label>
                    <select class="form-control" name="user_id" id="user_id">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (isset($selected_user) && $selected_user->id === $user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button style="margin-top: 31px" class="form-group btn btn-info">Alterar informações</button>
            </div>
        </form>

        <div class="row" style="margin-top: 10px">
        @if(isset($budgedFinancials))
            @foreach($budgedFinancials as $budgedFinancial)
                <div class="col-md-3">
                    <div class="card" style="width: 18rem;">
                        <div class="card-title">
                            <p class="card-header">
                                {!! $budgedFinancial->month($budgedFinancial->month).($budgedFinancial->isFinalized ?
                                ' - Finalizado <i class="text-success fas fa-check-circle"></i>' : '')  !!} </p>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                Total Receitas: R$: {{ \App\Utilitarios::getFormatReal($incomeSum = $budgedFinancial->budgetFinancialPostingsIncomes()->sum('amount')) }}</p>
                            <p class="card-text">
                                Total Despesas: R$: {{ \App\Utilitarios::getFormatReal($expenseSum = $budgedFinancial->budgetFinancialPostingsExpenses()->sum('amount')) }}</p>
                            <p class="card-text">
                                Saldo: R$: {{ \App\Utilitarios::getFormatReal($budgedFinancial->initial_balance + $incomeSum - $expenseSum) }} | {{ \App\Utilitarios::getFormatReal($incomeSum - $expenseSum) }}</p>
                            <a href="{{ routeTenant('budget_financial.edit', [$budgedFinancial->id]) }}" class="btn btn-primary">{!! $budgedFinancial->isFinalized ? 'Finalizado' : 'Planejar' !!}</a>
                        </div>
                    </div>
                </div>
            @endforeach
       @endif
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        console.log('teste');
        $('.edit-finalized').dblclick( function(){
            $(this).removeClass('disabled');
        })
    </script>
@endpush
