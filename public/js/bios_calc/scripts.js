function insydeKeygen(serial){
    const salt = "Iou|hj&Z";
    let password = "";
    let b = 0;
    let a = 0;
    for (let i = 0; i < 8; i++) {
        b = salt.charCodeAt(i) ^ serial.charCodeAt(i);
        a = b;
        // a = (a * 0x66666667) >> 32;
        a = (a * 0x66666667);
        a = Math.floor(a  / Math.pow(2, 32));
        a = (a >> 2) | (a & 0xC0);
        if (a & 0x80000000) {
            a++;
        }
        a *= 10;
        password += (b - a).toString();
    }
    return [password];
}

$('#btn_bios_code').on('click', function () {
    let $BIOSCODE = $('#bios_code');
    $('#label_bios_code').text(insydeKeygen($BIOSCODE.val()));
});