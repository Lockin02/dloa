//����������־
function outExcel() {
	var beginDateThan = $("#beginDateThan").val();
	var endDateThan = $("#endDateThan").val();
	if (beginDateThan == "" || endDateThan == "") {
		alert('���ڲ���Ϊ��');
		return false;
	}
	if (beginDateThan > endDateThan) {
		alert('ѡ������������');
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