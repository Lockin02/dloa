$(document).ready(function() {
//	//Ա��
//	$("#userName").yxselect_user({
//		hiddenId : 'userAccount',
//		isGetDept : [true, "deptId", "deptName"],
//		formCode : 'userName'
//	});

			validate({
					"jobName" : {
						required : true
					}
	 		});



	$("#jobName").yxcombogrid_jobs({
		hiddenId : 'jobId'
	});
});

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