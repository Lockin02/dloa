//人员渲染
$(document).ready(function(){
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		isShowButton : false,
		isFocusoutCheck : false,
		height : 250,
		width : 600,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#beginYear").val($("#beginYearHide").val());
	$("#beginMonth").val($("#beginMonthHide").val()*1);
	$("#endYear").val($("#endYearHide").val());
	$("#endMonth").val($("#endMonthHide").val()*1);

});

//查询
function confirm(){
	var beginYear=$("#beginYear").val();
	var beginMonth=$("#beginMonth").val();
	var endYear=$("#endYear").val();
	var endMonth=$("#endMonth").val();

	var supplierId=$("#supplierId").val();
	var supplierName=$("#supplierName").val();

	this.opener.location = "?model=finance_payable_payable&action=toSupplierPayables"
		+"&beginMonth="+beginMonth
		+"&beginYear="+beginYear
		+"&endMonth="+endMonth
		+"&endYear="+endYear

		+"&supplierId="+supplierId
		+"&supplierName="+supplierName
	this.close();
}

//清空
function toClear(){
	$(".toClear").val("");
}