@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Análise de Despesas</h3>
        <div class="form-group">
            <form action="">
                <div class="row">
                    <div class="col-4">
                        <label for="bank_account_id">Conta Bancária</label>
                        <select class="form-control" name="bank_account_id" id="bank_account_id">
                            <option value="">Selecione um Banco</option>
                            @foreach($bankAccounts as $bankAccount)
                                <option value="{{ $bankAccount->id }}" {{ isset($selectedBank) ? ($bankAccount->id === $selectedBank ? 'selected' : '') : '' }}>{{ $bankAccount->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="year">Ano</label>
                        <input type="text" id="year" name="year" class="form-control"
                               value="{{ $year }}">
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-info">Selecionar</button>
            </form>
        </div>

        <canvas id="myChart"></canvas>
    </div>
@endsection

@push('scripts')
    <script>
        let expenses = {!! $expenses !!};

        let ctx = document.getElementById('myChart').getContext('2d');
        let myChart = new Chart(ctx, makeData(expenses));

        function makeDataSetByExpense(expense, key) {
            return {
                label: [key],
                data: expense,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1
            };
        }

        function makeData(expenses){
            let data = {
                type: 'line',
                data: {
                    labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                    datasets: []
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            };
            Object.entries(expenses).forEach(([key, value]) => {
                let dataset = makeDataSetByExpense(expenses[key], key);
                data.data.datasets.push(dataset);
            });
            console.log(data.data);
            return data;
        }
    </script>
@endpush
