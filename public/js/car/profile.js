let carSupplyJs = document.getElementById('car-supply').getContext('2d');
let carLitersJs = document.getElementById('car-litters').getContext('2d');
let carTraveledKilometersJs = document.getElementById('car-traveled-kilometers').getContext('2d');
let carAverageJs = document.getElementById('car-averages').getContext('2d');
let months = getMonths();
let graphCarSupply = new Chart(carSupplyJs, {
    type: 'line',
    data: {
        labels: months,
        datasets: [
            {
                label: ['Abastecimentos'],
                data: carSupply,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1
            },
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

let graphCarLiters = new Chart(carLitersJs, {
    type: 'line',
    data: {
        labels: months,
        datasets: [
            {
                label: ['Litros Abastecidos'],
                data: liters,
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
let graphCarTraveledKilometers = new Chart(carTraveledKilometersJs, {
    type: 'line',
    data: {
        labels: months,
        datasets: [
            {
                label: ['Km Rodados'],
                data: traveledKilometers,
                backgroundColor: [
                    'rgba(153, 102, 255, 0.2)',
                ],
                borderColor: [
                    'rgb(153, 102, 255, 1)',
                ],
                borderWidth: 1
            },
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
let graphAverages = new Chart(carAverageJs, {
    type: 'line',
    data: {
        labels: months,
        datasets: [
            {
                label: ['Médias'],
                data: averages,
                backgroundColor: [
                    'rgb(255, 159, 64, 0.2)',
                ],
                borderColor: [
                    'rgb(255, 159, 64, 1)',
                ],
                borderWidth: 1
            },
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

$('input[name="period"]').daterangepicker(configDateRange);

function getMonths(){
    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    let result = [];
    let periodDate = $('#period').val().split(' - ');
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
