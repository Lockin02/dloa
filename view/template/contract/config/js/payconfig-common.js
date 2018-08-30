

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
			if(defaultVal && defaultVal == dataArr[i].fieldCode){
				optionStr += "<option value='" + dataArr[i].fieldCode + "' selected='selected'>" + dataArr[i].fieldName + "</option>";
			}else{
				optionStr += "<option value='" + dataArr[i].fieldCode + "'>" + dataArr[i].fieldName + "</option>";
			}
		}
		$("#dateCode").append(optionStr);
	}
}

//���ʱѡ��
function changeDate(){
	var dateName = $("#dateCode").find("option:selected").text();
	$("#dateName").val(dateName);

	// ���ѡ�˽��Ȱٷֱ�,�����������
	var dateCode = $("#dateCode").find("option:selected").val();
	if(dateCode == 'esmPercentage' || dateCode == 'shipPercentage' || dateCode == 'schePercentage'){
		$("#schePctShowTr").show();
	}else{
		$("#schePctN").attr("checked","checked");
		$("#schePctShowTr").hide();
	}
}

//�Ƿ��������
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