function OnContentCellDblClick(event) {
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	var id = RunningDetailGrid.Recordset.Fields.Item("ID").AsInteger;
	var isNew = RunningDetailGrid.Recordset.Fields.Item("isNew").AsInteger;
	var BillNo = RunningDetailGrid.Recordset.Fields.Item("BillNo").AsString;

	if (isNew == "1") {
		showModalWin("?model=finance_expense_exsummary&action=toView"
		+ "&id=" + id, 1, id);
	} else {
		showOpenWin("general/costmanage/reim/summary_detail.php?status=���ɸ���&BillNo=" + BillNo, 1)
	}
}

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


	//��ʼ����˾
	moduleNameArr = getData('HTBK');
	addDataToSelect(moduleNameArr, 'moduleName');

	setSelect("moduleName");
	$("#moduleName").change(function(){
		$("#moduleNameHidden").val($("#moduleName").val());
	});

    // ��������
//    moduleNameArr = getData('HTBK');
//    addDataToSelect(moduleNameArr, 'moduleName');



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
