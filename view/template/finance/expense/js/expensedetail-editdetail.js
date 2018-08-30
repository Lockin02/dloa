//����������
var billTypeArr = [];

$(function () {
    //��ȡ��Ʊ����
    billTypeArr = getBillType();
    //���⴦����
    billTypeArr = arrChange(billTypeArr);

    var id = $("#ID").val();
    var idArr = id.split(',');
    var isAdd = true;
    if (idArr.length > 1) {
        isAdd = false;
    }

    //��Ⱦ��Ʊ��ϸ
    $("#innerTable").yxeditgrid({
        objName: 'expensedetail[expenseinv]',
        title: '��Ʊ��ϸ',
        tableClass: 'form_in_table',
        url: '?model=finance_expense_expenseinv&action=listJson',
        param: {'BillDetailIDArr': id},
        isAdd: isAdd,
        event: {
            removeRow: function (t, rowNum, rowData) {

            }
        },
        colModel: [{
            display: 'ID',
            name: 'ID',
            type: 'hidden'
        }, {
            display: 'BillDetailID',
            name: 'BillDetailID',
            type: 'hidden'
        }, {
            display: '��Ʊ����',
            name: 'BillTypeID',
            validation: {
                required: true
            },
            tclass: 'select',
            type: 'select',
            options: billTypeArr
        }, {
            display: '��Ʊ���',
            name: 'Amount',
            validation: {
                required: true
            },
            type: 'money',
            tclass: 'txt'
        }, {
            display: '��Ʊ����',
            name: 'invoiceNumber',
            tclass: 'txt',
            value: 1
        }]
    })
    
    //��Ⱦ��̯��ϸ
    $("#innerTableCostshare").yxeditgrid({
        objName: 'expensedetail[expensecostshare]',
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

//ת��һ������
function arrChange(billTypeArr) {
    var newArr = [];
    var innerArr;
    for (var i = 0; i < billTypeArr.length; i++) {
        innerArr = {"name": billTypeArr[i].name, "value": billTypeArr[i].id};
        newArr.push(innerArr);
    }
    return newArr;
}

//�ж���ѡ�����Ƿ����
function checkCanSel(thisObj) {
    var newCostObj = $("#newCostTypeID");
    var childrenObjs = newCostObj.find("option[parentId='" + thisObj.value + "']");
    if (childrenObjs.length > 0) {
        alert('������������ͣ����ܽ���ѡ��');
        var costTypeId = $("#CostTypeID").val();
        newCostObj.val(costTypeId);
        return false;
    } else {
        //�����и�ֵ
        var newMainTypeId = newCostObj.find("option:selected").attr("parentId");
        var newMainType = newCostObj.find("option:selected").attr("parentName");
        var costType = newCostObj.find("option:selected").attr("title");
        $("#mainType").val(newMainTypeId);
        $("#mainTypeName").val(newMainType);
        $("#CostType").val(costType);
    }
    return true;
}


//���淢Ʊ���ͱ��
function checkform() {
    //�����֤
    var costMoney = $("#costMoney").val();
    if (costMoney * 1 == 0 || costMoney == "") {
        alert('���ý���Ϊ0���߿�');
        return false;
    }

    //�ӱ����-��Ʊ��ϸ
    $("#innerTable").yxeditgrid("getCmpByCol", "Amount").each(function (i, n) {
        //���㵱ǰ����
        costMoney = accSub(costMoney, this.value, 2);
    });
    //�����Ϊ0���򵥾ݽ�һ��
    if (costMoney != 0) {
        alert('���ý���뷢Ʊ��һ��');
        return false;
    }
    
    //�ӱ����-��̯��ϸ
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