//����֤����
function checkform(){
	if($("#inputExcel").val() =="" ){
		alert("��ѡ����Ҫ�����EXCEL�ļ�");
		return false;
	}

//	alert("��ǰ����δ���");
	$("#loading").show();

	return true;
}

function changeIsCheck(thisVal){
	$("#isCheck").val(thisVal);
}