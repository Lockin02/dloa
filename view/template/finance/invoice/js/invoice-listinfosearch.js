function toSupport() {
	var beginYear=$("#beginYear").val();
	var beginMonth=$("#beginMonth").val();
	var endYear=$("#endYear").val();
	var endMonth=$("#endMonth").val();

	var customerId=$("#customerId").val();
	var customerName=$("#customerName").val();
	var areaName=$("#areaName").val();
	var customerProvince=$("#customerProvince").val();
	var customerType=$("#customerType").val();
	var invoiceNo=$("#invoiceNo").val();
	var salesmanId=$("#salesmanId").val();
	var salesman=$("#salesman").val();
	var orderCode=$("#orderCode").val();
	var signSubjectName=$("#signSubjectName").val();

	this.opener.location = "?model=finance_invoice_invoice&action=toListInfo"
		+"&beginMonth="+beginMonth
		+"&beginYear="+beginYear
		+"&endMonth="+endMonth
		+"&endYear="+endYear
		+"&customerId="+customerId
		+"&customerName="+customerName
		+"&areaName="+areaName
		+"&customerProvince="+customerProvince
		+"&customerType="+customerType
		+"&invoiceNo="+invoiceNo
		+"&salesmanId="+salesmanId
		+"&salesman="+salesman
		+"&objCodeSearch="+orderCode
		+"&signSubjectName="+signSubjectName
	this.close();
}

function refresh(){
	$('.toClear').val("");
}

$(function(){
	// 客户类型
	customerTypeArr = getData('KHLX');
	addDataToSelect(customerTypeArr, 'customerType');

	$("#customerType").val($("#customerTypeHidden").val());

	//区域渲染
	$("#areaName").yxcombogrid_area({
		width : 500,
		gridOptions : {
			showcheckbox : false
		}
	});
	//业务员渲染
    $("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'invoice'
	});
	//省份渲染
	$("#customerProvince").yxcombogrid_province({
		height : 200,
		width : 400,
		gridOptions : {
			showcheckbox : false
		}
	});

	//客户
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		height : 250,
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#beginYear").val($("#beginYearHide").val());
	$("#beginMonth").val($("#beginMonthHide").val()*1);
	$("#endYear").val($("#endYearHide").val());
	$("#endMonth").val($("#endMonthHide").val()*1);
});