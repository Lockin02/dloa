$(document).ready(function() {

	//单选申请人
	$("#applyUserName").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [true, "deptId", "deptName"]
	});

	//工程项目渲染
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