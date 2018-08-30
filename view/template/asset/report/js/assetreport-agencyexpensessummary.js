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
	
	//处理部门权限
	var deptIdStr = $("#deptIdStr").val();
	if(deptIdStr != ""){
		if(deptIdStr.indexOf(';;') != -1){//所有部门权限
			$("#deptName").yxselect_dept({
				hiddenId : 'deptId',
		        mode: 'no' // 选择模式 :single 单选 check 多选
			});
		}else{//部分部门权限
			$("#deptName").yxselect_dept({
				hiddenId : 'deptId',
		        deptFilter : deptIdStr, // 部门限制，传入部门Id串，以逗号隔开
		        mode: 'no' // 选择模式 :single 单选 check 多选
			});
		}
	}else{
		$("#deptName").removeClass("txt").addClass("readOnlyTxtItem");
	}
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
	//部门验证
	var deptId = $("#deptId").val();
	if(deptId == ""){
		alert('部门不能为空');
		return false;
	}
	location.href = "?model=asset_report_assetreport&action=toAgencyExpensesSummary"
	+ "&dateType=" + $("#dateType").val()
	+ "&beginDate=" + $("#beginDate").val()
	+ "&endDate=" + $("#endDate").val()
	+ "&company=" + $("#company").val()
	+ "&deptId=" + $("#deptId").val()
	+ "&deptName=" + $("#deptName").val();
}

//响应内容行双击事件，打开当前行对应的明细报表
function OnContentCellDblClick(Sender){
	//交叉表应该从 RunningDetailGrid 取穿透数据
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	var SelColumn = RunningDetailGrid.Columns.Item(ReportViewer.SelColumnName);
	var month = SelColumn.TitleCell.Text;
    var deptId = RunningDetailGrid.Recordset.Fields.Item("三级部门id").AsInteger;
    var deptName = RunningDetailGrid.Recordset.Fields.Item("三级部门").AsString;
    var agencyCode = RunningDetailGrid.Recordset.Fields.Item("区域编号").AsString;
	var url;
    if(month == '三级部门'){
		url = "?model=asset_report_assetreport&action=toDeptSummary"
			+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&dateFmt=month" //日期格式精确到月份
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" + deptId
			+ "&deptName=" + deptName;
	}else{
		//SupCell用于获取本标题格的上级组标题格
		var year = SelColumn.TitleCell.SupCell.Text;
		if(month == '汇总'){
			url = "?model=asset_report_assetreport&action=toYearDetail"
				+ "&dateType=" + $("#dateTypeHidden").val()
				+ "&year=" + year
				+ "&company=" + $("#companyHidden").val()
				+ "&agencyCode=" + agencyCode
				+ "&deptId=" +  deptId
				+ "&deptName=" + deptName;
		}else{
			url = "?model=asset_report_assetreport&action=toMonthDetail"
				+ "&dateType=" + $("#dateTypeHidden").val()
				+ "&year=" + year
				+ "&month=" + month
				+ "&company=" + $("#companyHidden").val()
				+ "&agencyCode=" + agencyCode
				+ "&deptId=" +  deptId
				+ "&deptName=" + deptName;
		}
	}
    showModalWin(url);
}

//查看区域详情
function searchAgency(){
    //交叉表应该从 RunningDetailGrid 取穿透数据
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var deptId = RunningDetailGrid.Recordset.Fields.Item("二级部门id").AsInteger;
    var deptName = RunningDetailGrid.Recordset.Fields.Item("二级部门").AsString;
    var agencyCode = RunningDetailGrid.Recordset.Fields.Item("区域编号").AsString;
	showModalWin("?model=asset_report_assetreport&action=toAgencySummary"
			+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&dateFmt=month" //日期格式精确到月份
			+ "&company=" + $("#companyHidden").val()
			+ "&agencyCode=" + agencyCode
			+ "&deptId=" +  deptId
			+ "&deptName=" + deptName
		);
}

//查看二级部门详情
function searchDept(){
    //交叉表应该从 RunningDetailGrid 取穿透数据
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var deptId = RunningDetailGrid.Recordset.Fields.Item("二级部门id").AsInteger;
    var deptName = RunningDetailGrid.Recordset.Fields.Item("二级部门").AsString;
	showModalWin("?model=asset_report_assetreport&action=toDeptDetail"
			+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&dateFmt=month" //日期格式精确到月份
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" +  deptId
			+ "&deptName=" + deptName
		);
}

//查看三级部门资产分类汇总表
function searchSubDept(){
    //交叉表应该从 RunningDetailGrid 取穿透数据
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var SelColumn = RunningDetailGrid.Columns.Item(ReportViewer.SelColumnName);
    var title = SelColumn.TitleCell.Text;
    if(title == '区域'){
    	alert("请先选中单元格，再点击按钮查看详情");
        return false;
    }else{
        var deptId = RunningDetailGrid.Recordset.Fields.Item("三级部门id").AsInteger;
        var deptName = RunningDetailGrid.Recordset.Fields.Item("三级部门").AsString;
        var agencyCode = RunningDetailGrid.Recordset.Fields.Item("区域编号").AsString;
    	showModalWin("?model=asset_report_assetreport&action=toDeptSummary"
    			+ "&dateType=" + $("#dateTypeHidden").val()
    			+ "&beginDate=" + $("#beginDate").val()
    			+ "&endDate=" + $("#endDate").val()
    			+ "&dateFmt=month" //日期格式精确到月份
    			+ "&company=" + $("#companyHidden").val()
    			+ "&deptId=" +  deptId
    			+ "&deptName=" + deptName
    		);
    }
}