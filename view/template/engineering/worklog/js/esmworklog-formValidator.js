$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
		var result = $.ajax({
				url: "?model=engineering_worklog_esmworklog&action=couldAddLogDate&executionDate="+$("#executionDate").val(),
				async: false
				}).responseText;
		if(result=="no"){
			alert("本周周报已经提交审核，不可以填写日志！")
			$("#executionDate").focus();
				return false;
		}
		}
	});



	$("#executionDate").formValidator({
		onshow : "请选择日期",
		onfocus : "请选择日期",
		oncorrect : "你输入的日期合法"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "请输入合法的日期,并日期不能为空"
	}); // .defaultPassed();
})