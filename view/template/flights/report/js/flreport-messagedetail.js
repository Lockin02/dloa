
//���ʱ��
function OnContentCellDblClick(event){
	var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
	var id=RunningDetailGrid.Recordset.Fields.Item("id").AsInteger;
	showModalWin("?model=flights_message_message&action=toView&id=" + id,1,id);
}

//��ѯ
function search(){
	location.href = "?model=flights_report_flreport&action=messageDetail"
		+ "&thisYear=" + $("#thisYear").val()
		+ "&beginMonth=" + $("#beginMonth").val()
		+ "&endMonth=" + $("#endMonth").val()
		+ "&costBelongDeptId=" + $("#costBelongDeptId").val()
	;
}


//����
function checkYear(thisKey){
	var thisObj = $("#" + thisKey);
	if(thisObj.val() == ""){
		alert('��ݲ���Ϊ��');
		thisObj.val($("#" + thisKey + "Hidden").val());
	}
}

//�·ݼ��
function checkMonth(thisKey){
	var thisObj = $("#" + thisKey);
	if(thisObj.val() * 1 > 12 || thisObj.val() * 1 < 1){
		alert('�·��������');
		thisObj.val($("#" + thisKey + "Hidden").val());
	}
}