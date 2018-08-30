$(function() {
    $("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'payablesdetail',
		isGetDept : [true, "deptId", "deptName"]
	});
    $("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});


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

	var salesmanId=$("#salesmanId").val();
	var salesman=$("#salesman").val();

	var deptId=$("#deptId").val();
	var deptName=$("#deptName").val();

	var supplierName=$("#supplierName").val();

	var payContent=$("#payContent").val();

	var objType=$("#objType").val();

	var objCode=$("#objCode").val();
	var amount=$("#amount").val();

	this.opener.location = "?model=finance_payable_payable&action=payablesDetail"
		+"&beginMonth="+beginMonth
		+"&beginYear="+beginYear
		+"&endMonth="+endMonth
		+"&endYear="+endYear

		+"&salesmanId="+salesmanId
		+"&salesman="+salesman

		+"&deptName="+deptName
		+"&deptId="+deptId

		+"&supplierName="+supplierName

		+"&payContent="+payContent
		+"&amount="+amount

		+"&objType="+objType

		+"&objCode="+objCode
	;
	this.close();
}

function toClear(){
	$('.toClear').val("");
	$("#amount").val("");
}
