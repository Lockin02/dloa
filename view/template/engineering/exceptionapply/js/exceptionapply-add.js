$(document).ready(function() {

	//��ѡ������
	$("#applyUserName").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [true, "deptId", "deptName"]
	});

	//������Ŀ��Ⱦ
	$("#projectName").yxcombogrid_esmproject({
		hiddenId : 'projectId',
		nameCol : 'projectCode',
		isShowButton : false,
		height : 250,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectCode").val(data.projectName);
				}
			}
		}
	});

	validate({
		"applyMoney_v" : {
			required : true
		},
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
})