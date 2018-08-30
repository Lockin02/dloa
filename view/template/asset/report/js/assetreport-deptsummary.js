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
	location.href = "?model=asset_report_assetreport&action=toDeptSummary"
	+ "&dateType=" + $("#dateType").val()
	+ "&beginDate=" + $("#beginDate").val()
	+ "&endDate=" + $("#endDate").val()
	+ "&company=" + $("#company").val()
	+ "&deptId=" + $("#deptId").val()
	+ "&deptName=" + $("#deptName").val()
	+ "&deptDefault=none";//无须设置默认部门
}

//响应内容行双击事件，打开当前行对应的明细报表
function OnContentCellDblClick(Sender){
	//交叉表应该从 RunningDetailGrid 取穿透数据
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var parentDeptName = RunningDetailGrid.Recordset.Fields.Item("二级部门").AsString;
	var url;
	if(parentDeptName == '总计'){
		url = "?model=asset_report_assetreport&action=toDetail"
			+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" +  $("#deptId").val()
			+ "&deptName=" + $("#deptName").val()
			+ "&deptIdStr=" + $("#deptIdStr").val()//部门权限
			+ "&useStatusName=非报废" //不显示报废的资产
			+ "&agencyLimit=none";  //无须进行区域权限处理
	}else{
	    var deptId = RunningDetailGrid.Recordset.Fields.Item("三级部门id").AsInteger;
	    var deptName = RunningDetailGrid.Recordset.Fields.Item("三级部门").AsString;
		var assetName = RunningDetailGrid.Recordset.Fields.Item("资产名称").AsString;
		url = "?model=asset_report_assetreport&action=toDetail"
			+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" +  deptId
			+ "&deptName=" + deptName
			+ "&assetName=" + assetName
			+ "&useStatusName=非报废" //不显示报废的资产
			+ "&agencyLimit=none";  //无须进行区域权限处理
	}
	showModalWin(url);
}

//查看二级部门详情
function searchDept(){
    //交叉表应该从 RunningDetailGrid 取穿透数据
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var deptId = RunningDetailGrid.Recordset.Fields.Item("二级部门id").AsInteger;
    var deptName = RunningDetailGrid.Recordset.Fields.Item("二级部门").AsString;
    if(deptName == '总计'){
    	alert("请先选中单元格(非总计栏)，再点击按钮查看详情");
        return false;
    }
	showModalWin("?model=asset_report_assetreport&action=toDeptDetail"
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" +  deptId
			+ "&deptName=" + deptName
		);
}

//查看三级部门详情
function searchSubDept(){
    //交叉表应该从 RunningDetailGrid 取穿透数据
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var deptId = RunningDetailGrid.Recordset.Fields.Item("三级部门id").AsInteger;
    var deptName = RunningDetailGrid.Recordset.Fields.Item("三级部门").AsString;
    if(deptName == ''){
    	alert("请先选中单元格(非总计栏)，再点击按钮查看详情");
        return false;
    }else{
        showModalWin(
        		"?model=asset_report_assetreport&action=toSubDeptDetail"
    			+ "&company=" + $("#companyHidden").val()
    			+ "&deptId=" +  deptId
    			+ "&deptName=" + deptName	
        );
	}
}