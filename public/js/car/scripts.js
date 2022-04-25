let $MODEL = $('#model');
let $YEAR = $('#year');
let $BRAND = $('#brand');
let selectedModel = null;
let selectedYear = null;

$(document).ready(function () {
    let config = configDataRangePicker();
    config.singleDatePicker = true;
    config.timePicker = true;
    config.timePicker24Hour = true;
    config.locale.format= "DD/MM/YYYY";
    $('input.date').daterangepicker(config);
    if($BRAND.val().length > 0){
        $BRAND.trigger('change');
    }
});

$MODEL.select2();
$YEAR.select2();
$BRAND.select2();

$BRAND.on('change', function(){
    if($(this).val() !== ""){
        activeModel();
    }
});

$MODEL.on('change', function(){
    if($(this).val() !== ""){
        activeYears();
    }
});

$YEAR.on('change', function(){
    if($(this).val() !== ""){
        // showPrice();
    }
})

function activeModel(){
    let options = '';
    let defaultOption = '<option value="">Selecione um Modelo</option>';
    let brandId = $BRAND.val();

    $MODEL.html(defaultOption);

    let request = $.ajax({
        url: URLBASE()+`/fipe/models/${brandId}`,
        method: "GET"
    });
    request.done(function (data) {
        for (let i = 0; i < data.length; i++){
            let option = makeOption(data[i].codigo, data[i].nome);
            options += option;
        }
        $MODEL.append(options);
        if(selectedModel !== null){
            $MODEL.val(selectedModel).trigger('change');
            selectedModel = null;
        }
    });
}

function activeYears(){
    let options = '';
    let defaultOption = '<option value="">Selecione um Ano</option>';
    let brandId = $BRAND.val();
    let modelId = $MODEL.val();

    $YEAR.html(defaultOption);

    let request = $.ajax({
        url: URLBASE()+`/fipe/years/${brandId}/${modelId}`,
        method: "GET"
    });
    request.done(function (data) {
        for (let i = 0; i < data.length; i++){
            let option = makeOption(data[i].codigo, data[i].nome);
            options += option;
        }
        $YEAR.append(options);
        if(selectedYear !== null){
            $YEAR.val(selectedYear).trigger('change');
            selectedYear = null;
        }
    });
}

function makeOption(id, name){
    let template = '<option value="{id}">{name}</option>';
    return template.replace('{id}', id).replace('{name}', name);
}

function showPrice(){
    let brandId = $BRAND.val();
    let modelId = $MODEL.val();
    let year = $YEAR.val();

    let request = $.ajax({
        url: URLBASE()+`/fipe/price/${brandId}/${modelId}/${year}`,
        method: "GET"
    });
    request.done(function (data) {
        $('#price').html(JSON.stringify(data));
    });
}
