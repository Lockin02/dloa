$(document).ready(function() {
	if ($("#projectName").val() == '') {
		$("#customerName").parent().show().prev().show(); //�ͻ�����
		$("#saleUserId").parents("tr:first").show(); //���۸�����
		$("#salesExplain").parents("tr:first").show(); //����˵��
		toView();
	} else {
		//�������ŵĲ鿴
		$("#projectName").parent().show().prev().show(); //��Ŀ����
		toViewDepartment();
	}
});