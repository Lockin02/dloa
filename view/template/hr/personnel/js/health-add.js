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
	// ��ͬ����
	conTypeArr = getData('HRHTLX');
	addDataToSelect(conTypeArr, 'conType');
	// ��ͬ״̬
	conStateArr = getData('HRHTZT');
	addDataToSelect(conStateArr, 'conState');
	// ��ͬ����
	conNumArr = getData('HRHTCS');
	addDataToSelect(conNumArr, 'conNum');
});