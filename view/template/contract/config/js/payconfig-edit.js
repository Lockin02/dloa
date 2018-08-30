$(document).ready(function() {
	//初始化下拉选择
	getInitData($("#dateCodeHidden").val());

	//表单验证
	validate({
		"configName" : {
			required : true
		},
		"dateCode" : {
			required : true
		},
		"days" : {
			required : true
		}
	});

	//初始化日期相关
	var isNeedDate = $("#isNeedDate").val();
	if(isNeedDate == "1"){
		$("#isNeedDateY").attr("checked",true);
	}else{
		$("#isNeedDateN").attr("checked",true);
	}

	//初始化一下日期相关
	changeNeedDate($("#isNeedDate").val());

	//初始化是否可选进度百分比
	var dateCode = $("#dateCodeHidden").val();
	if(dateCode == 'schePercentage'){
		$("#schePctShowTr").show();
	}
	var schePct = $("#schePct").val();
	if(schePct == "1"){
		$("#schePctY").attr("checked",true);
	}else{
		$("#schePctN").attr("checked",true);
	}

});