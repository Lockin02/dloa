$(function() {
	countAll();

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});
	
	//�Ƿ�ί�и���
	var isEntrust = $("#isEntrust").val();
	if(isEntrust == 1){
		$("#isEntrustYes").attr('checked',true);
		$("#isEntrustYes").attr('checked',true);
		$("#bank").val('�Ѹ���');
		$("#bank").attr('class','readOnlyTxtNormal');
		$("#bank").attr('readonly',true);
		$("#account").val('�Ѹ���');
		$("#account").attr('class','readOnlyTxtNormal');
		$("#account").attr('readonly',true);
	}else{
		$("#isEntrustNo").attr('checked',true);
	}

	//�Ƿ񿪾ݷ�Ʊ
	var isInvoice = $("#isInvoice").val();
	if(isInvoice == 1){
		$("#isInvoiceYes").attr('checked',true);
	}
	else{
		$("#isInvoiceNo").attr('checked',true);
	}

});

//�����ܽ��
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
	$("#payMoneyView").val(moneyFormat2(allAmount));
}