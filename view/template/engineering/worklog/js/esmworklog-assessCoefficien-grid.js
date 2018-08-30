$(function() {
	$("#userName").yxselect_user({
		hiddenId : 'userId',
		formCode : 'certifyapply'
	});
});
//���ؿ���ϵ����ѯ
function initWorklog(){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var userName = $("#userName").val();

	if(beginDate == "" || endDate == ""){
		alert('��ѡ����������');
		return false;
	}
	if(endDate < beginDate){
		alert('��ʼʱ�䲻�ܴ��ڽ���ʱ��');
		return false;
	}
    showLoading(); // ��ʾ����ͼ��

	var objGrid = $("#esmworklogGrid");
    //��������
    $.ajax({
        url : '?model=engineering_worklog_esmworklog&action=assessCoefficien',
        data : {
            beginDateThan : beginDate,
            endDateThan : endDate,
            userName : userName,
            confirmStatus : '1',
            workStatusArr : 'GXRYZT-01,GXRYZT-02,GXRYZT-04'
        },
        type : 'POST',
        async : false,
        success : function(data){
            if(objGrid.html() != ""){
                objGrid.empty();
            }
            objGrid.html(data);
            hideLoading(); // ���ؼ���ͼ��
        }
    });
}

//�鿴��ϸ
function searchDetail(createId,createName,projectId){
	var url = "?model=engineering_worklog_esmworklog&action=toWorklogAndWeeklogDetailList&createId="
		+ createId
		+ "&createName=" + createName
		+ "&projectId=" + projectId
		+ "&beginDate=" + $("#beginDate").val()
		+ "&endDate=" + $("#endDate").val()
	;
	showOpenWin(url, 1 ,800 , 1100 ,createName );
}
//��������ϵ����ѯ
function exportExcel(){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var userName = $("#userName").val();

	var url = "?model=engineering_worklog_esmworklog&action=toExportAssessCoefficien&beginDateThan="
		+ beginDate
		+ "&endDateThan=" + endDate
		+ "&userName=" + userName
	;
	showOpenWin(url, 1 ,150 , 300 ,'exportExcel' );
}