<div class="chart-container" style="position: relative; height:40vh; width:80vw">
    <canvas id="myChart"></canvas>
</div>

@push('scripts')
    <script>
        let expense =        [{!! collect($budgetsFinancial->pluck('sum_expense'))->implode(',') !!}];
        let income =         [{!! collect($budgetsFinancial->pluck('sum_income'))->implode(',') !!}];
        let monthlyBalance = [{!! collect($budgetsFinancial->pluck('monthly_balance'))->implode(',') !!}];
        let balance =        [{!! collect($budgetsFinancial->pluck('balance'))->implode(',') !!}];
        let initialBalance = [{!! collect($budgetsFinancial->pluck('initial_balance'))->implode(',') !!}];
        let ctx = document.getElementById('myChart').getContext('2d');
        let myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                datasets: [
                    {
                        label: ['Despesas'],
                        data: expense,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    },
                    {
                        label: ['Receitas'],
                        data: income,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.2)',
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                        ],
                        borderWidth: 1
                    },
                    {
                        label: ['Saldo Mensal'],
                        data: monthlyBalance,
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 206, 86, 1)',
                        ],
                        borderWidth: 1
                    },
                    {
                        label: ['Saldo Inicial'],
                        data: initialBalance,
                        backgroundColor: [
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(153, 102, 255, 1)',
                        ],
                        borderWidth: 1
                    },
                    {
                        label: ['Saldo'],
                        data: initialBalance,
                        backgroundColor: [
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }
                ],
            },
            options: {
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                beginAtZero: true
                            }
                        }
                    ]
                }
            }
        });

    </script>
@endpush
