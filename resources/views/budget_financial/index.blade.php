@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h3>Orçamento Financeiro</h3>
            <div class="col-md-2">
                <label for="year">Ano do Orçamento</label>
                <input type="text" id="year" name="year" class="form-control"
                       value="{{ isset($budgedFinancialYear) ? $budgedFinancialYear : 0 }}">
            </div>
        </div>

        <div class="row" style="margin-top: 10px">
            @foreach($budgedFinancials as $budgedFinancial)
                <div class="col-md-3">
                    <div class="card" style="width: 18rem;">
                        <div class="card-title">
                            <p class="card-header">
                                {!! $budgedFinancial->month($budgedFinancial->month).($budgedFinancial->isFinalized ?
                                ' - Finalizado <i class="text-success fas fa-check-circle"></i>' : '')  !!} </p>
                        </div>
                        <div class="card-body">
                            <p class="card-text" style="">Total Receitas:</p>
                            <p class="card-text">Total Despesas:</p>
                            <a href="{{ route('budget_financial.edit', $budgedFinancial->id) }}" class="btn btn-primary {!! $budgedFinancial->isFinalized ? 'disabled ">Finalizado' : '">Planejar' !!}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    </div>
@endsection

@push('scripts')
<script>

</script>
@endpush
