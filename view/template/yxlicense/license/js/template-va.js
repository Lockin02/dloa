function checkform(){
	if($("#name").val() == ""){
		alert("���Ʋ���Ϊ��");
		return false;
	}

	if($("#licenseType").val() == ""){
		alert("��Ӧlicense���Ͳ���Ϊ��");
		return false;
	}

	if($("#thisVal").val() == "" && $("#extVal").val() == ""){
		alert("û�ж�ģ����и�ֵ");
		return false;
	}
	//�������ñ��水ť
	$("#savebtn").attr("disabled", true);
	return true;
}
 