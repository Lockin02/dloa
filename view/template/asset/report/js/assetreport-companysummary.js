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
	
	//��ʼ����������
	var responseText = $.ajax({
		url:'index1.php?model=asset_assetcard_assetcard&action=getAgency',
		type : "POST",
		async : false
	}).responseText;
	var agencyArr = eval("(" + responseText + ")");
	if(agencyArr != null){//��������Ȩ��
		for (var i = 0, l = agencyArr.length; i < l; i++) {
			$("#agencyCode").append("<option title='" + agencyArr[i].agencyName
				+ "' value='" + agencyArr[i].agencyCode + "'>" + agencyArr[i].agencyName
				+ "</option>");
		}
		//������������
		setSelect("agencyCode");
	}else{//����������Ȩ��
		$("#agencyCode").addClass("readOnlyTxtItem").attr("disabled","disabled");
	}
	
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

	location.href = "?model=asset_report_assetreport&action=toCompanySummary"
	+ "&dateType=" + $("#dateType").val()
	+ "&beginDate=" + $("#beginDate").val()
	+ "&endDate=" + $("#endDate").val()
	+ "&company=" + $("#company").val()
	+ "&deptId=" + $("#deptId").val()
	+ "&deptName=" + $("#deptName").val()
	+ "&agencyCode=" + $("#agencyCode").val();
}

//��Ӧ������˫���¼����򿪵�ǰ�ж�Ӧ����ϸ����
function OnContentCellDblClick(Sender){
	//�����Ӧ�ô� RunningDetailGrid ȡ��͸����
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	//����ȡ��SelMonthText�����ã���Ϊ��ʾ�����ȡ�����򽻲������
	var SelColumn = RunningDetailGrid.Columns.Item(ReportViewer.SelColumnName);
	//SupCell���ڻ�ȡ���������ϼ�������
	var useStatusName = SelColumn.TitleCell.SupCell.Text;
	if(useStatusName == "�ʲ�״̬" || useStatusName == "�ܼ�"){
		useStatusName = "�ܼ�(��˾����)";
	}
    var assetName = RunningDetailGrid.Recordset.Fields.Item("�ʲ�����").AsString;
    var url;
    if(assetName == ""){
		url = "?model=asset_report_assetreport&action=toDetail"
			+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" +  $("#deptId").val()
			+ "&deptName=" + $("#deptName").val()
			+ "&agencyCode=" + $("#agencyCodeHidden").val()
			+ "&useStatusName=" + useStatusName;
    }else{
		url = "?model=asset_report_assetreport&action=toDetail"
			+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" +  $("#deptId").val()
			+ "&deptName=" + $("#deptName").val()
			+ "&agencyCode=" + $("#agencyCodeHidden").val()
			+ "&assetName=" + assetName
			+ "&useStatusName=" + useStatusName;
    }
	showModalWin(url);
}