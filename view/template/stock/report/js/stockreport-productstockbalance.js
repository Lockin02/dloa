//查询
function search(){
	//年份
	if($("#thisYear").val() == ""){
		alert('年份不能为空');
		return false;
	}

	location.href = "?model=stock_report_stockreport&action=toProductStockBalance"
	+ "&thisYear=" + $("#thisYear").val()
	;
}