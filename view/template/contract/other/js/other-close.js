$(document).ready(function() {
    // 验证信息
    validate({
        closeReason: {
            required: true
        }
    });

    // 如果是付款合同，显示金额信息
    var fundType = $("#fundType").val();
    if (fundType == 'KXXZB') {
        $("#showMoney").show();
    }

    var submitBtn = $("#submitBtn");
    submitBtn.bind("click", function() {
        // 在这里处理关闭类型
        if (fundType == 'KXXZB') {
            // 分摊金额
            var costMoney = $("#costMoney").val();

            // 如果含有分摊金额，则进入逻辑处理
            if (costMoney != '' && parseFloat(costMoney) != 0) {
                var orderMoney = $("#orderMoney").val(); // 合同金额
                var hookMoney = $("#hookMoney").val(); // 勾稽金额

                // 如果合同未勾稽完，并且分摊金额不等于勾稽金额，则算是未完成
                if (parseFloat(hookMoney) != parseFloat(orderMoney) && parseFloat(costMoney) != parseFloat(hookMoney)) {
                    alert('合同未正常完成，如果需要关闭，请通知财务审核发票或者自行冲销多余的分摊金额。');
                    return false;
                }
            }
        }

        // 提交表单
        $("form").submit();
    });

    // 判断权限，改变提交按钮的值
    if ($("#closeLimit").val() == "1") {
        submitBtn.val("提交");
    }
});