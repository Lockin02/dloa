$(document).ready(function(){
	//��ʼ��ʡ�ݳ�����Ϣ
	initCity();
});

$(document).ready(function(){
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"planEndDate" : {
			required : true
		},
		"newBudgetOther_v" : {
			required : true
		},
		"newBudgetField_v" : {
			required : true
		},
		"changeDescription" : {
			required : true
		}
	});
});
