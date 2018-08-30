$(document).ready(function() {
	if ($("#projectName").val() == '') {
		$("#customerName").parent().show().prev().show(); //客户名称
		$("#saleUserId").parent().show().prev().show(); //销售负责人
		$("#salesExplain").parent().show().prev().show(); //销售说明
		toView();
	} else {
		//其他部门的查看
		$("#projectName").parent().show().prev().show(); //项目名称
		toViewDepartment();
	}
});