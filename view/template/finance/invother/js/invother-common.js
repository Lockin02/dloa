$(function () {
    $("#innerTable").width(document.documentElement.clientWidth - 10);

    //渲染部门和业务员信息
    if ($("#salesmanId").length != 0) {
        $("#salesman").yxselect_user({
            hiddenId: 'salesmanId',
            isGetDept: [true, "departmentsId", "departments"]
        });
        $("#departments").yxselect_dept({
            hiddenId: 'departmentsId'
        });
    }

    //根据id判断调用税额选择函数
    if ($("#id").length == 1) {
//		changeTaxRate('invType');
        // 红字渲染
        if ($("#isRed").val() == "1") {
            $("#formTitle").attr('style','color:red');
        }
    } else {
        changeTaxRateWithOutCount('invType');

        // 新增页面根据红字权限显示单据类型选择
        if (redLimit == "1") {
            $('#redSpan').show();
        }
    }

    /**
     * 验证信息
     */
    validate({
        "supplierName": {
            required: true
        },
        "invoiceNo": {
            required: true
        },
        "formDate": {
            required: true
        },
        "salesman": {
            required: true
        },
        "departments": {
            required: true
        },
        "businessBelongName": {
            required: true
        }
    });

    if ($("#TO_NAME").length != 0) {
        $("#TO_NAME").yxselect_user({
            hiddenId: 'TO_ID',
            mode: 'check'
        });
    }
});

// 红蓝字切换时的标题渲染
function changeTitle(thisVal){
    if(thisVal == '1'){
        $("#formTitle").attr('style','color:red');
    }else{
        $("#formTitle").attr('style','');
    }
}

//改变单据税额 - 不包含从表计算方法
function changeTaxRateWithOutCount(thisVal) {
    var taxRateObj = $("#taxRate");
    var taxRate = 0;
    if ($("#" + thisVal).find("option:selected").attr("e1") != "") {
        taxRate = $("#" + thisVal).find("option:selected").attr("e1");
    }
    taxRateObj.val(taxRate);
}

//改变单据税额 - 包含从表计算方法
function changeTaxRate(thisVal) {
    var taxRateObj = $("#taxRate");
    var taxRate = 0;
    if ($("#" + thisVal).find("option:selected").attr("e1") != "") {
        taxRate = $("#" + thisVal).find("option:selected").attr("e1");
    }
    taxRateObj.val(taxRate);
    countAll2();
}

//计算单据金额
function countAll(rowNum, thisId) {
    //计算本行
    countThisRow(rowNum, thisId);
    //计算表单
    countForm();
    countFinalAmount();
}

//行内计算 - 不包含表单金额计算
function countThisRow(rowNum, thisId) {
    //从表前置字符串
    var beforeStr = "innerTable_cmp_";
    //单价
    var price = 0;
    //含税单价
    var taxPrice = 0;
    //税率
    var taxRate = $("#taxRate").val();
    //本行金额
    var thisAmount = 0;
    //本行价税合计
    var thisAllCount = 0;

    //获取当前数量
    var number = $("#" + beforeStr + "number" + rowNum).val();

    if ($("#" + beforeStr + "price" + rowNum + "_v").val() == "" && $("#" + beforeStr + "taxPrice" + rowNum + "_v").val() == "") {
        return false;
    }

    if (thisId == 'price') {
        //获取当前单价
        price = $("#" + beforeStr + "price" + rowNum + "_v").val();

        //计算本行金额 - 不含税
        thisAmount = accMul(price, number, 2);
        setMoney(beforeStr + "amount" + rowNum, thisAmount);

        //计算含税单价
        taxPrice = accMul(price, accAdd(1, accDiv(taxRate, 100, 2), 2), 2);
        setMoney(beforeStr + "taxPrice" + rowNum, taxPrice);

        //计算价税合计
        thisAllCount = accMul(taxPrice, number, 2);
        setMoney(beforeStr + "allCount" + rowNum, thisAllCount);

        setMoney(beforeStr + "assessment" + rowNum, accSub(thisAllCount, thisAmount, 2));

    } else if (thisId == 'taxPrice') {
        //获取当前单价
        taxPrice = $("#" + beforeStr + "taxPrice" + rowNum + "_v").val();

        //计算价税合计
        thisAllCount = accMul(taxPrice, number, 2);
        setMoney(beforeStr + "allCount" + rowNum, thisAllCount);

        //计算不含税单价
        price = accDiv(taxPrice, accAdd(1, accDiv(taxRate, 100, 2), 2), 2);
        setMoney(beforeStr + "price" + rowNum, price);

        //计算本行金额 - 不含税
        thisAmount = accMul(price, number, 2);
        setMoney(beforeStr + "amount" + rowNum, thisAmount);

        setMoney(beforeStr + "assessment" + rowNum, accSub(thisAllCount, thisAmount, 2));

    } else {
        //获取当前单价
        taxPrice = $("#" + beforeStr + "taxPrice" + rowNum + "_v").val();

        //计算价税合计
        thisAllCount = accMul(taxPrice, number, 2);
        setMoney(beforeStr + "allCount" + rowNum, thisAllCount);

        //计算不含税单价
        price = accDiv(taxPrice, accAdd(1, accDiv(taxRate, 100, 2), 2), 2);
        setMoney(beforeStr + "price" + rowNum, price);

        //计算本行金额 - 不含税
        thisAmount = accMul(price, number, 2);
        setMoney(beforeStr + "amount" + rowNum, thisAmount);

        setMoney(beforeStr + "assessment" + rowNum, accSub(thisAllCount, thisAmount, 2));
    }
}

