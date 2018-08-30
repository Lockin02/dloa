$(document).ready(function(){
	//初始化省份城市信息
	initCity();
});

$(document).ready(function(){
	/**
	 * 验证信息
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
