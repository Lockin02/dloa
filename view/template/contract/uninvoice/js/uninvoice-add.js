$(document).ready(function() {
	validate({
		"money" : {
			required : true
		}
	});

	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'uninvoice'
	});
});

//表单验证
function checkform(){
	//本次录入金额
	var thisMoney = $("#money").val()*1;
	var canUninvoiceMoney = $("#canUninvoiceMoney").val()*1;//可录不开票金额
	var uninvoiceMoney = $("#uninvoiceMoney").val()*1;//已录不开票金额
	var isRed = $("#isRed").val(); //是否红字

	if(canUninvoiceMoney <= 0 && isRed == '0'){
		alert('可录不开票金额为' + canUninvoiceMoney + '，当可录不开票金额大于0时，才可以正常录入!');
		return false;
	}

	if(canUninvoiceMoney < thisMoney && isRed == '0'){
		alert('可录不开票金额为' + canUninvoiceMoney + '，不能大于可录不开票金额!');
		return false;
	}

	if(uninvoiceMoney < thisMoney && isRed == '1'){
		alert('红字不开票金额只能小于或等于已录不开票金额' + uninvoiceMoney);
		return false;
	}

	if(thisMoney <= 0){
		alert('不开票金额只能输入正数，如需取消原不开票金额，是否红字一栏选择【是】即可！');
		return false;
	}

	if(!confirm('确认保存该不开票金额吗？')){
		return false;
	}

	return true;
}