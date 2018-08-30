//表单类型数组
var billTypeArr = [];

$(function () {
    //获取发票类型
    billTypeArr = getBillType();
    //特殊处理下
    billTypeArr = arrChange(billTypeArr);

    var id = $("#ID").val();
    var idArr = id.split(',');
    var isAdd = true;
    if (idArr.length > 1) {
        isAdd = false;
    }

    //渲染发票明细
    $("#innerTable").yxeditgrid({
        objName: 'expensedetail[expenseinv]',
        title: '发票明细',
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
            display: '发票类型',
            name: 'BillTypeID',
            validation: {
                required: true
            },
            tclass: 'select',
            type: 'select',
            options: billTypeArr
        }, {
            display: '发票金额',
            name: 'Amount',
            validation: {
                required: true
            },
            type: 'money',
            tclass: 'txt'
        }, {
            display: '发票数量',
            name: 'invoiceNumber',
            tclass: 'txt',
            value: 1
        }]
    })
    
    //渲染分摊明细
    $("#innerTableCostshare").yxeditgrid({
        objName: 'expensedetail[expensecostshare]',
        title: '分摊明细',
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
            display: '所属板块',
            name: 'module',
            validation: {
                required: true
            },
            tclass: 'select',
            type: 'select',
            datacode: 'HTBK'
        }, {
            display: '分摊金额',
            name: 'CostMoney',
            validation: {
                required: true
            },
            type: 'money',
            tclass: 'txt'
        }]
    })
});

//转换一下数据
function arrChange(billTypeArr) {
    var newArr = [];
    var innerArr;
    for (var i = 0; i < billTypeArr.length; i++) {
        innerArr = {"name": billTypeArr[i].name, "value": billTypeArr[i].id};
        newArr.push(innerArr);
    }
    return newArr;
}

//判断所选类型是否可行
function checkCanSel(thisObj) {
    var newCostObj = $("#newCostTypeID");
    var childrenObjs = newCostObj.find("option[parentId='" + thisObj.value + "']");
    if (childrenObjs.length > 0) {
        alert('该项存在子类型，不能进行选择');
        var costTypeId = $("#CostTypeID").val();
        newCostObj.val(costTypeId);
        return false;
    } else {
        //父类行赋值
        var newMainTypeId = newCostObj.find("option:selected").attr("parentId");
        var newMainType = newCostObj.find("option:selected").attr("parentName");
        var costType = newCostObj.find("option:selected").attr("title");
        $("#mainType").val(newMainTypeId);
        $("#mainTypeName").val(newMainType);
        $("#CostType").val(costType);
    }
    return true;
}


//保存发票类型变更
function checkform() {
    //金额验证
    var costMoney = $("#costMoney").val();
    if (costMoney * 1 == 0 || costMoney == "") {
        alert('费用金额不能为0或者空');
        return false;
    }

    //从表对象-发票明细
    $("#innerTable").yxeditgrid("getCmpByCol", "Amount").each(function (i, n) {
        //计算当前单价
        costMoney = accSub(costMoney, this.value, 2);
    });
    //如果金额不为0，则单据金额不一致
    if (costMoney != 0) {
        alert('费用金额与发票金额不一致');
        return false;
    }
    
    //从表对象-分摊明细
    var costMoney = $("#costMoney").val();
    $("#innerTableCostshare").yxeditgrid("getCmpByCol", "CostMoney").each(function (i, n) {
        //计算当前单价
        costMoney = accSub(costMoney, this.value, 2);
    });
    //如果金额不为0，则单据金额不一致
    if (costMoney != 0) {
        alert('费用金额与分摊金额不一致');
        return false;
    }

    return confirm('确认修改吗？');
}