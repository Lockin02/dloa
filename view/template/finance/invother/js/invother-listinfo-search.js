$(function(){
	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'invothersales'
	});

	$("#exaMan").yxselect_user({
		hiddenId : 'exaManId',
		formCode : 'invotherexa'
	});

	setSelect('ExaStatus');
});

function confirm(){
	var formDateBegin=$("#formDateBegin").val();
	var formDateEnd=$("#formDateEnd").val();
	var supplierName=$("#supplierName").val();
	var invoiceNo=$("#invoiceNo").val();
	var salesmanId =$("#salesmanId").val();
	var salesman=$("#salesman").val();
	var exaManId=$("#exaManId").val();
	var exaMan=$("#exaMan").val();
	var invType = $("#invType").val();
	var ExaStatus=$("#ExaStatus").val();
	var productName=$("#productName").val();
	this.opener.location = "?model=finance_invother_invother&action=listinfo"
						+ "&formDateBegin="+formDateBegin
						+ "&formDateEnd="+formDateEnd
						+ "&supplierName="+supplierName
						+ "&invoiceNo="+invoiceNo
						+ "&salesmanId="+salesmanId
						+ "&salesman="+salesman
						+ "&exaManId="+exaManId
						+ "&exaMan="+exaMan
						+ "&invType="+invType
						+ "&ExaStatus=" + ExaStatus
						+ "&productName=" + productName;
	this.close();
}

function refresh(){
	$(".toClear").val('');
}