//变更表单验证方法 - 当为项目外包时,需要填写项目编号
function checkFormChange() {
    if ($("#fundType").val() == 'KXXZB') {
        //显示费用分摊明细
        if ($("#shareGrid").costShareGrid('checkForm', $("#" + getMoneyKey()).val(), false) == false) {
            return false;
        }
    }
    return confirm('修改后会立即生效，确认修改吗？');
}

$(function () {
    // 加载分摊列表
    $("#shareGrid").costShareGrid({
        objName: 'other[costshare]',
        type: 'change',
        url: "?model=finance_cost_costshare&action=listjson",
        param: {objType: 2, objId: $("#id").val()},
        isShowCountRow: true,
		isShowExcelBtn: true,
        countKey: getMoneyKey()
    });

    // 计算合同金额
    var taxPointObj = $("#taxPoint");
    taxPointObj.bind('blur', calContractMoney);

    // 不含税合同金额
    var moneyNoTaxVObj = $("#moneyNoTax_v");
    moneyNoTaxVObj.bind('blur', moneyChange);

    if ($("#changeType").val() != "") {
        taxPointObj.hide().after(taxPointObj.val());
        $("#taxPointNeed").hide();
        moneyNoTaxVObj.hide().after(moneyNoTaxVObj.val());
        $("#moneyNoTaxNeed").hide();
    }

    // 验证信息
    validate({
        taxPoint: {
            required: true
        },
        moneyNoTax_v: {
            required: true
        },
        invoiceType: {
            required: true
        }
    });

    // 发票事件绑定
    $("#invoiceType").bind("change", invoiceTypeChange);
});