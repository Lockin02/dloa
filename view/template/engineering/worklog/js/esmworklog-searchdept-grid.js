$(function() {
	//部门
	$("#deptName").yxselect_dept({
		hiddenId: 'deptId',
		mode: 'no'
	});	
    //工程项目渲染
    $("#projectCode").yxcombogrid_esmproject({
        hiddenId: 'projectId',
        nameCol: 'projectCode',
        isShowButton: false,
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            isTitle: true,
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#projectCode").val(data.projectCode);
                }
            }
        }
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
		url: '?model=engineering_worklog_esmworklog&action=searchDeptJson',
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
	var url = "?model=engineering_worklog_esmworklog&action=toSearchDetailList&createId="
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
	var url = "?model=engineering_worklog_esmworklog&action=exportSearchDeptJson&deptId="
			+ $("#deptId").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
		;
	showOpenWin(url, 1, 150, 300, 'exportExcel');
}

//导出部门日志 : 用于部门周报 - 部门日志 - 导出Excel
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
	var deptId = $("#deptId").val();
	var deptIds = $("#deptIds").val();//这个是权限
	var projectId = $("#projectId").val();
	var url = "?model=engineering_worklog_esmworklog&action=outExcel&deptId="
			+ deptId
			+ "&deptIds=" + deptIds
			+ "&userNo=" + userNo
			+ "&beginDateThan=" + beginDateThan
			+ "&endDateThan=" + endDateThan
			+ "&projectId=" + projectId
		;
	showOpenWin(url, 1, 150, 300, 'outExcel');
}