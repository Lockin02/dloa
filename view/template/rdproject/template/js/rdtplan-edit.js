$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onerror : function(msg) {
		},
		onsuccess : function() {
			return true;
		}
	});

	$("#name").formValidator({
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
})