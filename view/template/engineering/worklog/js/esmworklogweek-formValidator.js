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
	$("#assessmentName").formValidator({
		onshow : "请选择考核人",
		onfocus : "不可输入，请选择",
		oncorrect : "您选择的考核人有效"
	}).inputValidator({
		min : 1,
		onerror : "请选择考核人"
	});

})