$(document).ready(function() {
	//��ʼ������ѡ��
	getInitData();

	//����֤
	validate({
		"configName" : {
			required : true
		},
		"dateCode" : {
			required : true
		},
		"days" : {
			required : true
		}
	});
});