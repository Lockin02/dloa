$(function() {
	/**
	 * ��֤��Ϣ
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
	 * ���Ψһ����֤
	 */
	var url = "?model=engineering_baseinfo_budget&action=checkRepeat";
	$("#budgetCode").ajaxCheck({
		url : url,
		alertText : "* �ñ���Ѵ���",
		alertTextOk : "* �ñ�ſ���"
	});
});
