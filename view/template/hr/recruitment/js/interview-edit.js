$(document).ready(function() {

	//�������Թ�
	$("#hrInterviewer").yxselect_user({
		hiddenId : 'hrInterviewerId',
		formCode : 'intHrInterviewer'
	});

	//��Ƹ����
	$("#hrCharger").yxselect_user({
		hiddenId : 'hrChargerId',
		formCode : 'intHrCharger'
	});

	//��Ƹ����
	$("#hrManager").yxselect_user({
		hiddenId : 'hrManagerId',
		formCode : 'intHrManager'
	});

	//���ʸ�����
	$("#manager").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'intManager'
	});

	//���ܾ���
	$("#deputyManager").yxselect_user({
		hiddenId : 'deputyManagerId',
		formCode : 'intDeputyManager'
	});

	// ��֤��Ϣ
	validate({
		"userName" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"sexy" : {
			required : true
		},
		"positionsName" : {
			required : true
		},
		"wageLevelCode" : {
			required : true
		}
	});
})