
function notify(message, type){
    $.notify({
        // options
        message: message
    },{
        // settings
        type: type //success, info, warning, danger
    });
}