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
	$("#name").formValidator({
		onshow : "请输入任务名称",
		onfocus : "任务名称至少2个字符,最多100个字符",
		oncorrect : "您输入的任务名称可用"
	}).inputValidator({
		min : 2,
		max : 100,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "任务名称两边不能有空符号"
		},
		onerror : "你输入的任务名称,请确认"
//	}).ajaxValidator({
//		type : "get",
//		url : "index1.php",
//		data : "model=rdproject_task_rdtask&action=ajaxTaskName",
//		datatype : "json",
//		success : function(data) {
//			if (data == "1") {
//				return true;
//			} else {
//				return false;
//			}
//		},
//		buttons : $("#submitSave"),
//		error : function() {
//			alert("服务器没有返回数据，可能服务器忙，请重试");
//		},
//		onerror : "该名称不可用，请更换",
//		onwait : "正在对项目名称进行合法性校验，请稍候..."
	});
	$("#chargeName").formValidator({
		onshow : "请选择责任人",
		onfocus : "不可输入，请选择",
		oncorrect : "您选择的负责人有效"
	}).inputValidator({
		min : 1,
		onerror : "请选择负责人"
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

	$("#appraiseWorkload").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "请输入估计工作量（数字）",
		onfocus : "请输入数字",
		oncorrect : "你输入的内容正确"
	}).inputValidator({
		min : 1,
		type : "value",
		onerrormin : "你输入的值必须大于等于1",
		onerror : "请输入估计工作量(数字)"
	});// .defaultPassed();

	$("#nodeName1").formValidator({
		onshow : "请输入节点名称",
		onfocus : "节点名称至少2个字符,最多50个字符",
		oncorrect : "您输入的节点名称可用"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "节点名称两边不能有空符号"
		},
		onerror : "你输入的节点名称,请确认"
	}).ajaxValidator({
		type : "get",
		url : "index1.php",
		data : "model=rdproject_task_tknode&action=ajaxNodeName",
		datatype : "json",
		success : function(data) {
			if (data == "1") {
				return true;
			} else {
				return false;
			}
		},
		buttons : $("#submitSave"),
		error : function() {
			alert("服务器没有返回数据，可能服务器忙，请重试");
		},
		onerror : "该名称不可用，请更换",
		onwait : "正在对节点名称进行合法性校验，请稍候..."
	});

	$("#charegeName").formValidator({
		onshow : "请选择责任人",
		onfocus : "不可输入，请选择",
		oncorrect : "您选择的负责人有效"
	}).inputValidator({
		min : 1,
		onerror : "请选择负责人"
	});
	$("#informTime").formValidator({
		forcevalid : true,
		triggerevent : "change",
		onshow : "请输入确认工作量（数字）",
		onfocus : "请输入数字",
		oncorrect : "你输入的内容正确"
	}).inputValidator({
		min : 1,
		type : "value",
		onerrormin : "你输入的值必须大于等于1",
		onerror : "请输入确认工作量(数字)"
	});// .defaultPassed();



})