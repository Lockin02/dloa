var objTypeArr = [];// 业务类型数组

$(function() {
	objTypeArr = getData('YFRK');
	countAll();

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
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
		thisMoney = $("#money"+i).val()*1;
		if( thisMoney != 0 || thisMoney != ""){
			allAmount = accAdd(allAmount,thisMoney,2);
		}
	}

	$("#payMoney").val(allAmount);
	$("#payMoney_v").val(moneyFormat2(allAmount));
}



function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=addInPurcont&act=audit";
	}else{
		document.getElementById('form1').action="?model=finance_payablesapply_payablesapply&action=addInPurcont";
	}

}