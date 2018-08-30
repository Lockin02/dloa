$(document).ready(function() {

	//职位选择
	$("#afterPositionName").yxcombogrid_position({
		hiddenId : 'afterPositionId',
		width:350,
		width:350,
		gridOptions : {
			showcheckbox : false,
			param : {"deptId" : $("#deptId").val()}
		}
	});

	//单选人员等级
	$("#personLevel").yxcombogrid_eperson({
		hiddenId : 'personLevelId',
		gridOptions : {
			showcheckbox : false
		}
	});
});


//表单验证
function checkForm(){

	//转正建议
	if($('#permanentDate').val() ==　""){
		alert('请填写转正日期');
		return false;
	}

	if($('#afterSalary').val() ==　""){
		alert('请填写转正后工资');
		return false;
	}

	if($('#hrSalary').val() ==　""){
		alert('请填写人事建议工资');
		return false;
	}

	if($('#afterPositionName').val() ==　""){
		alert('请填写转正后职位');
		return false;
	}

//	if($('#levelName').val() ==　""){
//		alert('请填写转正后职级');
//		return false;
//	}

	if($('#personLevel').val() ==　""){
		alert('请填写人员技术等级');
		return false;
	}

	return true;
}

//编辑页 - 提交审批
function auditEdit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=hr_trialplan_trialdeptsuggest&action=edit&act=audit";
	}else{
		document.getElementById('form1').action="?model=hr_trialplan_trialdeptsuggest&action=edit";
	}
}
