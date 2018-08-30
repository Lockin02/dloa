$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onsuccess : function() {
			if (confirm("你输入成功,确定提交吗?")) {
				if($("input[id^='money']").length == 0){
					$("#allotAble").val($("#incomeMoney").val());
				}
				return true;
			} else {
				return false;
			}
		}
	});

	/** 到款单位验证* */
	$("#incomeUnitName").formValidator({
		empty : false,
		onshow : "请选择到款单位",
		onfocus : "选择到款单位",
		oncorrect : "您选择了到款单位"
	}).inputValidator({
		min : 1,
		onerror : "请选择到款单位"
	});

});