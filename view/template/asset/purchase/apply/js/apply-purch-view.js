$(document).ready(function () {
    $("#purchaseProductTable").yxeditgrid({
        objName: 'apply[applyItem]',
        url: '?model=asset_purchase_apply_applyItem&action=purchListJson',
        delTagName: 'isDelTag',
        type: 'view',
        param: {
            applyId: $("#applyId").val(),
            "isDel": '0'
        },
        colModel: [{
            display: 'ȷ����������',
            name: 'inputProductName',
            tclass: 'readOnlyTxtItem',
            width: 200
        }, {
            display: 'ȷ�����ϱ��',
            name: 'productCode',
            tclass: 'readOnlyTxtItem',
            width: 80
        }, {
            display: 'ȷ����������',
            name: 'productName',
            tclass: 'readOnlyTxtItem',
            width: 200
        }, {
            display: '���',
            name: 'pattem',
            tclass: 'readOnlyTxtItem',
            width: 100
        }, {
            display: '��������',
            name: 'applyAmount',
            tclass: 'txtshort',
            width: 70
        }, {
            display: '��Ӧ��',
            name: 'supplierName',
            tclass: 'txtmiddle',
            width: 100
        }, {
            display: '��λ',
            name: 'unitName',
            tclass: 'readOnlyTxtItem',
            width: 60
        }, {
            display: '�ɹ�����',
            name: 'purchAmount',
            tclass: 'txtshort',
            width: 70
        }, {
            display: '����',
            name: 'price',
            tclass: 'txtshort',
            width: 80,
            // type : 'money'
            // �б��ʽ��ǧ��λ
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '���',
            name: 'moneyAll',
            tclass: 'txtshort',
            // �б��ʽ��ǧ��λ
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            display: 'ϣ����������',
            name: 'dateHope',
            type: 'date',
            width: 70
        }, {
            display: '��ע',
            name: 'remark',
            tclass: 'txt',
            width: 120
        }]
    })

    // ���ݲɹ��������ж��Ƿ���ʾ���ֵ��ֶ�
    // alert($("#purchaseType").text());
    if ($("#purchaseType").text() != "�ƻ��� ") {
        $("#hiddenA").hide();
        // $("#hiddenB").hide();
    }

    // ���ݲɹ�����Ϊ���з��ࡱʱ����ʾ�����ֶΣ��ɹ����ࡢ�ش�ר�����ơ�ļ���ʽ���Ŀ�������з���Ŀ��
    // alert($("#purchCategory").text());
    if ($("#purchCategory").text() == "�з���") {
        $("#hiddenC").hide();
    } else {
        $("#hiddenD").hide();
        $("#hiddenE").hide();
    }

});