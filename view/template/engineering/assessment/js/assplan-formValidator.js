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
	$("#planName").formValidator({
		onshow : "请输入计划名称",
		onfocus : "计划名称至少2个字符,最多50个字符",
		oncorrect : "您输入的计划名称可用"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "计划名称两边不能有空符号"
		},
		onerror : "你输入的计划名称,请确认"
	});


	$("#planStartTime").formValidator({
		onshow : "请选择计划起始日期",
		onfocus : "请选择日期",
		oncorrect : "你输入的日期合法"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "请输入合法的日期,并起始日期不能为空"
	}); // .defaultPassed();

	$("#planEndTime").formValidator({
		// forcevalid:true,
		// triggerevent:"change",
		onshow : "请选择结束日期",
		onfocus : "请选择日期，不能小于计划开始日期哦",
		oncorrect : "你输入的日期合法"
	}).inputValidator({
		min : "1900-01-01",
		max : "2100-01-01",
		type : "date",
		onerror : "请输入合法的日期,并结束日期不能为空"
	}).compareValidator({
		desid : "planStartTime",
		operateor : ">=",
		onerror : "结束日期不能小于起始日期"
	}); // .defaultPassed();


	$("#sortNo").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "请输入排序号（数字）",
		onfocus : "请输入数字",
		oncorrect : "你输入的内容正确"
	}).inputValidator({
		min : 1,
		type : "value",
		onerrormin : "你输入的值必须大于等于1",
		onerror : "请输入排序号(数字)"
	});// .defaultPassed();
})