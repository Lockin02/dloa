function checkform(){
	if($("#stockName").val() == ""){
		alert('��ѡ��ֿ�����');
		return false;
	}
	if($("#stockId").val() == ""){
		alert('û����ȷѡ��ֿ�,������ѡ��');
		return false;
	}
	if($("#formDate").val() == ""){
		alert('��ѡ������');
		return false;
	}
	return true;
}