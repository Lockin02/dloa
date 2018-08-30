$(document).ready(function(){
	$("#preMilestoneName").yxcombogrid_milestone({
		hiddenId : 'preMilestoneId',
		gridOptions : {
			param  : {'projectId' : $("#projectId").val()}
		}
	});

	//��ʱ�����
	if(actBeginDate=="0000-00-00"){
		$("#actBeginDate").val("");
	};
	if(actEndDate=="0000-00-00"){
		$("#actEndDate").val("");
	}

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

//��ʼ������رղ���֤
function actTimeCheck($t){
	var s = plusDateInfo('actBeginDate','actEndDate');
	if(s <= 0) {
		alert("��ʼʱ�䲻�ܱȽ���ʱ����");
		$t.value = "";
		return false;
	}
}