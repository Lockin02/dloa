$(document).ready(function() {
	var idArr = [];
	var nameArr = [];
	//Ա��
	$("#userName").yxselect_user({
		hiddenId : 'userAccount',
		formCode : 'tutor',
		event : {
			"select" : function(obj,row){
				$.ajax({
					type: "POST",
					url: "?model=hr_personnel_personnel&action=getPersonnelInfo",
					async: false,
					data: {"userAccount" :row.val },
					success: function(data){
			   			var dataObj = eval("(" + data +")");
						$("#userNo").val(dataObj.userNo);
						$("#deptId").val(dataObj.belongDeptId);
						$("#deptName").val(dataObj.belongDeptName);
						$("#jobId").val(dataObj.jobId);
						$("#jobName").val(dataObj.jobName);
					}
				});
				//���������ʼ��ռ���
				setMail();
			}
		}
//        isOnlyCurDept : true
	});
    //ֱ���ϼ�
	$("#studentSuperior").yxselect_user({
		hiddenId : 'studentSuperiorId',
		formCode : 'tutorSup',
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