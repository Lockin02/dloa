$(function() {
	/**
	 * 验证信息
	 */
	validate({
		"budgetCode" : {
			required : true
		},
		"budgetName" : {
			required : true
		},
		"parentName" : {
			required : true
		}
	});

	/**
	 * 编号唯一性验证
	 */
	var url = "?model=engineering_baseinfo_budget&action=checkRepeat";
	$("#budgetCode").ajaxCheck({
		url : url,
		alertText : "* 该编号已存在",
		alertTextOk : "* 该编号可用"
	});
});
