//��ѯ
function search(){
	location.href = "?model=flights_report_flreport&action=toSel"
	+ "&thisYear=" + $("#thisYear").val()
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


//��Ӧ������˫���¼����򿪵�ǰ�ж�Ӧ����ϸ����
function OnContentCellDblClick(Sender){
	//ֱ�ӵ�����ϸ������ϸ
    searchDetail();
}

//�򿪵�ǰ�ж�Ӧ����ϸ����
function searchDetail(){
    //�����Ӧ�ô� RunningDetailGrid ȡ��͸����
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var thisYear = $("#thisYear").val();
    var orgDetail = $("#orgDetail").val();
    var projectCode = RunningDetailGrid.Recordset.Fields.Item("projectCode").AsString;
    var chanceCode = RunningDetailGrid.Recordset.Fields.Item("chanceCode").AsString;
    var costBelonger = RunningDetailGrid.Recordset.Fields.Item("costBelonger").AsString;
    var province = RunningDetailGrid.Recordset.Fields.Item("province").AsString;
	var SelColNo = ReportViewer.SelColumnNo;
	if(!SelColNo){
		var month = 1
	}else{
		var month = SelColNo*1 - 5;
	}

	showOpenWin("?model=flights_report_flreport&action=messageDetail"
		+ "&thisYear=" + thisYear
		+ "&orgDetail=" + orgDetail
		+ "&projectCode=" + projectCode
		+ "&chanceCode=" + chanceCode
		+ "&costBelonger=" + costBelonger
		+ "&province=" + province
		+ "&beginMonth=" + month
		+ "&endMonth=" + month,1,700,1000
	);
}