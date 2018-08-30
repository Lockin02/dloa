$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onsuccess : function() {
			if (confirm("你输入成功,确定提交吗?")) {
				return true;
			} else {
				return false;
			}
		}
	});

	/** 供应商验证* */
	$("#supplierName").formValidator({
		empty : false,
		onshow : "请选择供应商",
		onfocus : "选择供应商",
		oncorrect : "您选择了供应商"
	}).inputValidator({
		min : 1,
		onerror : "请选择供应商"
	});

});