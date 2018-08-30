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

	//渲染合同编号
	buildInputSet('contractCode','contract');

	//渲染到款单号
	buildInputSet('incomeNo','income');
});