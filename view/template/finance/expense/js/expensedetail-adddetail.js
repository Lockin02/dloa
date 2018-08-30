//����������
var billTypeArr = [];

$(function () {
    //��ȡ��Ʊ����
    billTypeArr = getBillType();
    //���⴦����
    billTypeArr = arrChange(billTypeArr);


    //��Ⱦ��Ʊ��ϸ
    $("#innerTable").yxeditgrid({
        objName: 'expensedetail[expenseinv]',
        title: '��Ʊ��ϸ',
        event: {
            removeRow: function (t, rowNum, rowData) {

            }
        },
        isAddAndDel: false,
        colModel: [{
            display: '��Ʊ����ID',
            name: 'BillTypeID',
            type: 'hidden',
            value: 57
        },{
            display: '��Ʊ����',
            name: 'BillTypeName',
            tclass: 'readOnlyTxtNormal',
            readonly: true,
            value: '˰��'
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
        isAddAndDel: false,
        event: {
            removeRow: function (t, rowNum, rowData) {

            }
        },
        colModel: [{
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

    // �Զ���������ķ������͵ķ�Ʊ�Լ���̯���
    $("#costMoney_v").blur(function(){
        $("#innerTable_cmp_Amount0_v").val($(this).val());
        $("#innerTable_cmp_Amount0").val($(this).val());

        $("#innerTableCostshare_cmp_CostMoney0_v").val($(this).val());
        $("#innerTableCostshare_cmp_CostMoney0").val($(this).val());
    });
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
        alert('������������������ͣ�����ֱ��ѡ��');
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
    if(!checkCanSel(document.getElementById('newCostTypeID'))){
        $("#newCostTypeID").focus();
        return false;
    }

    //�����֤
    var costMoney = $("#costMoney").val();
    if (costMoney * 1 == 0 || costMoney == "") {
        alert('���ý���Ϊ0���߿�');
        $("#costMoney_v").focus();
        return false;
    }

    var maxCostVal = $("#maxCostVal").val();
    if(maxCostVal == 0 && $("#toTakeOutSlt").find('option:selected').val() == ""){
        alert('��ѡ����Ҫ�ֳ�ķ������͡�');
        $("#toTakeOutSlt").focus();
        return false;
    }else if (costMoney * 1 >= maxCostVal) {
        alert('���ý�����С�� '+maxCostVal+'��');
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

    if(!chkToTakeOutInfo()){
        return false;
    }

    return confirm('�ύ��������Ч,ȷ���ύ��');
}

var sltToTakeOutCostType = function(){
    // ���ݳ�ʼ��
    $("#HeadID").val("");
    $("#AssID").val("");
    $("#costCanAdd").text(moneyFormat2(0));
    $("#toTakeOutID").val("");
    $("#maxCostVal").val(0);
    $("#toTakeOut_innerTable").html("");
    $("#toTakeOut_innerTableCostshare").html("");

    // ��ȡѡ���������
    var sltedObj = $("#toTakeOutSlt").find('option:selected');
    var toTakeOutTypeId = sltedObj.val();
    var maxCost = sltedObj.attr("amount");
    var headid = sltedObj.attr("headid");
    var assid = sltedObj.attr("assid");
    if(toTakeOutTypeId != ''){
        $("#toTakeOutID").val(toTakeOutTypeId);
        $("#maxCostVal").val(maxCost);
        $("#costCanAdd").text(moneyFormat2(maxCost));
        $("#HeadID").val(headid);
        $("#AssID").val(assid);


        toShowTakeOutCostTypeInfo();
    }else{
        $("#toTakeOutCostTypeInfoTr").hide();
    }
}

// ��ʾѡ�еĵֳ������ķ�Ʊ�Լ���̯��ϸ
var toShowTakeOutCostTypeInfo = function(){
    $("#toTakeOutCostTypeInfoTr").show();
    var sltedObj = $("#toTakeOutSlt").find('option:selected');
    var toTakeOutTypeId = sltedObj.val();
    var CostTypeID = sltedObj.attr("costtypeid");
    var BillNo = $("#BillNo").val();
    //��Ⱦ��Ʊ��ϸ
    $("#toTakeOut_innerTable").yxeditgrid({
        objName: 'expensedetail[toTakeOut_expenseinv]',
        title: '��Ʊ��ϸ',
        tableClass: 'form_in_table',
        url: '?model=finance_expense_expenseinv&action=listJson',
        param: {'BillDetailIDArr': toTakeOutTypeId},
        isAddAndDel: false,
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
            name: 'BillType',
            type: 'statictext'
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
            type: 'statictext'
        }]
    });

    //��Ⱦ��̯��ϸ
    $("#toTakeOut_innerTableCostshare").yxeditgrid({
        objName: 'expensedetail[toTakeOut_expensecostshare]',
        title: '��̯��ϸ',
        tableClass: 'form_in_table',
        url: '?model=finance_expense_expensecostshare&action=listJson',
        param: {
            'BillNo': BillNo,
            'CostTypeID': CostTypeID
        },
        isAddAndDel: false,
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
            name: 'moduleName',
            type: 'statictext'
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
};

// ���ֳ������ķ�Ʊ�Լ���̯�ܶ�, ��ֳ��Ľ���Ƿ�һ��
var chkToTakeOutInfo = function(){
    var sltedObj = $("#toTakeOutSlt").find('option:selected');
    var toTakeOutTypeCost = sltedObj.attr("amount");
    var costMoneyLess = accSub(toTakeOutTypeCost, $("#costMoney").val(), 2);//  �ֳ��ʣ����ý��
    var invMoney = costMoneyLess,shareMoney = costMoneyLess;

    //�ӱ����-��Ʊ��ϸ
    $("#toTakeOut_innerTable").yxeditgrid("getCmpByCol", "Amount").each(function (i, n) {
        //���㵱ǰ����
        invMoney = accSub(invMoney, this.value, 2);
    });
    //�����Ϊ0���򵥾ݽ�һ��
    if (invMoney != 0) {
        alert('�ֳ���õķ�Ʊ�����ֳ���� '+costMoneyLess+' ��һ��');
        return false;
    }

    //�ӱ����-��̯��ϸ
    var costMoneyLess = accSub(toTakeOutTypeCost, $("#costMoney").val(), 2);//  �ֳ��ʣ����ý��
    $("#toTakeOut_innerTableCostshare").yxeditgrid("getCmpByCol", "CostMoney").each(function (i, n) {
        //���㵱ǰ����
        shareMoney = accSub(shareMoney, this.value, 2);
    });
    console.log(shareMoney);
    //�����Ϊ0���򵥾ݽ�һ��
    if (shareMoney != 0) {
        alert('�ֳ���õķ�̯�����ֳ���� '+costMoneyLess+' ��һ��');
        return false;
    }

    return true;
}