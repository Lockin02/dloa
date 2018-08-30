

//计算设备金额
function calResource(){
	//获取数量
	var number = $("#number").val();
	//获取单价
	var price = $("#price").val();
	//获取天数
	var useDays = $("#useDays").val();
	if( number != "" && price != "" && useDays != "" ){
		//计算单天设备金额
		var amount = accMul(number,price,2);
		//计算多天设备金额
		var amount = accMul(amount,useDays,2);

		setMoney('amount',amount,2);
	}
}

//计算设备金额 - 批量新增 - 复制 功能中使用
function calResourceBatch(rowNum){
	//从表前置字符串
	var beforeStr = "importTable_cmp";
	//获取当前数量
	var number= $("#"+ beforeStr + "_number" + rowNum ).val();

	if($("#" + beforeStr + "_resourceName"  + rowNum ).val() != "" && number != ""){
		//获取单价
		var price = $("#" + beforeStr +  "_price" + rowNum + "_v").val();
		//获取天数
		var useDays = $("#" + beforeStr +  "_useDays" + rowNum ).val();
		//计算单天设备金额
		var amount = accMul(number,price,2);

		//计算多天设备金额
		var amount = accMul(useDays,amount,2);

		setMoney(beforeStr +  "_amount" +  rowNum,amount,2);
	}
}

/**
 * 预计借出和预计归还日期差验证，使用天数的计算
 * @param {} $t
 * @return {Boolean}
 */
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
	$("#useDays").val(s);
}