//��ѯ
function search(){
	location.href = "?model=flights_report_flreport&action=toSeled"
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
    var contractCode = RunningDetailGrid.Recordset.Fields.Item("contractCode").AsString;
    var orgDetail = $("#orgDetail").val();
	var SelColNo = ReportViewer.SelColumnNo;
	if(!SelColNo){
		var month = 1
	}else{
		var month = SelColNo*1 - 3;
	}
	showOpenWin("?model=flights_report_flreport&action=messageDetail"
		+ "&thisYear=" + thisYear
		+ "&contractCode=" + contractCode
		+ "&orgDetail=" + orgDetail
		+ "&beginMonth=" + month
		+ "&endMonth=" + month,1,700,1000
	);
}