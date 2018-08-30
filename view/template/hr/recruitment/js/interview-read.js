$(document).ready(function() {
	//需要特殊转换的字段【级别，面试结果，项目类型，配置是否按公司标准】去除尾部的name
	var transedArr = ['positionLevelName' ,'useInterviewResultName' ,'projectTypeName' ,'isCompanyStandardName'];
	var thisObj;
	$.ajax({
		type: "POST",
		url: "?model=syslog_operation_logoperationItem&action=LastListJson",
		data: {
			id : $("#id").val(),
			tableName : "oa_hr_recruitment_interview"
		},
		async: false,
		success: function(data){
			if(data){
				data = eval("(" + data + ")");
				for(var i = 0 ;i < data.length ;i++) {
					if ($.inArray(data[i]['columnCcode'] ,transedArr) >= 0) {
						thisObj = $("#" + data[i]['columnCcode'].substr(0 ,data[i]['columnCcode'].length - 4));
					} else {
						thisObj = $("#" + data[i]['columnCcode']);
					}

					if(thisObj.attr("class") == "formatMoney"){
						thisObj.before( moneyFormat2(data[i]['oldValue']) + " => ");
					} else {
						thisObj.before(data[i]['oldValue'] + ' => ');
					}
					thisObj.attr('style' ,'color:red');
				}
			}
		}
	});
});