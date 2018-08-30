$(document).ready(function(){
	$("#preMilestoneName").yxcombogrid_milestone({
		hiddenId : 'preMilestoneId',
		gridOptions : {
			param  : {'projectId' : $("#projectId").val()}
		}
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"milestoneName" : {
			required : true,
			length : [0,100]
		},
		"versionNo" : {
			custom : ['onlyNumber']
		},
		"planBeginDate" : {
			required : true,
			custom : ['date']
		},
		"planEndDate" : {
			required : true,
			custom : ['date']
		},
		"actBeginDate" : {
			required : false,
			custom : ['date']
		},
		"actEndDate" : {
			required : false,
			custom : ['date']
		}
	});
});

//��ʼ�������ɲ���֤
function timeCheck($t){
	var s = plusDateInfo('planBeginDate','planEndDate');
	if(s <= 0) {
		alert("��ʼʱ�䲻�ܱ����ʱ����");
		$t.value = "";
		return false;
	}
}