//��ѯ
function search(){
	location.href = "?model=flights_report_flreport&action=toPro"
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
    var projectCode = RunningDetailGrid.Recordset.Fields.Item("proNum").AsString;
    var orgDetail = $("#orgDetail").val();
    var thisYear = $("#thisYear").val();
	var SelColNo = ReportViewer.SelColumnNo;
	if(!SelColNo){
		var month = 1
	}else{
		var month = SelColNo*1 - 3;
	}

	showOpenWin("?model=flights_report_flreport&action=messageDetail"
		+ "&thisYear=" + thisYear
		+ "&projectCode=" + projectCode
		+ "&orgDetail=" + orgDetail
		+ "&beginMonth=" + month
		+ "&endMonth=" + month,1,700,1000
	);
}