$(function(){
	//设置日期类型
	setSelect("dateType");
	
	//初始化公司
	var responseText = $.ajax({
		url:'index1.php?model=deptuser_branch_branch&action=listJson&sort=ComCard&dir=ASC',
		type : "POST",
		async : false
	}).responseText;
	var branchArr = eval("(" + responseText + ")");

	for (var i = 0, l = branchArr.length; i < l; i++) {
		$("#company").append("<option title='" + branchArr[i].NameCN
			+ "' value='" + branchArr[i].NamePT + "'>" + branchArr[i].NameCN
			+ "</option>");
	}

	//设置公司
	setSelect("company");
});

//查询
function search(){	
	//日期验证
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	if(beginDate == ""){
		alert('开始日期不能为空');
		return false;
	}
	if(endDate == ""){
		alert('结束日期不能为空');
		return false;
	}
	if(DateDiff(beginDate,endDate) < 0){
		alert('开始日期不能大于结束日期');
		return false;
	}

	location.href = "?model=asset_report_assetreport&action=toAnnualSummary"
	+ "&dateType=" + $("#dateType").val()
	+ "&beginDate=" + $("#beginDate").val()
	+ "&endDate=" + $("#endDate").val()
	+ "&company=" + $("#company").val();
}

//响应内容行双击事件，打开当前行对应的明细报表 
function OnContentCellDblClick(Sender){
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var assetName = RunningDetailGrid.Recordset.Fields.Item("资产名称").AsString;
    var url;
    if(assetName == "汇总"){
    	url = "?model=asset_report_assetreport&action=toDetail"
    		+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&company=" + $("#companyHidden").val()
			+ "&deptLimit=none&agencyLimit=none"//无须进行部门权限，区域权限处理
    }else{
    	url = "?model=asset_report_assetreport&action=toDetail"
    		+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&company=" + $("#companyHidden").val()
			+ "&assetName=" + assetName
			+ "&deptLimit=none&agencyLimit=none"//无须进行部门权限，区域权限处理
    }
	showModalWin(url);
}
