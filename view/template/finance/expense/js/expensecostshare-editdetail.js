$(function () { 
    //��Ⱦ��̯��ϸ
    $("#innerTableCostshare").yxeditgrid({
        objName: 'expensecostshare[expensecostshare]',
        title: '��̯��ϸ',
        tableClass: 'form_in_table',
        url: '?model=finance_expense_expensecostshare&action=listJson',
        param: {
        	'BillNo': $("#BillNo").val(),
        	'CostTypeID': $("#CostTypeID").val()
        },
        isAdd: true,
        event: {
            removeRow: function (t, rowNum, rowData) {

            }
        },
        colModel: [{
            display: 'ID',
            name: 'ID',
            type: 'hidden'
        }, {
            display: '�������',
            name: 'module',
            validation: {
                required: true
            },
            tclass: 'select',
            type: 'select',
            datacode: 'HTBK'
        }, {
            display: '��̯���',
            name: 'CostMoney',
            validation: {
                required: true
            },
            type: 'money',
            tclass: 'txt'
        }]
    })
});

//���淢Ʊ���ͱ��
function checkform() {
    //�����֤
    var costMoney = $("#costMoney").val();
    $("#innerTableCostshare").yxeditgrid("getCmpByCol", "CostMoney").each(function (i, n) {
        //���㵱ǰ����
        costMoney = accSub(costMoney, this.value, 2);
    });
    //�����Ϊ0���򵥾ݽ�һ��
    if (costMoney != 0) {
        alert('���ý�����̯��һ��');
        return false;
    }

    return confirm('ȷ���޸���');
}