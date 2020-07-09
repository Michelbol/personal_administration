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


@push('scripts')
    <script>
        $('.edit-finalized').dblclick( function(){
            $(this).removeClass('disabled');
        })
    </script>
@endpush
