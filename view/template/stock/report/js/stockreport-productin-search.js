
$(function(){
	setSelect('beginYear');
	setSelect('endYear');
	setSelect('beginMonth');
	setSelect('endMonth');
	setSelect('isRed');
	setSelect('docStatus');

	// 供应商
	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		gridOptions : {
			showcheckbox : false
		}
	});
});

function confirm() {
	var productId = $("#productId").val();//物料代码
	var productCode = $("#productCode").val();//物料代码
	var beginYear=$("#beginYear").val();
	var beginMonth=$("#beginMonth").val();
	var endYear=$("#endYear").val();
	var endMonth=$("#endMonth").val();
	var docStatus=$("#docStatus").val();
	var isRed=$("#isRed").val();

	this.opener.location= "?model=stock_report_stockreport&action=toStockinProduct&beginMonth="+beginMonth
		+ "&beginYear="+beginYear
		+ "&endMonth="+endMonth+"&endYear="+endYear
		+ "&productId="+productId
		+ "&productCode="+productCode
		+ "&docStatus="+docStatus
		+ "&isRed="+isRed
	;
	this.close();
}

function refresh(){
	$(".clear").val("");
}