//提交校验
function checkForm() {

    // 验证申请原因
    if($("#applyTbl").find("input:checkbox[type='checkbox']:checked").length == 0) {
        alert("请勾选申请原因");
        return false;
    }

    var outType = $('input:radio[name="apply[outType]"]:checked').val();
    if (outType == 1 || outType == 2) {//整包或者分包
        // 最高限价
        if ($("#limitPrice_v").val() == "") {
            alert('请填写最高限价');
            return false;
        }
        // 最高限价折扣
        if ($("#limitDiscount").val() == "") {
            alert('请填写最高限价折扣');
            return false;
        }
        //  工作量描述
        if ($("#workloadDescription").val() == "") {
            alert('请填写工作量描述');
            return false;
        }
        // 项目要求
        if ($("#projectRequest").val() == "") {
            alert('请填写项目要求');
            return false;
        }
        // 客户要求
        if ($("#customerRequest").val() == "") {
            alert('请填写客户要求');
            return false;
        }
        $("#wapdiv").html('');
    }
}

// 计算限价
function calculateLimit(type) {
    // 税前金额
    var projectMoneyWithTax = $("#projectMoneyWithTax").val();
    if (projectMoneyWithTax == "") {
        $("#limitDiscount").val("");
        setMoney("limitPrice", "");
    } else {
        var limitPrice;
        var limitDiscount;
        if (type == 0) {
            limitPrice = $("#limitPrice").val();
            limitDiscount = accMul(accDiv(limitPrice, projectMoneyWithTax, 3), 10, 2);
            $("#limitDiscount").val(limitDiscount);
        } else {
            limitDiscount = accDiv($("#limitDiscount").val(), 10, 4);
            limitPrice = accMul(limitDiscount, projectMoneyWithTax, 2);
            setMoney("limitPrice", limitPrice, 2);
        }
    }
}