//��ѯ
function search(){
	location.href = "?model=flights_report_flreport&action=page"
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
    var DetailType = RunningDetailGrid.Recordset.Fields.Item("detailType").AsString;
    var orgDetail = RunningDetailGrid.Recordset.Fields.Item("orgDetail").AsString;
	showOpenWin("?model=flights_report_flreport&action=toFlightsDetail"
		+ "&thisYear=" + thisYear
		+ "&DetailType=" + DetailType
		+ "&orgDetail=" + orgDetail,1,700,1000
	);
}