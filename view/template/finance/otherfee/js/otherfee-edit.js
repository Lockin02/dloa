$(document).ready(function() {
	//����֤
	validate({
		"accountYear" : {
			required : true
		},
		"accountPeriod" : {
			required : true
		},
		"summary" : {
			required : false
		},
		"subjectName" : {
			required : true
		},
		"debit_v" : {
			required : true,
			process : function(v){
				return moneyFormat2(v);
				}
		},
		"chanceCode" : {
			required : false
		},
		"trialProjectCode" : {
			required : false
		},
		"feeDeptName" : {
			required : true
		},
		"contractCode" : {
			required : false
		},
		"province" : {
			required : false
		}
	});

	//��Աѡ��
/*	$("#managerName").yxselect_user({
*		hiddenId : 'managerId',
*		formCode : 'stockinfo'
	});*/
})