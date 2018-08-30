$(document).ready(function() {
		var url = "?model=hr_contract_contract&action&action=checkRepeat";

		validate({
					"userName" : {
						required : true
					}
	 		});

	//Ա��
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		formCode : 'userName',
		event : {
			select : function(e,row){
							$.ajax({
							    type: "POST",
							    url: "?model=hr_personnel_personnel&action=getPersonnelSimpleInfo_d",
							    data: {"userAccount" : row.val },
							    async: false,
							    success: function(data){
							   		if(data != ""){
							   			var dataObj = eval("(" + data +")");
							   			$("#userNo").val(dataObj.userNo);
							   			$("#jobName").val(dataObj.jobName);
							   			$("#jobId").val(dataObj.jobId);
							   			$("#trialBeginDate").val(dataObj.entryDate);
							   			$("#trialEndDate").val(dataObj.becomeDate);
							   		}
								}
							});
					}
		}
	});
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