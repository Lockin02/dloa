function searchBtn() {
	var year = $("#year").val();
	if(year == ""){
		alert("��ʼ��ݲ���Ϊ��");
		return false;
	}
	var month = $("#month").val();
	if(month == ""){
		alert("��ʼ�·ݲ���Ϊ��");
		return false;
	}
	var endYear = $("#endYear").val();
	if(endYear == ""){
		alert("�����·ݲ���Ϊ��");
		return false;
	}
	var endMonth = $("#endMonth").val();
	if(endMonth == ""){
		alert("�����·ݲ���Ϊ��");
		return false;
	}
	location.href = "?model=stock_report_stockreport&action=toProSFDetailReport&year="
		+ year
		+ "&month=" + month
		+ "&endYear=" + endYear
		+ "&endMonth=" + endMonth
		+ "&productNo=" + $("#productNo").val()
		+ "&k3Code=" + $("#k3Code").val();
}