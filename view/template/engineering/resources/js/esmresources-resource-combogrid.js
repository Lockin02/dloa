

//�����豸���
function calResource(){
	//��ȡ����
	var number = $("#number").val();
	//��ȡ����
	var price = $("#price").val();
	//��ȡ����
	var useDays = $("#useDays").val();
	if( number != "" && price != "" && useDays != "" ){
		//���㵥���豸���
		var amount = accMul(number,price,2);
		//��������豸���
		var amount = accMul(amount,useDays,2);

		setMoney('amount',amount,2);
	}
}

//�����豸��� - �������� - ���� ������ʹ��
function calResourceBatch(rowNum){
	//�ӱ�ǰ���ַ���
	var beforeStr = "importTable_cmp";
	//��ȡ��ǰ����
	var number= $("#"+ beforeStr + "_number" + rowNum ).val();

	if($("#" + beforeStr + "_resourceName"  + rowNum ).val() != "" && number != ""){
		//��ȡ����
		var price = $("#" + beforeStr +  "_price" + rowNum + "_v").val();
		//��ȡ����
		var useDays = $("#" + beforeStr +  "_useDays" + rowNum ).val();
		//���㵥���豸���
		var amount = accMul(number,price,2);

		//��������豸���
		var amount = accMul(useDays,amount,2);

		setMoney(beforeStr +  "_amount" +  rowNum,amount,2);
	}
}

/**
 * Ԥ�ƽ����Ԥ�ƹ黹���ڲ���֤��ʹ�������ļ���
 * @param {} $t
 * @return {Boolean}
 */
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
	$("#useDays").val(s);
}