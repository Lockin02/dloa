$(function() {
	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			mode : 'check',
			hiddenId : 'TO_ID',
			formCode : 'income'
		});
	}

	if($("#ADDIDS").length!=0){
		$("#ADDNAMES").yxselect_user({
			mode : 'check',
			hiddenId : 'ADDIDS',
			formCode : 'income'
		});
	}

    // 初始化设置
    if($("#id").length > 0){
        var currency = $("#currency").val();
        if(currency != '人民币'){
            $("#currencyInfo").show();
            $("#currencyShow").text(currency);
            $("#incomeMoney_v").removeClass('txt').addClass('readOnlyTxtNormal');
        }else{
            $("#currencyInfo").hide();
            $("#incomeMoney_v").addClass('txt').removeClass('readOnlyTxtNormal');
        }
    }
});

// 设置到款金额（本币）
function changeIncomeMoney(){
    var incomeMoney = accMul($("#incomeCurrency").val(),$("#rate").val(),2);
    setMoney('incomeMoney',incomeMoney,2);
}

// 设置到款金额
function changeincomeCurrency(){
    var incomeCurrency = accDiv($("#incomeMoney").val(),$("#rate").val(),2);
    setMoney('incomeCurrency',incomeCurrency,2);
}

//表单验证
function checkform(){
	if($("#incomeUnitId").val() == ""){
		alert("请通过下拉表格正确选择到款单位");
		return false;
	}
	if($("#incomeMoney").val() == "" || $("#incomeMoney").val()*1 == 0 ){
		alert("到款金额不能为0或者空");
		return false;
	}
    if($("#currency").val() == ""){
        alert('请选择到款币别');
        return false;
    }
    if($("#businessBelongName").val() == ""){
        alert('请输入归属公司');
        return false;
    }
    // 两个金额字段设置
    $("#allotAble").val($("#incomeMoney").val());
    $("#allotCurrency").val($("#incomeCurrency").val());
}

/**
 * 检查分配金额与到款金额规则
 */
function toSubmit() {
    if($("#incomeUnitId").val() == ''){
        alert('请选择客户');
        return false;
    }

    if($("#businessBelongName").val() == ''){
        alert('请选择归属公司');
        return false;
    }

    var incomeMoney = $("#incomeMoney").val();
    if(incomeMoney == '' || incomeMoney*1 == 0){
        alert('请输入单据金额');
        return false;
    }

    var isEmpty = false;
    //获取分配从表数据
    var objGrid = $("#allotTable"); // 缓存从表对象
    objGrid.yxeditgrid('getCmpByCol','objId').each(function(){
        if($(this).val() == ''){
            isEmpty = true;
        }
    });

    if(isEmpty == true ){
        alert('关联编号不能为空！');
        return false;
    }

    //获取分配从表数据
    var allotMoney = incomeMoney; // 本币余额
    objGrid.yxeditgrid('getCmpByCol','money').each(function(){
        if($(this).val() != '' && $(this).val()*1 != 0){
            allotMoney = accSub(allotMoney,$(this).val(),2);
        }
    });

    if(allotMoney < 0){
        alert('分配金额不能大于到款金额');
        return false;
    }else{
        $("#allotMoney").val(allotMoney);
    }
}
