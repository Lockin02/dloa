$(document).ready(function() {
	if ($("#projectName").val() == '') {
		$("#customerName").parent().show().prev().show(); //�ͻ�����
		$("#saleUserId").parent().show().prev().show(); //���۸�����
		$("#salesExplain").parent().show().prev().show(); //����˵��
		toView();
	} else {
		//�������ŵĲ鿴
		$("#projectName").parent().show().prev().show(); //��Ŀ����
		toViewDepartment();
	}
});