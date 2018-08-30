$(function() {
	$("#userName").yxselect_user({
		hiddenId : 'userId',
		formCode : 'certifyapply'
	});
});
//加载考核系数查询
function initWorklog(){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var userName = $("#userName").val();

	if(beginDate == "" || endDate == ""){
		alert('请选择日期区间');
		return false;
	}
	if(endDate < beginDate){
		alert('开始时间不能大于结束时间');
		return false;
	}
    showLoading(); // 显示加载图标

	var objGrid = $("#esmworklogGrid");
    //请求数据
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
            hideLoading(); // 隐藏加载图标
        }
    });
}

//查看明细
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
//导出考核系数查询
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