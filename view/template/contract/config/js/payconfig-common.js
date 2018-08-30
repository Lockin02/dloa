

//获取初始化数据
function getInitData(defaultVal){
	$.ajax({
	    type: "POST",
	    url: "?model=contract_config_dateconfig&action=listJson",
	    async: false,
	    success: function(data){
	    	var dataArr = eval("(" + data + ")");
			//初始化选项
	    	initOption(dataArr,defaultVal);
		}
	});
}

//初始化选项
function initOption(dataArr,defaultVal){
	if(dataArr.length > 0){
		var optionStr = "";
		for(var i = 0; i < dataArr.length ; i++){
			if(defaultVal && defaultVal == dataArr[i].fieldCode){
				optionStr += "<option value='" + dataArr[i].fieldCode + "' selected='selected'>" + dataArr[i].fieldName + "</option>";
			}else{
				optionStr += "<option value='" + dataArr[i].fieldCode + "'>" + dataArr[i].fieldName + "</option>";
			}
		}
		$("#dateCode").append(optionStr);
	}
}

//变更时选择
function changeDate(){
	var dateName = $("#dateCode").find("option:selected").text();
	$("#dateName").val(dateName);

	// 如果选了进度百分比,则带出配置项
	var dateCode = $("#dateCode").find("option:selected").val();
	if(dateCode == 'esmPercentage' || dateCode == 'shipPercentage' || dateCode == 'schePercentage'){
		$("#schePctShowTr").show();
	}else{
		$("#schePctN").attr("checked","checked");
		$("#schePctShowTr").hide();
	}
}

//是否日期相关
function changeNeedDate(thisVal){
	if(thisVal == "1"){
		$("#dateCodeShow").addClass("blue");
		$("#daysShow").addClass("blue");

		$("#dateCode").addClass("validate[required]");
		$("#days").addClass("validate[required]");
	}else{
		$("#dateCodeShow").removeClass("blue");
		$("#daysShow").removeClass("blue");

		$("#dateCode").removeClass("validate[required]");
		$("#days").removeClass("validate[required]");
	}
}