//�������֤���� - ��Ϊ��Ŀ���ʱ,��Ҫ��д��Ŀ���
function checkFormChange() {
    if ($("#fundType").val() == 'KXXZB') {
        //��ʾ���÷�̯��ϸ
        if ($("#shareGrid").costShareGrid('checkForm', $("#" + getMoneyKey()).val(), false) == false) {
            return false;
        }
    }
    return confirm('�޸ĺ��������Ч��ȷ���޸���');
}

$(function () {
    // ���ط�̯�б�
    $("#shareGrid").costShareGrid({
        objName: 'other[costshare]',
        type: 'change',
        url: "?model=finance_cost_costshare&action=listjson",
        param: {objType: 2, objId: $("#id").val()},
        isShowCountRow: true,
		isShowExcelBtn: true,
        countKey: getMoneyKey()
    });

    // �����ͬ���
    var taxPointObj = $("#taxPoint");
    taxPointObj.bind('blur', calContractMoney);

    // ����˰��ͬ���
    var moneyNoTaxVObj = $("#moneyNoTax_v");
    moneyNoTaxVObj.bind('blur', moneyChange);

    if ($("#changeType").val() != "") {
        taxPointObj.hide().after(taxPointObj.val());
        $("#taxPointNeed").hide();
        moneyNoTaxVObj.hide().after(moneyNoTaxVObj.val());
        $("#moneyNoTaxNeed").hide();
    }

    // ��֤��Ϣ
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

    // ��Ʊ�¼���
    $("#invoiceType").bind("change", invoiceTypeChange);
});