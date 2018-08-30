$(document).ready(function() {
	//初始化下拉选择
	getInitData();

	//表单验证
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