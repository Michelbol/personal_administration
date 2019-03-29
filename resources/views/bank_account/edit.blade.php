@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Editar Conta Corrente</h3>

        <form action="{{ route('bank_accounts.update', ['id' => $bank_account->id]) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            @include('bank_account._form')
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Atualizar</button>
                <a href="{{ route('bank_accounts.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
        <form action="{{ route('bank_accounts.edit', $bank_account->id) }}">
            <div class="col-md-2">
                <label for="year">Ano do Orçamento</label>
                <input type="text" id="year" name="year" class="form-control"
                       value="{{ $year_search }}">
            </div>
        </form>
        <div class="chart-container" style="position: relative; height:40vh; width:80vw">
            <canvas id="myChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var interest = [{!! $monthInterest !!}];
    var balance = [{!! $monthBalance !!}];
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            datasets: [{
                label: ['Juros'],
                data: interest,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
//                    'rgba(54, 162, 235, 0.2)',
//                    'rgba(255, 206, 86, 0.2)',
//                    'rgba(75, 192, 192, 0.2)',
//                    'rgba(153, 102, 255, 0.2)',
//                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
//                    'rgba(54, 162, 235, 1)',
//                    'rgba(255, 206, 86, 1)',
//                    'rgba(75, 192, 192, 1)',
//                    'rgba(153, 102, 255, 1)',
//                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            },
                {
                    label: ['Saldo / 100'],
                    data: balance,
                    backgroundColor: [
//                        'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
//                    'rgba(255, 206, 86, 0.2)',
//                    'rgba(75, 192, 192, 0.2)',
//                    'rgba(153, 102, 255, 0.2)',
//                    'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
//                        'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
//                    'rgba(255, 206, 86, 1)',
//                    'rgba(75, 192, 192, 1)',
//                    'rgba(153, 102, 255, 1)',
//                    'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }
            ],

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
    });
</script>
@endpush
