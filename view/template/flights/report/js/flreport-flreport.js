//查询
function search(){
	location.href = "?model=flights_report_flreport&action=page"
	+ "&thisYear=" + $("#thisYear").val()
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
//响应内容行双击事件，打开当前行对应的明细报表
function OnContentCellDblClick(Sender){
	//直接调用明细部门详细
    searchDetail();
}

//打开当前行对应的明细报表
function searchDetail(){
    //交叉表应该从 RunningDetailGrid 取穿透数据
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var thisYear = $("#thisYear").val();
    var DetailType = RunningDetailGrid.Recordset.Fields.Item("detailType").AsString;
    var orgDetail = RunningDetailGrid.Recordset.Fields.Item("orgDetail").AsString;
	showOpenWin("?model=flights_report_flreport&action=toFlightsDetail"
		+ "&thisYear=" + thisYear
		+ "&DetailType=" + DetailType
		+ "&orgDetail=" + orgDetail,1,700,1000
	);
}