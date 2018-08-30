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
	//��Ŀ����
	$("#projectManager").yxselect_user({
		hiddenId : 'projectManagerId',
		mode : 'check'
	});
});

// //��������
// function dayTimes(){
// 	var start = $("#workBegin").val();
// 	var startB = $("#beginIdentify").val();		//��ʼʱ����/����
// 	var end = $("#workEnd").val();
// 	var endB = $("#endIdentify").val();			//����ʱ����/����
// 	if(start!=''&&end!==''){
// 		var dayNo = Days(end,start);
// 		var identify = calculate(endB,startB);
// 		var days =  dayNo + identify;
// 		if(days>0){
// 			$("#dayNo").val(days);
// 		}else{
// 			$("#dayNo").val('');
// 			alert("��������ѡ�񲻹淶��������ѡ��");
// 		}

// 	}
// }

// //����ʱ��������
// function Days(day1, day2){
//     //�þ��׼ʱ�������ȡ���ʱ��
//     var minsec =  Date.parse(day1.replace(/-/g,"/"))*1 - Date.parse(day2.replace(/-/g,"/"))*1;
//     var days = minsec / 1000 / 60 / 60 / 24; //factor: second / minute / hour / day
//     return days;
// }

// //����ʱ��������֮��
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
		alert("����ѡ��һ���Ӱ���ڣ�");
		return false;
	}

	if($("#deptId").val()=='131'&&$("#wageLevelCode").val()=='GZJBFGL'&&$("#provinceName").val()=='') {
		alert("��ѡ��ʡ��");
		return false;
	}
	if(!$("#projectManager").val()){
		if(confirm("��Ŀ������ĿΪ�գ��Ƿ������")){
			return true;
		}else{
			return false;
		}
	}
}

//��������
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