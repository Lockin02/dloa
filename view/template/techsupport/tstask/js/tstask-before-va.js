function checkForm(){
	if($("#objId").val() == ''){
		alert('��ѡ�������Ŀ');
		return false;
	}

	if($("#salesman").val() == ''){
		alert('��ѡ�����۸�����');
		return false;
	}

	if($("#trainDate").val() == ''){
		alert('��ѡ����ʱ��');
		return false;
	}

	if($("#customerId").val() == ''){
		alert('��ѡ��ͻ�');
		return false;
	}

	if($("#trainAddress").val() == ''){
		alert('����д������ַ');
		return false;
	}

	if($("#cusLinkman").val() == ''){
		alert('��ѡ��ͻ���ϵ��');
		return false;
	}

	if($("#cusLinkPhone").val() == ''){
		alert('����д�ͻ���ϵ�绰');
		return false;
	}
}

