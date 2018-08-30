$(document).ready(function() {
	//��ʼ������ѡ��
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
// ���ʱѡ��
function changeDate(){
	var dateName = $("#dateCode").find("option:selected").text();
	$("#dateName").val(dateName);
}



//��ȡ��ʼ������
function getInitData(defaultVal){
	$.ajax({
	    type: "POST",
	    url: "?model=contract_config_dateconfig&action=listJson",
	    async: false,
	    success: function(data){
	    	var dataArr = eval("(" + data + ")");
			//��ʼ��ѡ��
	    	initOption(dataArr,defaultVal);
		}
	});
}

//��ʼ��ѡ��
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