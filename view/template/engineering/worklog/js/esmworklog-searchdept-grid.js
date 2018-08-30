$(function() {
	//����
	$("#deptName").yxselect_dept({
		hiddenId: 'deptId',
		mode: 'no'
	});	
    //������Ŀ��Ⱦ
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
			hideLoading(); // ���ؼ���ͼ��
		}
	});
}

//�鿴��ϸ
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

//����������ͳ��
function exportExcel() {
	var url = "?model=engineering_worklog_esmworklog&action=exportSearchDeptJson&deptId="
			+ $("#deptId").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
		;
	showOpenWin(url, 1, 150, 300, 'exportExcel');
}

//����������־ : ���ڲ����ܱ� - ������־ - ����Excel
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
	var deptId = $("#deptId").val();
	var deptIds = $("#deptIds").val();//�����Ȩ��
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