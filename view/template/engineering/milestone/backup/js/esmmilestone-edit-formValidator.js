$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			if (confirm("你输入成功,确定提交吗?")) {
				return true;
			} else {
				return false;
			}
		}
	});
	$("#planBeginDate").formValidator({
		onshow : "请选择计划开始日期",
		onfocus : "请选择日期",
		oncorrect : "你输入的日期合法"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "请输入合法的日期,并计划开始日期不能为空"
	}); // .defaultPassed();

	$("#planEndDate").formValidator({
		// forcevalid:true,
		// triggerevent:"change",
		onshow : "请选择计划完成日期",
		onfocus : "请选择日期，不能小于计划开始日期哦",
		oncorrect : "你输入的日期合法"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "请输入合法的日期,并计划完成日期不能为空"
	}).compareValidator({
		desid : "planBeginDate",
		operateor : ">=",
		onerror : "计划完成日期不能小于计划开始日期"
	}); // .defaultPassed();


})