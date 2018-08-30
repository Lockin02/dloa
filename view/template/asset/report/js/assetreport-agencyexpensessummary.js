$(function(){
	//������������
	setSelect("dateType");
	
	//��ʼ����˾
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

	//���ù�˾
	setSelect("company");
	
	//������Ȩ��
	var deptIdStr = $("#deptIdStr").val();
	if(deptIdStr != ""){
		if(deptIdStr.indexOf(';;') != -1){//���в���Ȩ��
			$("#deptName").yxselect_dept({
				hiddenId : 'deptId',
		        mode: 'no' // ѡ��ģʽ :single ��ѡ check ��ѡ
			});
		}else{//���ֲ���Ȩ��
			$("#deptName").yxselect_dept({
				hiddenId : 'deptId',
		        deptFilter : deptIdStr, // �������ƣ����벿��Id�����Զ��Ÿ���
		        mode: 'no' // ѡ��ģʽ :single ��ѡ check ��ѡ
			});
		}
	}else{
		$("#deptName").removeClass("txt").addClass("readOnlyTxtItem");
	}
});

//��ѯ
function search(){
	//������֤
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	if(beginDate == ""){
		alert('��ʼ���ڲ���Ϊ��');
		return false;
	}
	if(endDate == ""){
		alert('�������ڲ���Ϊ��');
		return false;
	}
	if(DateDiff(beginDate,endDate) < 0){
		alert('��ʼ���ڲ��ܴ��ڽ�������');
		return false;
	}
	//������֤
	var deptId = $("#deptId").val();
	if(deptId == ""){
		alert('���Ų���Ϊ��');
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

//��Ӧ������˫���¼����򿪵�ǰ�ж�Ӧ����ϸ����
function OnContentCellDblClick(Sender){
	//�����Ӧ�ô� RunningDetailGrid ȡ��͸����
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	var SelColumn = RunningDetailGrid.Columns.Item(ReportViewer.SelColumnName);
	var month = SelColumn.TitleCell.Text;
    var deptId = RunningDetailGrid.Recordset.Fields.Item("��������id").AsInteger;
    var deptName = RunningDetailGrid.Recordset.Fields.Item("��������").AsString;
    var agencyCode = RunningDetailGrid.Recordset.Fields.Item("������").AsString;
	var url;
    if(month == '��������'){
		url = "?model=asset_report_assetreport&action=toDeptSummary"
			+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&dateFmt=month" //���ڸ�ʽ��ȷ���·�
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" + deptId
			+ "&deptName=" + deptName;
	}else{
		//SupCell���ڻ�ȡ���������ϼ�������
		var year = SelColumn.TitleCell.SupCell.Text;
		if(month == '����'){
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

//�鿴��������
function searchAgency(){
    //�����Ӧ�ô� RunningDetailGrid ȡ��͸����
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var deptId = RunningDetailGrid.Recordset.Fields.Item("��������id").AsInteger;
    var deptName = RunningDetailGrid.Recordset.Fields.Item("��������").AsString;
    var agencyCode = RunningDetailGrid.Recordset.Fields.Item("������").AsString;
	showModalWin("?model=asset_report_assetreport&action=toAgencySummary"
			+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&dateFmt=month" //���ڸ�ʽ��ȷ���·�
			+ "&company=" + $("#companyHidden").val()
			+ "&agencyCode=" + agencyCode
			+ "&deptId=" +  deptId
			+ "&deptName=" + deptName
		);
}

//�鿴������������
function searchDept(){
    //�����Ӧ�ô� RunningDetailGrid ȡ��͸����
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var deptId = RunningDetailGrid.Recordset.Fields.Item("��������id").AsInteger;
    var deptName = RunningDetailGrid.Recordset.Fields.Item("��������").AsString;
	showModalWin("?model=asset_report_assetreport&action=toDeptDetail"
			+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&dateFmt=month" //���ڸ�ʽ��ȷ���·�
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" +  deptId
			+ "&deptName=" + deptName
		);
}

//�鿴���������ʲ�������ܱ�
function searchSubDept(){
    //�����Ӧ�ô� RunningDetailGrid ȡ��͸����
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var SelColumn = RunningDetailGrid.Columns.Item(ReportViewer.SelColumnName);
    var title = SelColumn.TitleCell.Text;
    if(title == '����'){
    	alert("����ѡ�е�Ԫ���ٵ����ť�鿴����");
        return false;
    }else{
        var deptId = RunningDetailGrid.Recordset.Fields.Item("��������id").AsInteger;
        var deptName = RunningDetailGrid.Recordset.Fields.Item("��������").AsString;
        var agencyCode = RunningDetailGrid.Recordset.Fields.Item("������").AsString;
    	showModalWin("?model=asset_report_assetreport&action=toDeptSummary"
    			+ "&dateType=" + $("#dateTypeHidden").val()
    			+ "&beginDate=" + $("#beginDate").val()
    			+ "&endDate=" + $("#endDate").val()
    			+ "&dateFmt=month" //���ڸ�ʽ��ȷ���·�
    			+ "&company=" + $("#companyHidden").val()
    			+ "&deptId=" +  deptId
    			+ "&deptName=" + deptName
    		);
    }
}