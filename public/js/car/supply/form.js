$(document).ready(function () {
    let config = configDataRangePicker();
    config.singleDatePicker = true;
    config.timePicker = true;
    config.timePicker24Hour = true;
    config.locale.format= "DD/MM/YYYY";
    $('input.date').daterangepicker(config);
});
