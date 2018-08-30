$(document).ready(function () {
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
                    "<td style='text-align:right;'>" +
                    moneyFormat2($("#unitPrice").val()) +
                    "</td>" +
                        "<td style='text-align:right;'>" +
                    moneyFormat2($("#price").val()) +
                    "</td>" +
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
        },  {
            display: '����',
            name: 'unitPrice',
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

    //��ʾ���÷�̯��ϸ
    $("#costshareGrid").costshareGrid({
        objName: 'compensate[costshare]',
        url: "?model=finance_cost_costshare&action=listjson",
        param: {'objType': 1, 'objId': $("#id").val()},
        type: 'view',
        event: {
            'reloadData': function (e, g, data) {
                if (!data) {
                    $("#costshareGrid").hide();
                }
            }
        }
    });

    //��ʾ�ʼ����
    $("#showQualityReport").showQualityDetail({
        param: {
            "objId": $("#relDocId").val(),
            "objType": $("#qualityObjType").val()
        }
    });
});