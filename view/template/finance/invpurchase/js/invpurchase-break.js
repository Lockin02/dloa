function checkform(){
	var invnumber = $('#invnumber').val();
	var changeMark = false;

	for(var i = 1;i<= invnumber ; i++){
		if($('#breakNumber' + i).val() != 0 ){
			changeMark = true;
		}
	}

	if(changeMark){
		return true;
	}
	alert('拆分条目数量需要填写');
	return false;
}

function checkThis(arg1,arg2){
	var obj1 = $('#'+arg1);
	var obj2 = $('#'+arg2);
	if(isNaN(obj1.val())){
		alert('请输入数字');
		obj1.val(0);
		return false;
	}else if( obj1.val()*1 < 0 ){
		alert('请输入正数');
		obj1.val(0);
		return false;
	}else if(obj1.val()*1 >= obj2.val()*1){
		alert('拆分数不能超过或等于原数量');
		obj1.val(0);
		return false;
	}
}

function countAll(){
	var invnumber = $('#invnumber').val();
	allAmount = 0;
	allCount = 0;
	for(var i = 1 ;i<= invnumber ;i++){
		//基本数量价格
		breakNumber = $("#breakNumber"+i).val()*1;
		price = $("#price"+i).val()*1;
		taxPrice = $("#taxPrice"+i).val()*1;

		//计算部分
		allAmount = accAdd(allAmount,accMul(breakNumber,price,2),2);
		allCount = accAdd(allCount,accMul(breakNumber,taxPrice,2),2);
	}
	allAssessment = accSub(allCount,allAmount,4);
	$('#formAssessment').val(allAssessment);
	$('#formCount').val(allCount);
	$('#allAmount').val(allAmount);
	$('#formNumber').val(breakNumber);
}