function checkform(){
	var checkedObj = $('#invoiceType');

	if($('#docId').val() == ""){
		alert('��ѡ����Ҫ�ʼĵķ�Ʊ');
		return false;
	}

	if($('#customerId').val() == ""){
		alert('��ѡ��ͻ�');
		return false;
	}

	if($('#receiver').val() == ""){
		alert('�ռ�����Ҫ��д');
		return false;
	}

	if($('#mailNo').val() == ""){
		alert('�ʼĵ�����Ҫ��д');
		return false;
	}

	if($('#logisticsId').val() == ""){
		alert('��ѡ��������˾');
		return false;
	}

	if($('#address').val() == ""){
		alert('�ʼĵ�ַ��Ҫ��д');
		return false;
	}

	return true;
}