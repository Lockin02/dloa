

//计算人力预算
function calPerson(){
	//获取数量
	var feeDay = $("#feeDay").val();
	if($("#memberName").val() != "" && feeDay != ""){
		//获取计量系数
		var coefficient = $("#coefficient").val();
		//获取单价
		var price = $("#price").val();
		//计算人工天数
		setMoney('feeDay',feeDay,2);

		//计算人工天数
		var feePeople = accMul(coefficient,feeDay,2);
		setMoney('feePeople',feePeople,2);
		//计算人工天数
		var feePerson = accMul(price,feeDay,2);
		setMoney('feePerson',feePerson,2);
	}
}

//启动与结束关闭差验证
function timeCheck($t){
	var startDate = $('#beginDate').val();
	var endDate = $('#endDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
	if(s < 0) {
		alert("开始时间不能比结束时间晚！");
		$t.value = "";
		return false;
	}
	setMoney('feeDay',s,2);
}
