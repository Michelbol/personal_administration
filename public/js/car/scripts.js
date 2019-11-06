$(document).ready(function () {
    let config = configDataRangePicker();
    config.singleDatePicker = true;
    config.timePicker = true;
    config.timePicker24Hour = true;
    config.locale.format= "DD/MM/YYYY";
    config.startDate = moment();
    $('input.date').daterangepicker(config);
});
