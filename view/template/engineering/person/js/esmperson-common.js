//启动与结束关闭差验证
function timeCheck($t){
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
	if(s < 0) {
		alert("预计开始不能比预计结束时间晚！");
		$t.value = "";
		return false;
	}
	$("#days").val(s);
}




//计算人力预算
function calPerson(){
	//获取数量
	var number = $("#number").val();
	if($("#personLevel").val() != "" && number != ""){
		//获取计量系数
		var coefficient = $("#coefficient").val();
		//获取单价
		var price = $("#price").val();
		//获取天数
		var days = $("#days").val();
		//计算人工天数
		var personDays = accMul(number,days,2);
		$("#personDays").val(personDays);

		//计算人工天数
		var personCostDays = accMul(coefficient,accMul(number,days,2),2);
		$("#personCostDays").val(personCostDays);

		//计算人工天数
		var personCost = accMul(price,personDays,2);
		$("#personCost").val(personCost);
	}
}


//计算人力预算 - 批量新增中使用
function calPersonBatch(rowNum){
	//从表前置字符串
	var beforeStr = "importTable_cmp";
	//获取当前单价
	var number= $("#"+ beforeStr + "_number" + rowNum ).val();

	if($("#" + beforeStr + "_personLevel"  + rowNum ).val() != "" && number != ""){
		//获取计量系数
		var coefficient = $("#" + beforeStr +  "_coefficient" + rowNum).val();
		//获取单价
		var price = $("#" + beforeStr +  "_price" + rowNum).val();
		//获取天数
		var days = $("#" + beforeStr +  "_days" + rowNum ).val();
		//计算人工天数
		var personDays = accMul(number,days,2);
		$("#" + beforeStr +  "_personDays" + rowNum).val(personDays);

		//计算人工天数
		var personCostDays = accMul(coefficient,accMul(number,days,2),2);
		$("#" + beforeStr +  "_personCostDays" +  rowNum).val(personCostDays);

		//计算人工天数
		var personCost = accMul(price,personDays,2);
		setMoney(beforeStr +  "_personCost" +  rowNum,personCost,2);
	}
}