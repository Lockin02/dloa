$(document).ready(function () {

});

//表单验证
function checkform() {
    if ($("input[name='esmproject[status]']:checked").length == 0) {
        alert("请选择需要更新的状态");
        return false;
    }

    if ($.trim($("#remark").val()) == "") {
        alert("请填写备注信息");
        return false;
    }
    return true;
}