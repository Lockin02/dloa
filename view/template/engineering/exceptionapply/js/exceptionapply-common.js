$(document).ready(function() {

	//��ѡ������
	$("#applyUserName").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'exceptionApply'
	});

	//�����
	$("#ExaUserName").yxselect_user({
		hiddenId : 'ExaUserId',
		formCode : 'exceptionApplyAudit'
	});

	//������Ŀ��Ⱦ
	$("#projectName").yxcombogrid_esmproject({
		hiddenId : 'projectId',
		nameCol : 'projectName',
		isShowButton : false,
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectCode").val(data.projectCode);
				}
			}
		}
	});

	validate({
		"applyReson" : {
			required : true
		},
		"applyDate" : {
			required : true
		},
		"applyUserName" : {
			required : true
		}
	});

	//�깺��Ⱦ���������֤
	if($("#applyType").val() == 'GCYCSQ-03'){
		validate({
			"products" : {
				required : true
			}
		});
	}
})


//���ñ�����״̬
function setExaStatus(ExaStatus){
	$("#ExaStatus").val(ExaStatus);
}

//����֤
function checkForm(){
	if($("#ExaUserName").val() == "" && $("#ExaStatus").val() == "�����"){
		alert('�ύ���ʱ������˲���Ϊ��');
		return false;
	}
}