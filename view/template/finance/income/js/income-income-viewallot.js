var defaultCurrency = '�����'; // Ĭ�ϻ���
$(function () {
    // ��ȡ����
    var currency = $("#currency").val();

    // �������
    initAllot(currency);

    //�ؿ����
    initIncomeCheck();

    // ������ʾ
    if ($("#currency").val() != defaultCurrency) {
        $("#currencyInfo").show();
    }
});

// ���������ϸ
function initAllot(currency) {
    $("#allotTable").yxeditgrid({
        url: '?model=finance_income_incomeAllot&action=listJson',
        objName: 'income[incomeAllots]',
        title: '�������',
        param: {'incomeId': $("#id").val()},
        tableClass: 'form_in_table',
        type: 'view',
        event: {
            reloadData: function (e, g, data) {
                if (!data) {
                    $("#allotTable").find('tbody').append('<tr class="tr_odd"><td colspan="6">-- ���޷������� --</td></tr>');
                }
            }
        },
        colModel: [{
            display: 'Դ������',
            name: 'objType',
            datacode: 'KPRK'
        }, {
            display: 'Դ�����',
            name: 'objCode'
        }, {
            display: '��������',
            name: 'areaName',
            readonly: true,
            width: 100
        }, {
            display: '������',
            name: 'money',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '������(' + currency + ')',
            name: 'moneyCurrency',
            type: currency == defaultCurrency ? 'hidden' : 'money',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: 'ҵ����',
            name: 'rObjCode'
        }, {
            display: '��������',
            name: 'allotDate'
        }]
    });
}

//��Ⱦ��������ӱ�
function initIncomeCheck() {
    var objGrid = $("#checkTable");
    objGrid.yxeditgrid({
        url: '?model=finance_income_incomecheck&action=listJson',
        objName: 'income[incomeCheck]',
        title: '�ؿ����',
        param: {incomeId: $("#id").val(), incomeType: 0},
        tableClass: 'form_in_table',
        type: 'view',
        event: {
            'reloadData': function (e, g, data) {
                if (!data) {
                    objGrid.find("tbody").html("<tr><td colspan='6'>û����ϸ��Ϣ</td></tr>");
                }
            }
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '��ͬid',
            name: 'contractId',
            type: 'hidden'
        }, {
            display: '��ͬ����',
            name: 'contractName',
            type: 'hidden'
        }, {
            display: '��ͬ���',
            name: 'contractCode',
            tclass: 'readOnlyTxtNormal',
            readonly: true
        }, {
            display: '��������id',
            name: 'payConId',
            type: 'hidden'
        }, {
            display: '��������',
            name: 'payConName',
            tclass: 'readOnlyTxtMiddle',
            readonly: true
        }, {
            display: '���κ������',
            name: 'checkMoney',
            tclass: 'txtmiddle',
            type: 'money',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '��������',
            name: 'checkDate',
            tclass: 'txtmiddle Wdate',
            type: 'date'
        }, {
            display: '��ע',
            name: 'remark',
            tclass: 'txt'
        }]
    });
}