function OnContentCellDblClick(event) {
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	var id = RunningDetailGrid.Recordset.Fields.Item("ID").AsInteger;
	var isNew = RunningDetailGrid.Recordset.Fields.Item("isNew").AsInteger;
	var BillNo = RunningDetailGrid.Recordset.Fields.Item("BillNo").AsString;

	if (isNew == "1") {
		showModalWin("?model=finance_expense_exsummary&action=toView"
		+ "&id=" + id, 1, id);
	} else {
		showOpenWin("general/costmanage/reim/summary_detail.php?status=出纳付款&BillNo=" + BillNo, 1)
	}
}

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


	//初始化公司
	moduleNameArr = getData('HTBK');
	addDataToSelect(moduleNameArr, 'moduleName');

	setSelect("moduleName");
	$("#moduleName").change(function(){
		$("#moduleNameHidden").val($("#moduleName").val());
	});

    // 附件类型
//    moduleNameArr = getData('HTBK');
//    addDataToSelect(moduleNameArr, 'moduleName');



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

	location.href = "?model=finance_report_financereport&action=toExpenseDetail"
	+ "&thisYear=" + $("#thisYear").val()
	+ "&beginMonth=" + $("#beginMonth").val()
	+ "&endMonth=" + $("#endMonth").val()
	+ "&company=" + $("#company").val()
	+ "&moduleName=" + $("#moduleNameHidden").val()
	+ "&DetailType=" + $("#DetailTypeHidden").val()
	+ "&CostBelongDeptId=" + $("#CostBelongDeptId").val()
	+ "&CostBelongDeptName=" + $("#CostBelongDeptName").val()
	+ "&parentDeptId=" + $("#parentDeptId").val()
	+ "&parentDeptName=" + $("#parentDeptName").val()
	+ "&CostTypeName=" + $("#CostTypeName").val()
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
