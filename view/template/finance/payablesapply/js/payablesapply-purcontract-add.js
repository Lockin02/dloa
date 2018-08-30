$(function() {
	countAll();

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		isGetDept : [true, "deptId", "deptName"]
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId',
		unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : ''
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
	$("#payMoneyView").val(moneyFormat2(allAmount));
}

//修改审批付款日期

function changeAuditDate(){
	var payDate = $("#payDate").val();
	$("#auditDate").val(payDate);
	if($("#sourceType").val() == 'YFRK-01'){// 采购付款申请,当小于预计付款日期，则带出【提前付款原因】给申请人填写
		var planPayDate = $("#planPayDate").val();
		if(planPayDate > payDate){
			$("#planPayDateTd").removeAttr("colspan");
			$(".payEarlyReasonBox").show();
			$("#needPayEarlyReason").val(1);
		}else{
			$(".payEarlyReasonBox").hide();
			$("#planPayDateTd").attr("colspan",3);
			$("#needPayEarlyReason").val('');
            $("#payEarlyReason").val('');
		}
	}
}