$(document).ready(function() {
	if ($("#projectName").text() == '') {
		$("#customerName").parent().show().prev().show(); //客户名称
		$("#saleUserId").parent().show().prev().show(); //销售负责人
		$("#salesExplain").parent().show().prev().show(); //销售说明
		toView();
	} else {
		//其他部门的查看
		$("#projectName").parent().show().prev().show(); //项目名称
		toViewDepartment();
	}

	var thisObj ;
	$.ajax({
		type: "POST",
		url: "?model=common_changeLog&action=getChangeInformation",
		data: {
			tempId : $("#id").val(),
			logObj : "produceapply"
		},
		async: false,
		success: function(data){
			if(data){
				data = eval("(" + data + ")");
				for (var i = 0; i < data.length ; i++){
					thisObj = $("#" + data[i]['changeField']);
					if (thisObj.attr("class") == "formatMoney"){
						thisObj.html( moneyFormat2(data[i]['oldValue']) + " => " + moneyFormat2(data[i]['newValue']));
					} else {
						thisObj.html(data[i]['oldValue'] + ' => ' +  data[i]['newValue']);
					}
					thisObj.attr('style','color:red');
				}
			}
		}
	});
});