
//点击时间
function OnContentCellDblClick(event){
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	var id=RunningDetailGrid.Recordset.Fields.Item("id").AsInteger;
	showModalWin("?model=flights_message_message&action=toView&id=" + id,1,id);
}

//查询
function search(){
	location.href = "?model=flights_report_flreport&action=messageDetail"
		+ "&thisYear=" + $("#thisYear").val()
		+ "&beginMonth=" + $("#beginMonth").val()
		+ "&endMonth=" + $("#endMonth").val()
		+ "&costBelongDeptId=" + $("#costBelongDeptId").val()
	;
}


//年检查
function checkYear(thisKey){
	var thisObj = $("#" + thisKey);
	if(thisObj.val() == ""){
		alert('年份不能为空');
		thisObj.val($("#" + thisKey + "Hidden").val());
	}
}

//月份检查
function checkMonth(thisKey){
	var thisObj = $("#" + thisKey);
	if(thisObj.val() * 1 > 12 || thisObj.val() * 1 < 1){
		alert('月份输入错误');
		thisObj.val($("#" + thisKey + "Hidden").val());
	}
}