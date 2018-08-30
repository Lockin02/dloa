$(document).ready(function() {

	//单选申请人
	$("#applyUserName").yxselect_user({
		hiddenId : 'applyUserId',
		isGetDept : [true, "deptId", "deptName"],
		formCode : 'exceptionApply'
	});

	//审核人
	$("#ExaUserName").yxselect_user({
		hiddenId : 'ExaUserId',
		formCode : 'exceptionApplyAudit'
	});

	//工程项目渲染
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

	//申购渲染申请物件验证
	if($("#applyType").val() == 'GCYCSQ-03'){
		validate({
			"products" : {
				required : true
			}
		});
	}
})


//设置表单审批状态
function setExaStatus(ExaStatus){
	$("#ExaStatus").val(ExaStatus);
}

//表单验证
function checkForm(){
	if($("#ExaUserName").val() == "" && $("#ExaStatus").val() == "审核中"){
		alert('提交审核时，审核人不能为空');
		return false;
	}
}