//只计算单据金额
function countForm() {
    //从表对象
    var thisGrid = $("#innerTable");
    //计算单据金额 - 不含税
    var allAmount = 0;
    thisGrid.yxeditgrid("getCmpByCol", "amount").each(function (i, n) {
        allAmount = accAdd(allAmount, $(this).val(), 2);
    });

    setMoney('allAmount', allAmount);
    $("#listAmount").val(moneyFormat2(allAmount));

    //计算单据价税合计
    var allCount = 0;
    thisGrid.yxeditgrid("getCmpByCol", "allCount").each(function () {
        allCount = accAdd(allCount, $(this).val(), 2);
    });

    setMoney('formCount', allCount);
    $("#listCount").val(moneyFormat2(allCount));

    //计算单据税额
    var allAssessment = 0;
    thisGrid.yxeditgrid("getCmpByCol", "assessment").each(function () {
        allAssessment = accAdd(allAssessment, $(this).val(), 2);
    });

    setMoney('formAssessment', allAssessment);
    $("#listAssessment").val(moneyFormat2(allAssessment));

    //计算单据税额
    var allNumber = 0;
    thisGrid.yxeditgrid("getCmpByCol", "number").each(function () {
        allNumber = accAdd(allNumber, $(this).val(), 2);
    });
    setMoney('formNumber', allNumber);
    $("#listNumber").val(allNumber);
    countFinalAmount();
    return true;
}

//计算单据金额
function countAll2() {
    //从表前置字符串
    var beforeStr = "innerTable_cmp_";
    //从表对象
    var thisGrid = $("#innerTable");

    //不含税金额
    var thisPrice = 0;

    //税率
    var taxRate = $("#taxRate").val();

    //计算单据金额 - 不含税
    thisGrid.yxeditgrid("getCmpByCol", "taxPrice").each(function (i, n) {
        //计算当前单价
        thisPrice = accDiv($(this).val(), accAdd(1, accDiv(taxRate, 100, 2), 2), 2);

        setMoney(beforeStr + "price" + i, thisPrice);

        $("#" + beforeStr + "taxPrice" + i + "_v").trigger('blur');
    });

    countForm();
}

