$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			if (confirm("你输入成功,确定导出Excel吗?")) {
				return true;
			} else {
				return false;
			}
		}
	});

	$("#chargeName").formValidator({
		onshow : "请选择责任人",
		onfocus : "不可输入，请选择",
		oncorrect : "您选择的负责人有效"
	}).inputValidator({
		min : 1,
		onerror : "请选择负责人"
	});

	$("#beginTime").formValidator({
		onshow : "请选择开始日期",
		onfocus : "请选择日期",
		oncorrect : "你输入的日期合法"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "请输入合法的日期,并开始日期不能为空"
	}); // .defaultPassed();

	$("#endTime").formValidator({
		// forcevalid:true,
		// triggerevent:"change",
		onshow : "请选择结束日期",
		onfocus : "请选择日期，不能小于开始日期哦",
		oncorrect : "你输入的日期合法"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "请输入合法的日期,并结束日期不能为空"
	}).compareValidator({
		desid : "beginTime",
		operateor : ">=",
		onerror : "结束日期不能小于开始日期"
	}); // .defaultPassed();

		$("#officeName").formValidator({
		onshow : "请选择办事处",
		onfocus : "请选择",
		oncorrect : "您选择的办事处有效"
	}).inputValidator({
		min : 1,
		onerror : "请选择办事处"
	});
})