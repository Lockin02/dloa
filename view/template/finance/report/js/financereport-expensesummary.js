$(function() {
	//��ʼ����˾
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

	//���ù�˾
	setSelect("company");

	//����״̬
	setSelect("status");

	//ѡ�����������Ⱦ
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

//��ѯ
function search() {
	//��������
	if ($("#DetailTypeHidden").val() == "") {
		alert('����ѡ��һ����������');
		return false;
	}

	//�·�
	if ($("#beginMonth").val() * 1 > $("#endMonth").val() * 1) {
		alert('��ʼ�·ݲ��ܴ��ڽ����·�');
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

//����
function checkYear(thisKey) {
	var thisObj = $("#" + thisKey);
	if (thisObj.val() == "") {
		alert('��ݲ���Ϊ��');
		thisObj.val($("#" + thisKey + "Hidden").val());
	}
}

//�·ݼ��
function checkMonth(thisKey) {
	var thisObj = $("#" + thisKey);
	if (thisObj.val() * 1 > 12 || thisObj.val() * 1 < 1) {
		alert('�·��������');
		thisObj.val($("#" + thisKey + "Hidden").val());
	}
}

//ѡ��ȫ��
function checkAll() {
	var checkAllObj = $("#checkAll");
	if (checkAllObj.attr("checked") == true) {
		//������
		$("#DetailTypeHidden").val("all");
		$("input[name='DetailType']").attr("checked", true);
	} else {
		$("#DetailTypeHidden").val("");
		$("input[name='DetailType']").attr("checked", false);
	}
}

//����ѡ��
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

//��Ӧ������˫���¼����򿪵�ǰ�ж�Ӧ����ϸ����
function OnContentCellDblClick(Sender) {
	//�����Ӧ�ô� RunningDetailGrid ȡ��͸����
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	//����ȡ��SelMonthText�����ã���Ϊ��ʾ�����ȡ�����򽻲������
	var SelColumn = RunningDetailGrid.Columns.Item(ReportViewer.SelColumnName);
	var CostTypeName = SelColumn.TitleCell.Text;
	if (CostTypeName == '�·�' || CostTypeName == '�ϼ�') {
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

//�򿪵�ǰ�ж�Ӧ����ϸ����
function searchDetail() {
	//�����Ӧ�ô� RunningDetailGrid ȡ��͸����
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

//����һ��������ϸ
function searchDept() {
	//�����Ӧ�ô� RunningDetailGrid ȡ��͸����
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
