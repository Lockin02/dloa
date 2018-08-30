//变更表单验证方法 - 当为项目外包时,需要填写项目编号
function checkFormChange() {
    if ($("#fundType").val() == 'KXXZB' || $("#fundType").val() == 'KXXZC') {
        //显示费用分摊明细
        if ($("#shareGrid").costShareGrid('checkForm', $("#" + getMoneyKey('data')).val(), false) == false) {
            return false;
        }

        if($("#isNeedRelativeContract").val() == 1 && $("#hasRelativeContract option:selected").val() == 1 && ($("#relativeContractId").val() <= 0 || $("#relativeContractId").val() == '')){
            alert('费用明细含【投标服务费】的其他类合同,保证金关联其他类合同号必填');
            $("#relativeContract").focus();
            return false;
        }
    }
    return confirm('修改后会立即生效，确认修改吗？');
}

$(function () {
	$("#shareGridTr").show();
	$('#st').tabs();

    // 加载分摊列表
    $("#shareGrid").costShareGrid({
        objName: 'other[costshare]',
        type: 'change',
        url: "?model=finance_cost_costshare&action=listJson",
        param: {objType: 2, objId: $("#id").val()},
        isShowCountRow: true,
		title: '',
        // 初始化页面带出的数据,默认用上一次修改的值
        countKey: getMoneyKey('data')
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

    var isNeedRelativeContract = $("#isNeedRelativeContract").val();
    var hasRelativeContract = $("#hasRelativeContract option:selected").val();
    if(hasRelativeContract == 2){
        $("#relativeContract").val("");
        $("#relativeContractId").val("");
        $(".sltRelativeContractWrap").hide();
    }

    if($("#isNeedRelativeContract").val() != 1){
        $("#isNeedRelativeContractTip").hide();
    }
});