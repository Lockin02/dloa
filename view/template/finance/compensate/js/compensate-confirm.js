$(document).ready(function () {
    //������
    $("#chargerName").yxselect_user({
        hiddenId: 'chargerId',
        isGetDept: [true, "deptId", "deptName"],
        formCode: 'compensate'
    });

    $("#deptName").yxselect_dept({
        hiddenId: 'deptId'
    });
    var detailObj = $("#detail");
    // ��Ʒ�嵥
    detailObj.yxeditgrid({
        objName: 'compensate[detail]',
        url: '?model=finance_compensate_compensatedetail&action=listJson',
        tableClass: 'form_in_table',
        type: 'view',
        title: "�����嵥",
        param: {
            mainId: $("#id").val()
        },
        event: {
            'reloadData': function (e, g, data) {
                if (data.length > 0) {
                    detailObj.find('tbody').after("<tr class='tr_count'>" +
                    "<td></td><td>�ϼ�</td><td colspan='4'></td>" +
                    "<td style='text-align:right;'>" +
                    moneyFormat2($("#formMoney").val()) +
                    "</td>" +
                    "<td></td>" +
                    "<td style='text-align:right;'>" +
                    moneyFormat2($("#compensateMoney").val()) +
                    "</td><td colspan='2'></td>" +
                    "</tr>");
                } else {
                    detailObj.find('tbody').after("<tr class='tr_odd'><td colspan='20'>-- �������� --</td></tr>");
                }
            }
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden',
            isSubmit: true
        }, {
            display: '����Id',
            name: 'productId',
            type: 'hidden'
        }, {
            display: '���ϱ��',
            name: 'productNo',
            width: 80
        }, {
            display: '��������',
            name: 'productName'
        }, {
            display: '����ͺ�',
            name: 'productModel'
        }, {
            display: '��λ',
            name: 'unitName',
            width: 50
        }, {
            display: '����',
            name: 'number',
            width: 70
        }, {
            display: 'Ԥ��ά�޽��',
            name: 'money',
            width: 70,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '��ֵ',
            name: 'price',
            width: 70,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '�⳥���',
            name: 'compensateMoney',
            width: 70,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '��ע',
            name: 'remark',
            width: 70,
            align: 'left'
        }, {
            display: '���к�',
            name: 'serialNos',
            width: 150,
            align: 'left'
        }]
    });

    //����֤
    validate({
        "formDate": {
            required: true
        },
        "chargerName": {
            required: true
        },
        "deptName": {
            required: true
        },
        "dutyObjName": {
            required: true
        }
    });

    $("#dutyType").change(function () {
        if ($(this).val() == "PCZTLX-01") {
            $("#dutyObjName").val($("#chargerName").val());
            $("#dutyObjId").val($("#chargerId").val());
        } else {
            $("#dutyObjName").val($("#deptName").val());
            $("#dutyObjId").val($("#deptId").val());
        }
    });

    //��ʾ���÷�̯��ϸ
    var costshareGridObj = $("#costshareGrid");
    costshareGridObj.costShareGrid({
        objName: 'compensate[costshare]',
        url: "?model=finance_cost_costshare&action=listjson",
        param: {'objType': 1, 'objId': $("#id").val()}
    });

    //�󶨱���֤
    $("form").submit(function () {
        return costshareGridObj.costShareGrid('checkForm');
    });

    //��ʾ�ʼ����
    $("#showQualityReport").showQualityDetail({
        tableClass: 'form_in_table',
        param: {
            "objId": $("#relDocId").val(),
            "objType": $("#qualityObjType").val()
        }
    });
});

//������
function countMoney(rowNum) {
    var detailObj = $("#detail");
    //��ֵ
    var money = detailObj.yxeditgrid("getCmpByRowAndCol", rowNum, "money").val();
    //����������⳥
    var compensateType = detailObj.yxeditgrid("getCmpByRowAndCol", rowNum, "compensateType").val();//�⳥��ʽ
    var compensateRate = detailObj.yxeditgrid("getCmpByRowAndCol", rowNum, "compensateRate").val();

    var compensateMoney = accDiv(accMul(money, compensateRate, 2), 100, 2);
    detailObj.yxeditgrid("setRowColValue", rowNum, "compensateMoney", compensateMoney, true);
}

//���ݽ����㷽��
function countForm() {
    var detailObj = $("#detail");

    //���㵥�ݽ��
    var moneyArr = detailObj.yxeditgrid("getCmpByCol", "money");
    var formMoney = 0;
    moneyArr.each(function () {
        formMoney = accAdd(formMoney, $(this).val(), 2);
    });
    setMoney('formMoney', formMoney);

    //�����⳥���
    var compensateMoneyArr = detailObj.yxeditgrid("getCmpByCol", "compensateMoney");
    var compensateMoney = 0;
    compensateMoneyArr.each(function () {
        compensateMoney = accAdd(compensateMoney, $(this).val(), 2);
    });
    setMoney('compensateMoney', compensateMoney);
}

//���ύ����
function audit(thisVal) {
    $("#isSubAudit").val(thisVal);
}

// ѡ�����к�
function serialNum(rowNum, serialIds, serialNos, returnequId, number, detailId) {
    showThickboxWin('?model=finance_compensate_compensate&action=toSerialNos'
    + '&relDocId=' + $("#relDocId").val()
    + '&relDocType=' + $("#relDocType").val()
    + '&rowNum=' + rowNum
    + '&serialIds=' + serialIds
    + '&serialNos=' + serialNos
    + '&returnequId=' + returnequId
    + '&number=' + number
    + '&id=' + $("#id").val()
    + '&detailId='
    + "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=350");
}