$(document).ready(function() {
	//初始化下拉选择
	getInitData();
	
	validate({
		"clause" : {
			required : true
		},
		"dateCode" : {
			required : true
		},
		"days" : {
			required : true
		}
	});
});
// 变更时选择
function changeDate(){
	var dateName = $("#dateCode").find("option:selected").text();
	$("#dateName").val(dateName);
}



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
			if(dataArr[i].fieldCode != 'esmPercentage' && dataArr[i].fieldCode != 'shipPercentage' && dataArr[i].fieldCode != 'schePercentage'){
				if(defaultVal && defaultVal == dataArr[i].fieldCode){
					optionStr += "<option value='" + dataArr[i].fieldCode + "' selected='selected'>" + dataArr[i].fieldName + "</option>";
				}else{
					optionStr += "<option value='" + dataArr[i].fieldCode + "'>" + dataArr[i].fieldName + "</option>";
				}
			}
		}
		$("#dateCode").append(optionStr);
	}
}