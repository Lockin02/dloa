$(document).ready(function() {
	//
//		var url = "?model=hr_contract_contract&action&action=checkRepeat";
//		$("#conNo").ajaxCheck({
//			url : url,
//			alertText : "* �ú�ͬ����Ѵ���",
//			alertTextOk : "* ��ͬ��ſ���"
//		});
		validate({
					"userName" : {
						required : true
					},
//					"beginDate" : {
//						required : true
//					},
//					"closeDate" : {
//						required : true
//					},
					"jobName" : {
						required : true
					}
	 		});

	//Ա��
//	$("#userName").yxselect_user({
//		hiddenId : 'userAccount',
//		userNo : 'userNo',
//		formCode : 'userName'
//	});
//	$("#jobName").yxcombogrid_jobs({
//		hiddenId : 'jobId'
//	});
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

function checkForm() {
//	if ($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "�����κθ���") {
//		alert('���ϴ�������');
//		return false;
//	}
	return true;
}
    //��ʼʱ�������ʱ�����֤
function timeCheck($t){
	var s = plusDateInfo('beginDate','closeDate');
	if(s < 0) {
		alert("��ʼʱ�䲻�ܱȽ���ʱ����");
		$t.value = "";
		return false;
	}
}

    //���ÿ�ʼʱ�������ʱ�����֤
function trialTimeCheck($t){
	var s = plusDateInfo('trialBeginDate','trialEndDate');
	if(s < 0) {
		alert("���ÿ�ʼʱ�䲻�ܱ����ý���ʱ����");
		$t.value = "";
		return false;
	}
}