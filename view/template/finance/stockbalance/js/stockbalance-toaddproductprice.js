$(function(){
	$("#thisMonth").val($("#thisMonthHide").val());
});
//����֤����
function checkform(){
	if($("#thisYear").val() =="" ){
		alert("�����Ҫ��д");
		return false;
	}

	if($("#inputExcel").val() =="" ){
		alert("��ѡ����Ҫ�����EXCEL�ļ�");
		return false;
	}

//	alert("��ǰ����δ���");
	$("#loading").show();

	return true;
}