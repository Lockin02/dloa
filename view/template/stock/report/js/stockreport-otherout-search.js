
$(function(){
	setSelect('beginYear');
	setSelect('endYear');
	setSelect('beginMonth');
	setSelect('endMonth');

	//客户
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});

	// 供应商
	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//领料人
	$("#pickName").yxselect_user({
		hiddenId : 'pickCode',
		formCode : 'stockreportPicking'
	});

	//领料部门
	$("#deptName").yxselect_dept({
		hiddenId : 'deptCode'
	});
});

function confirm() {

	var beginYear=$("#beginYear").val();
	var beginMonth=$("#beginMonth").val();
	var endYear=$("#endYear").val();
	var endMonth=$("#endMonth").val();
	var productId=$("#productId").val();
	var productCode=$("#productCode").val();
	var deptCode=$("#deptCode").val();
	var deptName=$("#deptName").val();
	var pickCode=$("#pickCode").val();
	var pickName=$("#pickName").val();
	var customerId=$("#customerId").val();
	var customerName=$("#customerName").val();

	this.opener.location= "?model=stock_report_stockreport&action=toStockoutOther&beginMonth="+beginMonth
		+ "&beginYear="+beginYear
		+ "&endMonth="+endMonth+"&endYear="+endYear
		+ "&productId="+productId
		+ "&productCode="+productCode
		+ "&deptCode="+deptCode
		+ "&deptName="+deptName
		+ "&pickCode="+pickCode
		+ "&pickName="+pickName
		+ "&customerId="+customerId
		+ "&customerName="+customerName
	;
	this.close();
}

function refresh(){
	$(".clear").val("");
}