$(document).ready(function() {
	validate({
		"hospital" : {
			required : true
		},
		"checkDate" : {
			required : true
		},
		"checkResult" : {
			required : true
		},
		"userName" : {
			required : true
		}
	});
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		userNo : 'userNo',
		formCode : 'userName'
	});
	// 合同类型
	conTypeArr = getData('HRHTLX');
	addDataToSelect(conTypeArr, 'conType');
	// 合同状态
	conStateArr = getData('HRHTZT');
	addDataToSelect(conStateArr, 'conState');
	// 合同次数
	conNumArr = getData('HRHTCS');
	addDataToSelect(conNumArr, 'conNum');
});