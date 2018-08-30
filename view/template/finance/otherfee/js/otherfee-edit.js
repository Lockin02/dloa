$(document).ready(function() {
	//表单验证
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

	//人员选择
/*	$("#managerName").yxselect_user({
*		hiddenId : 'managerId',
*		formCode : 'stockinfo'
	});*/
})