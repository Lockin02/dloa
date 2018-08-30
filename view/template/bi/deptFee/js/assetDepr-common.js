// 表单验证
var checkSubmit = function() {
    try {
        if ($("#business").val() == "") {
            alert("需要填写事业部");
            return false;
        }
        var deprMoney = $("#deprMoney").val();
        if (deprMoney == "" || deprMoney == "0") {
            alert("需要填写财务折旧金额并且金额不能为0");
            return false;
        }

        // 分摊明细
        var shareMoney = 0;
        var projectDepr = 0;

        // 明细验证
        var girdObj = $("#grid");
        girdObj.yxeditgrid('getCmpByCol', 'feeIn').each(function () {
            shareMoney = accAdd(shareMoney, $(this).val(), 2);
        });
        girdObj.yxeditgrid('getCmpByCol', 'projectDepr').each(function () {
            projectDepr = accAdd(projectDepr, $(this).val(), 2);
        });

        if (shareMoney == 0) {
            alert("承担金额为0，请重新分配承担金额");
            return false;
        }

        var shareAll = accSub(deprMoney, projectDepr, 2);
        var diff = accSub(shareAll, shareMoney, 2);
        if (diff != 0) {
            alert("承担差额【" + shareMoney + "】与财务折旧金额【" + shareAll + "】不等，差额为：" + diff +
                "，请调整差额分配或者重新加载工程折旧");
            return false;
        }
    } catch (e) {
        console.log(e);
        alert('表单验证异常');
        return false;
    }
    return true;
};