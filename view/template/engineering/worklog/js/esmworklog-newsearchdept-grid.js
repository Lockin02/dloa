$(function() {
	//����
	$("#deptName").yxselect_dept({
		hiddenId: 'deptId',
		mode: 'no'
	});
});

//��ʼ��������ͳ�Ʊ�
function initWorklog() {
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();

	if (beginDate == "" || endDate == "") {
		alert('��ѡ����������');
		return false;
	}
	showLoading(); // ��ʾ����ͼ��

	var objGrid = $("#esmworklogGrid");
	//��������
	$.ajax({
		url: '?model=engineering_worklog_esmworklog&action=newSearchDeptJson',
		data: {
            beginDate: beginDate,
            endDate: endDate,
			deptId: $("#deptId").val()
		},
		type: 'POST',
		async: false,
		success: function(data) {
			if (objGrid.html() != "") {
				objGrid.empty();
			}
			objGrid.html(data);
			hideLoading(); // ���ؼ���ͼ��
		}
	});
}

//�鿴��ϸ
function searchDetail(createId, createName, projectId) {
	var url = "?model=engineering_worklog_esmworklog&action=toNewSearchDetailList&createId="
			+ createId
			+ "&userName=" + createName
			+ "&projectId=" + projectId
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
		;
	showOpenWin(url, 1, 800, 1100, createName);
}

//����������ͳ��
function exportExcel() {
	var url = "?model=engineering_worklog_esmworklog&action=exportNewSearchDept&deptId="
			+ $("#deptId").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
		;
	showOpenWin(url, 1, 150, 300, 'exportExcel');
}