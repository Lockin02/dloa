$(document).ready(function () {
    $("#memberName").yxselect_user({
        hiddenId: 'memberId'
    });
    validate({
        "memberName": {
            required: true
        },
        "beginDate": {
            required: true
        },
        "endDate": {
            required: true
        }
    });
});
