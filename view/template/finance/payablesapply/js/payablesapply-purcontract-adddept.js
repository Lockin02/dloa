$(function() {

	// 单选客户
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		isGetDept : [true, "deptId", "deptName"]
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});
});

//计算总金额
function countAll(){
	var invnumber = $('#coutNumb').val();
	var thisAmount = 0;
	var allAmount = 0;
	for(var i = 1;i<= invnumber ; i++ ){
		if($("#money"+ i).length == 0) continue;
		thisMoney = $("#money"+i).val()*1;
		if( thisMoney != 0 || thisMoney != ""){
			allAmount = accAdd(allAmount,thisMoney,2);
		}
	}
	$("#payMoney").val(allAmount);
	$("#payMoney_v").val(moneyFormat2(allAmount));
}