$(document).ready(function() {
	//验证信息
	validate({
		"applyUser" : {
			required : true
		},
		"reason" : {
			required : true
		}
	});
	
	//提示信息
	if($("#projectId").val() != "" && $("#projectId").val() != "0"){//项目续借的情况
		if($("#flag").val() == "2"){
			$("#trips").html("友情提示：该项目为关闭状态，只能续借至个人名下，且续期不能超过1个月");
		}else if($("#flag").val() == "3"){
			$("#trips").html("友情提示：该项目为试用项目，续期不能超过1个月");
		}
	}else{//个人续借的情况
		$("#trips").html("友情提示：个人续借时，续期不能超过1个月");
	}
});

//提交或保存时改变隐藏值
function setConfirm(thisType){
	$("#status").val(thisType);
}

//保存,提交时进行相关验证
function checkSubmit(type) {
	var objGrid = $("#importTable");
	//获取设备的行数
	var curRowNum = objGrid.yxeditgrid("getCurShowRowNum");
	//相关验证
	for(var i = 0; i < curRowNum ; i++){
		//日期验证
		var beginDate = objGrid.yxeditgrid("getCmpByRowAndCol",i,"beginDate").val();
		var endDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"endDate");
		var days = DateDiff(beginDate,endDateObj.val());
		if(days < 0){
			alert("预计归还日期不能早于预计开始日期");
			endDateObj.focus();
			return false;
		}
		if(days > 30 && $("#flag").val() != "1"){
			alert("续期最多不能超过1个月");
			endDateObj.focus();
			return false;
		}
		//新增页面，验证数量
		if(type == 'add'){
			var maxNum = objGrid.yxeditgrid("getCmpByRowAndCol",i,"maxNum").val();
			var numObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"number");
			var number = numObj.val();
			
	        if (!isNum(number)) {
	            alert("续借数量" + "<" + number + ">" + "填写有误!");
	            numObj.focus();
	            return false;
	        }
	        if (number*1 > maxNum*1) {
	            alert("续借数量不能大于在借数量");
	            numObj.focus();
	            return false;
	        }
		}
	}
}