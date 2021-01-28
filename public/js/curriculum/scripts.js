jQuery(function ($) {

    'use strict';

    // --------------------------------------------------------------------
    // PreLoader
    // --------------------------------------------------------------------

    (function () {
        $('#preloader').delay(200).fadeOut('slow');
    }());


}); // JQuery end
function notify(message, type){
    $.notify({
        // options
        message: message
    },{
        // settings
        type: type //success, info, warning, danger
    });
}
