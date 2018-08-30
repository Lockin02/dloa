//导出部门日志
function outExcel() {
	var beginDateThan = $("#beginDateThan").val();
	var endDateThan = $("#endDateThan").val();
	if (beginDateThan == "" || endDateThan == "") {
		alert('日期不能为空');
		return false;
	}
	if (beginDateThan > endDateThan) {
		alert('选择日期有问题');
		return false;
	}
	var userNo = $("#userAccount").val();
	var url = "?model=engineering_worklog_esmworklog&action=outExcel"
			+ "&userNo=" + userNo
			+ "&beginDateThan=" + beginDateThan
			+ "&endDateThan=" + endDateThan
		;
	showOpenWin(url, 1, 150, 300, 'outExcel');
}