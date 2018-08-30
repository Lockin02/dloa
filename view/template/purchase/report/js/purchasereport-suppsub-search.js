//人员渲染
$(document).ready(function(){
	$("#thisYear").val($("#thisYearHide").val());
	$("#beginMonth").val($("#beginMonthHide").val()*1);
	$("#endMonth").val($("#endMonthHide").val()*1);

});

//查询
function confirm(){
	var thisYear=$("#thisYear").val();
	var beginMonth=$("#beginMonth").val();
	var endMonth=$("#endMonth").val();
	var suppId=$("#suppId").val();
	var suppName=$("#suppName").val();

	this.opener.location = "?model=purchase_report_purchasereport&action=toSupSubPage"
		+"&thisYear="+thisYear
		+"&beginMonth="+beginMonth
		+"&endMonth="+endMonth
		+"&suppId="+suppId
		+"&suppName="+suppName
	this.close();
}

//清空
function toClear(){
	$(".toClear").val("");
}