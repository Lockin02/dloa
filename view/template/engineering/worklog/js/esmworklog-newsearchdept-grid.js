$(function() {
	//部门
	$("#deptName").yxselect_dept({
		hiddenId: 'deptId',
		mode: 'no'
	});
});

//初始化工作量统计表
function initWorklog() {
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();

	if (beginDate == "" || endDate == "") {
		alert('请选择日期区间');
		return false;
	}
	showLoading(); // 显示加载图标

	var objGrid = $("#esmworklogGrid");
	//请求数据
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
			hideLoading(); // 隐藏加载图标
		}
	});
}

//查看明细
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

//导出工作量统计
function exportExcel() {
	var url = "?model=engineering_worklog_esmworklog&action=exportNewSearchDept&deptId="
			+ $("#deptId").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
		;
	showOpenWin(url, 1, 150, 300, 'exportExcel');
}