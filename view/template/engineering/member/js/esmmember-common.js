

//��������Ԥ��
function calPerson(){
	//��ȡ����
	var feeDay = $("#feeDay").val();
	if($("#memberName").val() != "" && feeDay != ""){
		//��ȡ����ϵ��
		var coefficient = $("#coefficient").val();
		//��ȡ����
		var price = $("#price").val();
		//�����˹�����
		setMoney('feeDay',feeDay,2);

		//�����˹�����
		var feePeople = accMul(coefficient,feeDay,2);
		setMoney('feePeople',feePeople,2);
		//�����˹�����
		var feePerson = accMul(price,feeDay,2);
		setMoney('feePerson',feePerson,2);
	}
}

//����������رղ���֤
function timeCheck($t){
	var startDate = $('#beginDate').val();
	var endDate = $('#endDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate) + 1;
	if(s < 0) {
		alert("��ʼʱ�䲻�ܱȽ���ʱ����");
		$t.value = "";
		return false;
	}
	setMoney('feeDay',s,2);
}
