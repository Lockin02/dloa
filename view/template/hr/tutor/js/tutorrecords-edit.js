$(document).ready(function() {
	//Ա��
//	$("#userName").yxselect_user({
//		hiddenId : 'userAccount',
//		userNo : 'userNo',
//		isGetDept : [true, "deptId", "deptName"],
//		isGetJob : [true, "jobId", "jobName"],
//		formCode : 'tutor',
//		event : {
//			"select" : function(obj,row){
//				//���������ʼ��ռ���
//				setMail();
//			}
//		}
//	});

	//ֱ���ϼ�
	$("#studentSuperior").yxselect_user({
		hiddenId : 'studentSuperiorId',
		event : {
			"select" : function(obj,row){
				//���������ʼ��ռ���
				setMail();
			}
		}
	});

	// ��֤��Ϣ
	validate({
		"userName" : {
			required : true
		},
		"studentSuperior" : {
			required : true
		},
		"beginDate" : {
			required : true
		}
	});

	//�ʼ�������
	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			mode : 'check',
			hiddenId : 'TO_ID',
			formCode : 'tutor'
		});
	}

	//�ʼ�������
	if($("#ADDIDS").length!=0){
		$("#ADDNAMES").yxselect_user({
			mode : 'check',
			hiddenId : 'ADDIDS',
			formCode : 'tutor'
		});
	}

	//��ʼ���ʼ�������
	setMail();
})

//�����ʼ��ռ���
function setMail(){
	//��ʼ���ʼ����ݻ���
	var idArr = [];
	var nameArr = [];

	//��ʦ����
	var userName = $("#userName").val();
	if(userName){
		var userAccount = $("#userAccount").val();

		idArr.push(userAccount);
		nameArr.push(userName);
	}

	//ѧԱ����
	var studentName = $("#studentName").val();
	if(studentName){
		var studentAccount = $("#studentAccount").val();

		idArr.push(studentAccount);
		nameArr.push(studentName);
	}

	//�ϼ�����
	var studentSuperior = $("#studentSuperior").val();
	if(studentSuperior){
		var studentSuperiorId = $("#studentSuperiorId").val();

		idArr.push(studentSuperiorId);
		nameArr.push(studentSuperior);
	}

	$("#TO_NAME").val(nameArr.toString());
	$("#TO_ID").val(idArr.toString());
}