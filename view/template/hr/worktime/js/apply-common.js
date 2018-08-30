$(document).ready(function() {
	$("#beginIdentify").change(function(){
		dayTimes();
	});

	$("#endIdentify").change(function(){
		dayTimes();
	});

	validate({
		"dayNo" : {
			required : true
		}
	});
	//项目经理
	$("#projectManager").yxselect_user({
		hiddenId : 'projectManagerId',
		mode : 'check'
	});
});

// //计算天数
// function dayTimes(){
// 	var start = $("#workBegin").val();
// 	var startB = $("#beginIdentify").val();		//开始时间上/下午
// 	var end = $("#workEnd").val();
// 	var endB = $("#endIdentify").val();			//结束时间上/下午
// 	if(start!=''&&end!==''){
// 		var dayNo = Days(end,start);
// 		var identify = calculate(endB,startB);
// 		var days =  dayNo + identify;
// 		if(days>0){
// 			$("#dayNo").val(days);
// 		}else{
// 			$("#dayNo").val('');
// 			alert("申请日期选择不规范，请重新选择！");
// 		}

// 	}
// }

// //计算时间天数差
// function Days(day1, day2){
//     //用距标准时间差来获取相距时间
//     var minsec =  Date.parse(day1.replace(/-/g,"/"))*1 - Date.parse(day2.replace(/-/g,"/"))*1;
//     var days = minsec / 1000 / 60 / 60 / 24; //factor: second / minute / hour / day
//     return days;
// }

// //计算时间上下午之差
// function calculate(day1, day2){
//     var subtraction = day1 - day2;
//     var halfDay;
//     if(subtraction==0){
//     	halfDay = 0.5;
//     }
//     else if(subtraction==1){
//     	halfDay = 1;
//     }else{
//     	halfDay = 0;
//     }
//     return halfDay;
// }

function sub() {
	var rs = false;
	$("[id^=applyHoliday_cmp_isApply]").each(function () {
		if ($(this).attr("checked")) {
			rs = true;
			return;
		}
	});
	if (rs == false) {
		alert("至少选择一个加班假期！");
		return false;
	}

	if($("#deptId").val()=='131'&&$("#wageLevelCode").val()=='GZJBFGL'&&$("#provinceName").val()=='') {
		alert("请选择省份");
		return false;
	}
	if(!$("#projectManager").val()){
		if(confirm("项目经理栏目为空，是否继续？")){
			return true;
		}else{
			return false;
		}
	}
}

//计算天数
function calculateDays() {
	var days = 0;
	var num = $("#applyHoliday").yxeditgrid("getCurShowRowNum");
	for(var i = 0 ;i < num ;i++) {
		if ($("#applyHoliday_cmp_isApply" + i).attr("checked")) {
			if ($("#applyHoliday_cmp_holidayInfo" + i).val() == '3') {
				days += 1;
			} else {
				days += 0.5;
			}
		}
	}

	$("#dayNo").val(days);
}