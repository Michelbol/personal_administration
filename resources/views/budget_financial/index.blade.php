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

        @if(isset($budgetsFinancial))
            @foreach($budgetsFinancial as $budgetFinancial)
                @php
                    $expenseSum = $budgetFinancial->budgetFinancialPostingsExpenses()->sum('amount');
                    $incomeSum = $budgetFinancial->budgetFinancialPostingsIncomes()->sum('amount');
                @endphp
                <div class="col-md-3">
                    <div class="card" style="width: 18rem;">
                        <div class="card-title">
                            <p class="card-header">
                                {!! $budgetFinancial->month($budgetFinancial->month).($budgetFinancial->isFinalized ?
                                ' - Finalizado <i class="text-success fas fa-check-circle"></i>' : '')  !!} </p>
                        </div>
                        <div class="card-body">
                            <div class="collapse" id="balance-collapse-{{ $budgetFinancial->id }}">
                                <table align="center">
                                    <tr>
                                        <td>1. Saldo Inicial:</td>
                                        <td>R$: {{ \App\Utilitarios::getFormatReal($budgetFinancial->initial_balance) }}</td>
                                    </tr>
                                    <tr>
                                        <td>2. Total Receitas:</td>
                                        <td>R$: {{ \App\Utilitarios::getFormatReal($incomeSum) }}</td>
                                    </tr>
                                    <tr>
                                        <td>3. Total Despesas:</td>
                                        <td>R$: -{{ \App\Utilitarios::getFormatReal($expenseSum) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <table align="center">
                                <tr>
                                    <td>4. Saldo(2-3):</td>
                                    <td>R$: {{ \App\Utilitarios::getFormatReal($incomeSum - $expenseSum) }}</td>
                                </tr>
                                <tr>
                                    <td>Total:(1+4)</td>
                                    <td>R$: {{ \App\Utilitarios::getFormatReal($budgetFinancial->initial_balance + $incomeSum - $expenseSum) }}</td>
                                </tr>
                            </table>
                            <div class="" align="center">
                                <div class="input-group-btn" align="center">
                                    <a align="center" class="btn btn-info" href="#balance-collapse-{{ $budgetFinancial->id }}" data-toggle="collapse">Detalhes</a>
                                    <a align="center" href="{{ routeTenant('budget_financial.edit', [$budgetFinancial->id]) }}" class="btn btn-primary">{!! $budgetFinancial->isFinalized ? 'Finalizado' : 'Planejar' !!}</a>
                                </div>
                            </div>
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
