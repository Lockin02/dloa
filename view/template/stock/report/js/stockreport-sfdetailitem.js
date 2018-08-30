function searchBtn() {
	var year = $("#year").val();
	if(year == ""){
		alert("开始年份不能为空");
		return false;
	}
	var month = $("#month").val();
	if(month == ""){
		alert("开始月份不能为空");
		return false;
	}
	var endYear = $("#endYear").val();
	if(endYear == ""){
		alert("结束月份不能为空");
		return false;
	}
	var endMonth = $("#endMonth").val();
	if(endMonth == ""){
		alert("结束月份不能为空");
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