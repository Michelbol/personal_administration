var $HTML = $('html');
$(document).ready(function(){
    $('.date').mask('00/00/0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.cpf').mask('000.000.000-00', {reverse: true});
    $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('.money').mask('000.000.000.000.000,00', {reverse: true});
    $('.money2').mask('#.###.##', {
        reverse: true,
        translation: {
            '#': {
                pattern: /-|\d/,
                recursive: true
            }
        },
        onChange: function (value, e) {
            e.target.value = value.replace(/(?!^)-/g, 	'').replace(/^(-[,.])/, '-').replace(/(\d+\.*)\.(\d{2})$/,"$1,$2");
        }
    });

    $('.percent').mask('##0,00%', {reverse: true});

    $('[data-toggle="tooltip"]').tooltip()
});
function notify(message, type){
    $.notify({
        // options
        message: message
    },{
        // settings
        type: type //success, info, warning, danger
    });
}

function configDataRangePicker(){
    return {
        "locale": {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Custom",
            "weekLabel": "W",
            "daysOfWeek": [
                "Do",
                "Se",
                "Te",
                "Qua",
                "Qui",
                "Sex",
                "Sa"
            ],
            "monthNames": [
                "Janeiro",
                "Fevereiro",
                "Março",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro"
            ],
            "firstDay": 1
        }
    };
}

function convertBrasilianAmountToFloat(value){
    if(typeof value === 'number'){
        return value;
    }
    if(value.indexOf('-') !== value.lastIndexOf('-')){
        value = value.replace('-R$:', 'R$:');
    }
    return parseFloat(value.replace('.', '').replace(',','.').replace(/[^0-9./-]/g,''));
}

function URLBASE(){
    let url_full = window.location.href;
    let url_split = url_full.split('/');
    return url_split[0]+'//'+url_split[1]+url_split[2]+'/'+url_split[3];
}
