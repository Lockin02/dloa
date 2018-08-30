//查询
function search(){
	location.href = "?model=flights_report_flreport&action=toPro"
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
    var projectCode = RunningDetailGrid.Recordset.Fields.Item("proNum").AsString;
    var orgDetail = $("#orgDetail").val();
    var thisYear = $("#thisYear").val();
	var SelColNo = ReportViewer.SelColumnNo;
	if(!SelColNo){
		var month = 1
	}else{
		var month = SelColNo*1 - 3;
	}

	showOpenWin("?model=flights_report_flreport&action=messageDetail"
		+ "&thisYear=" + thisYear
		+ "&projectCode=" + projectCode
		+ "&orgDetail=" + orgDetail
		+ "&beginMonth=" + month
		+ "&endMonth=" + month,1,700,1000
	);
}