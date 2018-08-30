$(document).ready(function() {
    //表单验证
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