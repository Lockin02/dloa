$(document).ready(function() {
    //����֤
    validate({
        "balanceCode": {
            required: true
        },
        "billMoney": {
            required: true
        },
        "billTypeName": {
            required: true
        },
        "billDate": {
            required: true
        },
        "billCode": {
            required: true
        }
    });
})