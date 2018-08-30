
//人员渲染
$(document).ready(function(){
    $("#prinvipalName").yxselect_user({
		hiddenId : 'prinvipalId',
		formCode : 'invoice'
	});
    $("#areaPrincipal").yxselect_user({
		hiddenId : 'areaPrincipalId',
		formCode : 'invoice'
	});

	$("#customerProvince").yxcombogrid_province({
		hiddenId : 'customerProvinceId',
		height : 200,
		width : 400,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#customerName").yxcombogrid_customer({
		isShowButton : false,
		hiddenId : 'customerId',
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false
		}
	});

	//区域
	$("#areaName").yxcombogrid_area({
		hiddenId : 'areaId',
		width : 550,
		isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false
		}
	});
	// 客户类型
	customerTypeArr = getData('KHLX');
	addDataToSelect(customerTypeArr, 'customerType');
	$("#customerType").val($("#customerTypeHide").val()*1);

	$("#beginYear").val($("#beginYearHide").val());
	$("#beginMonth").val($("#beginMonthHide").val()*1);
	$("#endYear").val($("#endYearHide").val());
	$("#endMonth").val($("#endMonthHide").val()*1);
});


function confirm() {
	var beginYear=$("#beginYear").val();
	var beginMonth=$("#beginMonth").val();
	var endYear=$("#endYear").val();
	var endMonth=$("#endMonth").val();

	var areaName=$("#areaName").val();
	var areaId=$("#areaId").val();

	var customerId=$("#customerId").val();
	var customerName=$("#customerName").val();
	var customerType=$("#customerType").val();
	var customerProvince = $("#customerProvince").val();

	var orderCode=$("#orderCode").val();

	var prinvipalId=$("#prinvipalId").val();
	var prinvipalName=$("#prinvipalName").val();

	var areaPrincipalId=$("#areaPrincipalId").val();
	var areaPrincipal=$("#areaPrincipal").val();

	var incomeMoney = $("#incomeMoney").val();
	var invoiceMoney=$("#invoiceMoney").val();

	this.opener.location = "?model=finance_receviable_receviable&action=incomeSummary"
		+ "&beginMonth="+beginMonth
		+ "&beginYear="+beginYear
		+ "&endMonth="+endMonth
		+ "&endYear="+endYear

		+ "&areaName="+areaName
		+ "&areaId="+areaId

		+ "&customerId="+customerId
		+ "&customerName="+customerName
		+ "&customerType="+customerType
		+ "&customerProvince=" + customerProvince

		+ "&orderCode="+orderCode

		+ "&prinvipalId="+prinvipalId
		+ "&prinvipalName="+prinvipalName

		+ "&areaPrincipalId="+areaPrincipalId
		+ "&areaPrincipal="+areaPrincipal

		+ "&incomeMoney="+incomeMoney
		+ "&invoiceMoney=" + invoiceMoney;
	this.close();
}

function toClear(){
	$('.toClear').val("");

	$('#incomeMoney').val("");
	$('#invoiceMoney').val("");
}
