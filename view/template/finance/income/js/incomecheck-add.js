$(document).ready(function() {
	validate({
		"checkDate" : {
			required : true
		},
		"checkMoney_v" : {
			required : true
		},
		"contractCode" : {
			required : true
		},
		"incomeNo" : {
			required : true
		}
	});

	//��Ⱦ��ͬ���
	buildInputSet('contractCode','contract');

	//��Ⱦ�����
	buildInputSet('incomeNo','income');
});