

//预计开始日期与预计结束日期差验证
function timeCheck($t){
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate);
//	if(s < 0) {
//		alert("预计开始日期不能比预计结束日期晚！");
//		$t.value = "";
//		return false;
//	}
	var actDays = s + 1;
	$("#days").val(actDays);
	//工作量设置
	if($("#workloadUnit").val() == 'GCGZLDW-00'){
		$("#workload").val(actDays);
	};

	//从表数日期设置
	if($("#id").length > 0){
		var thisGrid = $("#activityPersons");
	}else{
		var thisGrid = $("#activityMembers");
	}

	if($t.id == 'planBeginDate'){
		var cmps = thisGrid.yxeditgrid("getCmpByCol", "planBeginDate");
		cmps.each(function(i,n) {
			this.value = startDate;
			detailTimeCheck(i);
		});
	}else{
		var cmps = thisGrid.yxeditgrid("getCmpByCol", "planEndDate");
		cmps.each(function(i,n) {
			this.value = endDate;
			detailTimeCheck(i);
		});
	}
}

//实际日期计算
function actTimeCheck($t){
	var startDate = $('#actBeginDate').val();
	var endDate = $('#actEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate);
	if(s < 0) {
		alert("实际开始日期不能比实际结束日期晚！");
		$t.value = "";
		return false;
	}
	var actDays = s + 1;
	$("#actDays").val(actDays);
	$("#workedDays").val(actDays);

	var days = $("#days").val()*1;
	var needDays = 0;
	if(days!="" && days != 0){
		needDays = days - actDays;
	}
	$("#needDays").val(needDays);
}

/**
 * 从表计算
 * @param {} rowNum
 */
function detailTimeCheck(rowNum){
	if($("#id").length > 0){
		//从表前置字符串
		var beforeStr = "activityPersons_cmp";
	}else{
		//从表前置字符串
		var beforeStr = "activityMembers_cmp";
	}
	//获取开始日期
	var planBeginDate = $("#" + beforeStr +  "_planBeginDate" + rowNum).val();
	//获取结束日期
	var planEndDate = $("#" + beforeStr +  "_planEndDate" + rowNum ).val();

	if(planBeginDate != "" && planEndDate != ""){
		var days = DateDiff(planBeginDate,planEndDate) + 1 ;
		$("#" + beforeStr +  "_days" + rowNum).val(days);
		calPersonBatch(rowNum);
	}
}

//计算人力预算 - 批量新增中使用
function calPersonBatch(rowNum){
	if($("#id").length > 0){
		//从表前置字符串
		var beforeStr = "activityPersons_cmp";
		var thisGrid = $("#activityPersons");
	}else{
		//从表前置字符串
		var beforeStr = "activityMembers_cmp";
		var thisGrid = $("#activityMembers");
	}
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

	//重置默认相关日期
	thisGrid.yxeditgrid('setConfigValue','planBeginDate',$("#planBeginDate").val());
	thisGrid.yxeditgrid('setConfigValue','planEndDate',$("#planEndDate").val());

	//获取当前任务工作量单位
	var workloadUnit = $("#workloadUnit").val();
	//如果是天，则根据人力预算更新任务的工作天数
	if(workloadUnit == 'GCGZLDW-00'){
		var cmps = thisGrid.yxeditgrid("getCmpByCol", "personDays");
		var allDays = 0;
		cmps.each(function(i,n) {
			allDays = accAdd(allDays,this.value);
		});

		$("#workload").val(allDays);
	}
}


//计算人力预算 - 批量新增中使用
function calPersonBatch2(rowNum){
	//从表前置字符串
	var beforeStr = "activityPersons_cmp";
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

	//改变默认值
	var thisGrid = $("#activityPersons");

	//获取当前任务工作量单位
	var workloadUnit = $("#workloadUnit").val();

	//如果是天，则根据人力预算更新任务的工作天数
	if(workloadUnit == 'GCGZLDW-00'){
		var cmps = thisGrid.yxeditgrid("getCmpByCol", "personDays");
		var allDays = 0;
		cmps.each(function(i,n) {
			allDays = accAdd(allDays,this.value);
		});

		$("#workload").val(allDays);
	}
}

//任务成员设值
function setMember(){
	var beforeStr = "activityMembers_cmp";
	var memberIdArr = [];
	var memberNameArr = [];

	$("input[id^='"+ beforeStr +"_memberName']").each(function(i,n){
		if(this.value){
			memberNameArr.push(this.value);
			memberIdArr.push($("#" + beforeStr +"_memberId" + i).val());
		}
	});
	//成员
	$("#memberName").val(memberNameArr.toString());
	$("#memberId").val(memberIdArr.toString());
}

//重新刷新tab
function reloadTab(thisVal){
	var tt = window.parent.$("#tt");
	var tb=tt.tabs('getTab',thisVal);
	tb.panel('options').headerCls = tb.panel('options').thisUrl;
}

//用于列表进度显示
function formatProgress(value,row){
	if($("#isACatWithFallOutsourcing").val() == "1" && row.planProcess != undefined){
		value = row.planProcess;
	}
    if (value){
        var s = '<div style="width:100%;height:auto;border:1px solid #ccc">' +
                '<div style="width:' + value + '%;background:#22DD92;">' + value + '%' + '</div>'
                '</div>';
        return s;
    } else {
        return '';
    }
}

function checkform(){
	if($("#workRate").val()<0){
		alert("工作占比不能小于0");
		return false;
	}else{
		return true;
	}
}