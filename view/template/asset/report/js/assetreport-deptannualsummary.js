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
	location.href = "?model=asset_report_assetreport&action=toDeptAnnualSummary"
	+ "&dateType=" + $("#dateType").val()
	+ "&beginDate=" + $("#beginDate").val()
	+ "&endDate=" + $("#endDate").val()
	+ "&company=" + $("#company").val()
	+ "&deptId=" + $("#deptId").val()
	+ "&deptName=" + $("#deptName").val()
	+ "&deptDefault=none";//��������Ĭ�ϲ���
}

//��Ӧ������˫���¼����򿪵�ǰ�ж�Ӧ����ϸ���� 
function OnContentCellDblClick(Sender){
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var assetName = RunningDetailGrid.Recordset.Fields.Item("�ʲ�����").AsString;
    var url;
    if(assetName == "����"){
    	url = "?model=asset_report_assetreport&action=toDetail"
    		+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" + $("#deptId").val()
			+ "&deptName=" + $("#deptName").val()
			+ "&deptIdStr=" + $("#deptIdStr").val()//����Ȩ��
			+ "&agencyLimit=none"//�����������Ȩ�޴���
    }else{
    	url = "?model=asset_report_assetreport&action=toDetail"
    		+ "&dateType=" + $("#dateTypeHidden").val()
			+ "&beginDate=" + $("#beginDate").val()
			+ "&endDate=" + $("#endDate").val()
			+ "&company=" + $("#companyHidden").val()
			+ "&deptId=" + $("#deptId").val()
			+ "&deptName=" + $("#deptName").val()
			+ "&assetName=" + assetName
			+ "&agencyLimit=none"//�����������Ȩ�޴���
    }
	showModalWin(url);
}
