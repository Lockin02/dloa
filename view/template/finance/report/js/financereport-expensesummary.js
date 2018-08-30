$(function() {
	//初始化公司
	var responseText = $.ajax({
		url: 'index1.php?model=deptuser_branch_branch&action=listJson&sort=ComCard&dir=ASC',
		type: "POST",
		async: false
	}).responseText;
	var branchArr = eval("(" + responseText + ")");

	for (var i = 0, l = branchArr.length; i < l; i++) {
		$("#company").append("<option title='" + branchArr[i].NameCN
		+ "' value='" + branchArr[i].NamePT + "'>" + branchArr[i].NameCN
		+ "</option>");
	}

	//设置公司
	setSelect("company");

	//设置状态
	setSelect("status");

	//选择费用类型渲染
	var DetailTypeHidden = $("#DetailTypeHidden").val();
	var checkAllObj = $("#checkAll");
	if (checkAllObj.val() == DetailTypeHidden) {
		checkAllObj.attr("checked", true);
		checkAll();
	} else {
		var detailArr = DetailTypeHidden.split(",");
		$('input[name="DetailType"]').each(function(i, n) {
			if (jQuery.inArray(this.value, detailArr) != -1) {
				$(this).attr("checked", true);
			}
		});
	}
});

//查询
function search() {
	//费用类型
	if ($("#DetailTypeHidden").val() == "") {
		alert('至少选择一个费用类型');
		return false;
	}

	//月份
	if ($("#beginMonth").val() * 1 > $("#endMonth").val() * 1) {
		alert('开始月份不能大于结束月份');
		return false;
	}

	location.href = "?model=finance_report_financereport&action=toExpenseSummary"
	+ "&thisYear=" + $("#thisYear").val()
	+ "&beginMonth=" + $("#beginMonth").val()
	+ "&endMonth=" + $("#endMonth").val()
	+ "&company=" + $("#company").val()
	+ "&DetailType=" + $("#DetailTypeHidden").val()
	+ "&deptNames=" + $("#deptNames").val()
	+ "&status=" + $("#status").val()
	;
}

//年检查
function checkYear(thisKey) {
	var thisObj = $("#" + thisKey);
	if (thisObj.val() == "") {
		alert('年份不能为空');
		thisObj.val($("#" + thisKey + "Hidden").val());
	}
}

//月份检查
function checkMonth(thisKey) {
	var thisObj = $("#" + thisKey);
	if (thisObj.val() * 1 > 12 || thisObj.val() * 1 < 1) {
		alert('月份输入错误');
		thisObj.val($("#" + thisKey + "Hidden").val());
	}
}

//选择全部
function checkAll() {
	var checkAllObj = $("#checkAll");
	if (checkAllObj.attr("checked") == true) {
		//隐藏域
		$("#DetailTypeHidden").val("all");
		$("input[name='DetailType']").attr("checked", true);
	} else {
		$("#DetailTypeHidden").val("");
		$("input[name='DetailType']").attr("checked", false);
	}
}

//单项选择
function checkDetail(thisValue) {
	var detailArrObj = $('input[name="DetailType"]:checked');
	var detailLenght = detailArrObj.length;
	var checkAllObj = $("#checkAll");
	if (detailLenght == 5) {
		checkAllObj.attr("checked", true);
		$("#DetailTypeHidden").val("all");
	} else {
		checkAllObj.attr("checked", false);
		var detailArr = [];

		detailArrObj.each(function(i, n) {
			detailArr.push(this.value);
		});

		$("#DetailTypeHidden").val(detailArr.toString());
	}
}

