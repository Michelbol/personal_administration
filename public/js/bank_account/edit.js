let ctx = document.getElementById('myChart').getContext('2d');
let myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: getMonths(),
        datasets: [{
            label: ['Juros'],
            data: interest,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
            ],
            borderWidth: 1
        },
            {
                label: ['Saldo'],
                data: balance,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
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

let configDateRange = configDataRangePicker();
configDateRange.maxDate = moment().format("DD/MM/YYYY");
configDateRange.minDate = moment().subtract(1, 'year').format("DD/MM/YYYY");

$('input[name="period_date"]').daterangepicker(configDateRange);

function getMonths(){
    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    let result = []
    let periodDate = $('#period_date').val().split(' - ');
    let startMonth = moment(periodDate[0], "DD/MM/YYYY");
    let endMonth = moment(periodDate[1], "DD/MM/YYYY");
    let date = moment(startMonth);
    let diff = getPositiveNumber(startMonth.diff(endMonth, 'months'));
    for(let i = 0; i <= diff; i++){
        result.push(months[date.month()]);
        date.add(1, 'month');
    }
    return result;
}

function getPositiveNumber(number){
    if(number < 0){
        number = number*(-1);
    }
    return number;
}
