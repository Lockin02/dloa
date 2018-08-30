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

//�����ܽ��
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

//�޸�������������

function changeAuditDate(){
	var payDate = $("#payDate").val();
	$("#auditDate").val(payDate);
	if($("#sourceType").val() == 'YFRK-01'){// �ɹ���������,��С��Ԥ�Ƹ������ڣ����������ǰ����ԭ�򡿸���������д
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