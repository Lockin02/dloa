//��ѯ
function search(){
	//���
	if($("#thisYear").val() == ""){
		alert('��ݲ���Ϊ��');
		return false;
	}

	location.href = "?model=stock_report_stockreport&action=toProductStockBalance"
	+ "&thisYear=" + $("#thisYear").val()
	;
}