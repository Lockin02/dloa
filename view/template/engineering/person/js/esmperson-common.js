//����������رղ���֤
function timeCheck($t){
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
	if(s < 0) {
		alert("Ԥ�ƿ�ʼ���ܱ�Ԥ�ƽ���ʱ����");
		$t.value = "";
		return false;
	}
	$("#days").val(s);
}




//��������Ԥ��
function calPerson(){
	//��ȡ����
	var number = $("#number").val();
	if($("#personLevel").val() != "" && number != ""){
		//��ȡ����ϵ��
		var coefficient = $("#coefficient").val();
		//��ȡ����
		var price = $("#price").val();
		//��ȡ����
		var days = $("#days").val();
		//�����˹�����
		var personDays = accMul(number,days,2);
		$("#personDays").val(personDays);

		//�����˹�����
		var personCostDays = accMul(coefficient,accMul(number,days,2),2);
		$("#personCostDays").val(personCostDays);

		//�����˹�����
		var personCost = accMul(price,personDays,2);
		$("#personCost").val(personCost);
	}
}


//��������Ԥ�� - ����������ʹ��
function calPersonBatch(rowNum){
	//�ӱ�ǰ���ַ���
	var beforeStr = "importTable_cmp";
	//��ȡ��ǰ����
	var number= $("#"+ beforeStr + "_number" + rowNum ).val();

	if($("#" + beforeStr + "_personLevel"  + rowNum ).val() != "" && number != ""){
		//��ȡ����ϵ��
		var coefficient = $("#" + beforeStr +  "_coefficient" + rowNum).val();
		//��ȡ����
		var price = $("#" + beforeStr +  "_price" + rowNum).val();
		//��ȡ����
		var days = $("#" + beforeStr +  "_days" + rowNum ).val();
		//�����˹�����
		var personDays = accMul(number,days,2);
		$("#" + beforeStr +  "_personDays" + rowNum).val(personDays);

		//�����˹�����
		var personCostDays = accMul(coefficient,accMul(number,days,2),2);
		$("#" + beforeStr +  "_personCostDays" +  rowNum).val(personCostDays);

		//�����˹�����
		var personCost = accMul(price,personDays,2);
		setMoney(beforeStr +  "_personCost" +  rowNum,personCost,2);
	}
}