//初始化数量和单价
function initNumAndPrice(rowNum, thisType) {
    //从表前置字符串
    var beforeStr = "innerTable_cmp_";

    //本行数量
    var thisNumObj = $("#" + beforeStr + "number" + rowNum);
    //本行单价
    var thisPriceObj = $("#" + beforeStr + "price" + rowNum + "_v");
    var thisTaxPriceObj = $("#" + beforeStr + "taxPrice" + rowNum + "_v");
    //本行金额
    var thisAmountObj = $("#" + beforeStr + "amount" + rowNum + "_v");
    var thisCountObj = $("#" + beforeStr + "allCount" + rowNum + "_v");

    if ((thisNumObj.val() == "" || thisNumObj.val() * 1 == 1) && thisType == 'allCount') {
        thisNumObj.val(1);
        thisTaxPriceObj.val(thisCountObj.val());

        thisTaxPriceObj.trigger('blur');
    } else if ((thisNumObj.val() == "" || thisNumObj.val() * 1 == 1) && thisType == 'amount') {
        thisNumObj.val(1);
        thisPriceObj.val(thisAmountObj.val());

        thisPriceObj.trigger('blur');
    }
}

//计算金额和税额合计
function countAccount(rowNum) {
    //从表前置字符串
    var beforeStr = "innerTable_cmp_";

    //获取金额
    var amount = $("#" + beforeStr + "amount" + rowNum + "_v").val();
    //获取税额
    var assessment = $("#" + beforeStr + "assessment" + rowNum + "_v").val();
    //计算后的合计值
    var allCount = accAdd(amount, assessment, 2);

    setMoney(beforeStr + "allCount" + rowNum, allCount);
}


function audit(thisType) {
    document.getElementById('form1').action = "?model=finance_invother_invother&action=add&act=" + thisType;
}

function auditEdit(thisType) {
    document.getElementById('form1').action = "?model=finance_invother_invother&action=edit&act=" + thisType;
}

//初始化合计栏
function initListCount() {
    $("#innerTable").find('tbody').after("<tr class='tr_count'><td colspan='3'>合计</td>"
    + "<td><input type='text' class='readOnlyTxtShortCount' id='listNumber' value='"
    + "' readonly='readonly'/></td>"
    + "<td colspan='2'></td>"
    + "<td><input type='text' class='readOnlyTxtMiddleCount' id='listAmount' value='"
    + "' readonly='readonly'/></td>"
    + "<td><input type='text' class='readOnlyTxtMiddleCount' id='listAssessment' value='"
    + "' readonly='readonly'/></td>"
    + "<td><input type='text' class='readOnlyTxtMiddleCount' id='listCount' value='"
    + "' readonly='readonly'/></td>"
    + "<td></td></tr>");
}

//加入一个表单验证
function checkform() {
    // 费用分摊
    if ($("#isShare").val() == "1") {
        // 红字的时候转换金额政府
        var formMoney = $("#" + getMoneyKey()).val();

        if ($("#id").length == 0) {
            if ($("input:radio[name='invother[isRed]']:checked").val() == "1") {
                formMoney = -formMoney;
            }
        } else {
            if ($("#isRed").val() == "1") {
                formMoney = -formMoney;
            }
        }

        //显示费用分摊明细
        if ($("#shareGrid").costShareGrid('checkForm', formMoney) == false) {
            return false;
        }
    }

    if ($("#sourceType").val() == 'YFQTYD03' && $("#period").val() == "") {
        alert('请选择归属月份');
        return false;
    }
    return true;
}

// 获取发票类型对应的字段
function getMoneyKey() {
    var invoiceTypeE3 = $("#invType").find("option:selected").attr("e3");
    return invoiceTypeE3 && invoiceTypeE3 == "1" ? 'allAmount' : 'formCount';
}

// 发票类型改变时触发事件 -- 此方法通过jquery绑定，可以直接用$(this)
var invoiceTypeChange = function () {
    $("#shareGrid").costShareGrid('changeCountKey', getMoneyKey());
};

//计算决算金额(租车业务)
function countFinalAmount(){
    if($("#finalAmount").length>0){
        var invType=$("#invType").val();
        if(invType=="ZZSZYFP13"||invType=="ZZSZYFP6"){//为增值税专用发票取的成本金额为总金额
            $("#finalAmount_v").val( $("#allAmount_v").val());
            $("#finalAmount").val( $("#allAmount").val());
        }else{//非专用发票取的是价税合计
            $("#finalAmount_v").val( $("#formCount_v").val());
            $("#finalAmount").val( $("#formCount").val());
        }
    }
}