//表单类型数组
var billTypeArr = [];

$(function () {
    //获取发票类型
    billTypeArr = getBillType();
    //特殊处理下
    billTypeArr = arrChange(billTypeArr);


    //渲染发票明细
    $("#innerTable").yxeditgrid({
        objName: 'expensedetail[expenseinv]',
        title: '发票明细',
        event: {
            removeRow: function (t, rowNum, rowData) {

            }
        },
        isAddAndDel: false,
        colModel: [{
            display: '发票类型ID',
            name: 'BillTypeID',
            type: 'hidden',
            value: 57
        },{
            display: '发票类型',
            name: 'BillTypeName',
            tclass: 'readOnlyTxtNormal',
            readonly: true,
            value: '税费'
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
        isAddAndDel: false,
        event: {
            removeRow: function (t, rowNum, rowData) {

            }
        },
        colModel: [{
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

    // 自动填充新增的费用类型的发票以及分摊金额
    $("#costMoney_v").blur(function(){
        $("#innerTable_cmp_Amount0_v").val($(this).val());
        $("#innerTable_cmp_Amount0").val($(this).val());

        $("#innerTableCostshare_cmp_CostMoney0_v").val($(this).val());
        $("#innerTableCostshare_cmp_CostMoney0").val($(this).val());
    });
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
        alert('费用类型项存在子类型，不能直接选择。');
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
    if(!checkCanSel(document.getElementById('newCostTypeID'))){
        $("#newCostTypeID").focus();
        return false;
    }

    //金额验证
    var costMoney = $("#costMoney").val();
    if (costMoney * 1 == 0 || costMoney == "") {
        alert('费用金额不能为0或者空');
        $("#costMoney_v").focus();
        return false;
    }

    var maxCostVal = $("#maxCostVal").val();
    if(maxCostVal == 0 && $("#toTakeOutSlt").find('option:selected').val() == ""){
        alert('请选择需要抵充的费用类型。');
        $("#toTakeOutSlt").focus();
        return false;
    }else if (costMoney * 1 >= maxCostVal) {
        alert('费用金额必须小于 '+maxCostVal+'。');
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

    if(!chkToTakeOutInfo()){
        return false;
    }

    return confirm('提交后将立即生效,确认提交吗？');
}

var sltToTakeOutCostType = function(){
    // 数据初始化
    $("#HeadID").val("");
    $("#AssID").val("");
    $("#costCanAdd").text(moneyFormat2(0));
    $("#toTakeOutID").val("");
    $("#maxCostVal").val(0);
    $("#toTakeOut_innerTable").html("");
    $("#toTakeOut_innerTableCostshare").html("");

    // 读取选中项的数据
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

// 显示选中的抵充费用项的发票以及分摊明细
var toShowTakeOutCostTypeInfo = function(){
    $("#toTakeOutCostTypeInfoTr").show();
    var sltedObj = $("#toTakeOutSlt").find('option:selected');
    var toTakeOutTypeId = sltedObj.val();
    var CostTypeID = sltedObj.attr("costtypeid");
    var BillNo = $("#BillNo").val();
    //渲染发票明细
    $("#toTakeOut_innerTable").yxeditgrid({
        objName: 'expensedetail[toTakeOut_expenseinv]',
        title: '发票明细',
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
            display: '发票类型',
            name: 'BillType',
            type: 'statictext'
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
            type: 'statictext'
        }]
    });

    //渲染分摊明细
    $("#toTakeOut_innerTableCostshare").yxeditgrid({
        objName: 'expensedetail[toTakeOut_expensecostshare]',
        title: '分摊明细',
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
            display: '所属板块',
            name: 'moduleName',
            type: 'statictext'
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
};

// 检查抵充费用项的发票以及分摊总额, 与抵充后的金额是否一致
var chkToTakeOutInfo = function(){
    var sltedObj = $("#toTakeOutSlt").find('option:selected');
    var toTakeOutTypeCost = sltedObj.attr("amount");
    var costMoneyLess = accSub(toTakeOutTypeCost, $("#costMoney").val(), 2);//  抵充后剩余费用金额
    var invMoney = costMoneyLess,shareMoney = costMoneyLess;

    //从表对象-发票明细
    $("#toTakeOut_innerTable").yxeditgrid("getCmpByCol", "Amount").each(function (i, n) {
        //计算当前单价
        invMoney = accSub(invMoney, this.value, 2);
    });
    //如果金额不为0，则单据金额不一致
    if (invMoney != 0) {
        alert('抵充费用的发票金额与抵充后金额 '+costMoneyLess+' 不一致');
        return false;
    }

    //从表对象-分摊明细
    var costMoneyLess = accSub(toTakeOutTypeCost, $("#costMoney").val(), 2);//  抵充后剩余费用金额
    $("#toTakeOut_innerTableCostshare").yxeditgrid("getCmpByCol", "CostMoney").each(function (i, n) {
        //计算当前单价
        shareMoney = accSub(shareMoney, this.value, 2);
    });
    console.log(shareMoney);
    //如果金额不为0，则单据金额不一致
    if (shareMoney != 0) {
        alert('抵充费用的分摊金额与抵充后金额 '+costMoneyLess+' 不一致');
        return false;
    }

    return true;
}