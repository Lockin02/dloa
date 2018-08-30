$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		}
//		,
//		onsuccess : function() {
//			if (confirm("编辑成功,确定保存吗?")) {
//				return true;
//			} else {
//				return false;
//			}
//		}
	});
	$("#name").formValidator({
		onshow : "请输入任务书名称",
		onfocus : "任务书名称至少2个字符,最多50个字符",
		oncorrect : "您输入的任务书名称可用"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "任务书名称两边不能有空符号"
		},
		onerror : "你输入的任务书名称,请确认"
	});


	$("#startDate").formValidator({
		onshow : "请选择起始日期",
		onfocus : "请选择日期",
		oncorrect : "你输入的日期合法"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "请输入合法的日期,并起始日期不能为空"
	}); // .defaultPassed();

	$("#endDate").formValidator({
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
		desid : "startDate",
		operateor : ">=",
		onerror : "结束日期不能小于起始日期"
	}); // .defaultPassed();

})