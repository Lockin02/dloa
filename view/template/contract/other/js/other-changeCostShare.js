//�������֤���� - ��Ϊ��Ŀ���ʱ,��Ҫ��д��Ŀ���
function checkFormChange() {
    if ($("#fundType").val() == 'KXXZB' || $("#fundType").val() == 'KXXZC') {
        //��ʾ���÷�̯��ϸ
        if ($("#shareGrid").costShareGrid('checkForm', $("#" + getMoneyKey('data')).val(), false) == false) {
            return false;
        }

        if($("#isNeedRelativeContract").val() == 1 && $("#hasRelativeContract option:selected").val() == 1 && ($("#relativeContractId").val() <= 0 || $("#relativeContractId").val() == '')){
            alert('������ϸ����Ͷ�����ѡ����������ͬ,��֤������������ͬ�ű���');
            $("#relativeContract").focus();
            return false;
        }
    }
    return confirm('�޸ĺ��������Ч��ȷ���޸���');
}

$(function () {
	$("#shareGridTr").show();
	$('#st').tabs();

    // ���ط�̯�б�
    $("#shareGrid").costShareGrid({
        objName: 'other[costshare]',
        type: 'change',
        url: "?model=finance_cost_costshare&action=listJson",
        param: {objType: 2, objId: $("#id").val()},
        isShowCountRow: true,
		title: '',
        // ��ʼ��ҳ�����������,Ĭ������һ���޸ĵ�ֵ
        countKey: getMoneyKey('data')
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