//响应内容行双击事件，打开当前行对应的明细报表
function OnContentCellDblClick(Sender) {
	//交叉表应该从 RunningDetailGrid 取穿透数据
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	//这里取出SelMonthText而不用，仅为了示范如何取到横向交叉的数据
	var SelColumn = RunningDetailGrid.Columns.Item(ReportViewer.SelColumnName);
	var CostTypeName = SelColumn.TitleCell.Text;
	if (CostTypeName == '月份' || CostTypeName == '合计') {
		searchDetail();
	} else {
		var thisYear = RunningDetailGrid.Recordset.Fields.Item("thisYear").AsInteger;
		var thisMonth = RunningDetailGrid.Recordset.Fields.Item("thisMonth").AsInteger;
		var CostBelongDeptId = RunningDetailGrid.Recordset.Fields.Item("CostBelongDeptId").AsInteger;
		var CostBelongDeptName = RunningDetailGrid.Recordset.Fields.Item("CostBelongDeptName").AsString;
		var DetailType = $("#DetailTypeHidden").val();
		var company = $("#companyHidden").val();
		showModalWin("?model=finance_report_financereport&action=toExpenseDetail"
			+ "&thisYear=" + thisYear
			+ "&beginMonth=" + thisMonth
			+ "&endMonth=" + thisMonth
			+ "&DetailType=" + DetailType
			+ "&company=" + company
			+ "&moduleName=all"
			+ "&CostBelongDeptId=" + CostBelongDeptId
			+ "&CostBelongDeptName=" + CostBelongDeptName
			+ "&parentDeptId="
			+ "&parentDeptName="
			+ "&CostTypeName=" + CostTypeName
			+ "&status=" + $("#status").val()
		);
	}
}

//打开当前行对应的明细报表
function searchDetail() {
	//交叉表应该从 RunningDetailGrid 取穿透数据
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	var thisYear = RunningDetailGrid.Recordset.Fields.Item("thisYear").AsInteger;
	var thisMonth = RunningDetailGrid.Recordset.Fields.Item("thisMonth").AsInteger;
	var CostBelongDeptId = RunningDetailGrid.Recordset.Fields.Item("CostBelongDeptId").AsInteger;
	var CostBelongDeptName = RunningDetailGrid.Recordset.Fields.Item("CostBelongDeptName").AsString;
	var DetailType = $("#DetailTypeHidden").val();
	var company = $("#companyHidden").val();
	showModalWin("?model=finance_report_financereport&action=toExpenseDetail"
		+ "&thisYear=" + thisYear
		+ "&beginMonth=" + thisMonth
		+ "&endMonth=" + thisMonth
		+ "&DetailType=" + DetailType
		+ "&company=" + company
		+ "&moduleName=all"
		+ "&CostBelongDeptId=" + CostBelongDeptId
		+ "&CostBelongDeptName=" + CostBelongDeptName
		+ "&parentDeptId="
		+ "&parentDeptName="
		+ "&CostTypeName="
		+ "&status=" + $("#status").val()
	);
}

//根据一级部门明细
function searchDept() {
	//交叉表应该从 RunningDetailGrid 取穿透数据
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	var thisYear = RunningDetailGrid.Recordset.Fields.Item("thisYear").AsInteger;
	var thisMonth = RunningDetailGrid.Recordset.Fields.Item("thisMonth").AsInteger;
	var parentDeptId = RunningDetailGrid.Recordset.Fields.Item("parentDeptId").AsInteger;
	var parentDeptName = RunningDetailGrid.Recordset.Fields.Item("parentDeptName").AsString;
	var DetailType = $("#DetailTypeHidden").val();
	var company = $("#companyHidden").val();
	showModalWin("?model=finance_report_financereport&action=toExpenseDetail"
		+ "&thisYear=" + thisYear
		+ "&beginMonth=" + thisMonth
		+ "&endMonth=" + thisMonth
		+ "&DetailType=" + DetailType
		+ "&company=" + company
		+ "&CostBelongDeptId="
		+ "&CostBelongDeptName="
		+ "&parentDeptId=" + parentDeptId
		+ "&parentDeptName=" + parentDeptName
		+ "&CostTypeName="
		+ "&status=" + $("#status").val()
	);
}
