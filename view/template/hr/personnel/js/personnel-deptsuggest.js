
$(document).ready(function() {

	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});

	//职位选择
	$("#afterPositionName").yxcombogrid_position({
		hiddenId : 'afterPositionId',
		width:350,
		gridOptions : {
			showcheckbox : false,
			param : {"deptId" : $("#deptId").val()}
		}
	});

	//单选人员等级
	$("#personLevel").yxcombogrid_eperson({
		hiddenId : 'personLevelId',
		width : 350,
		gridOptions : {
			showcheckbox : false
		}
	});
});


//部门建议改变后
function changeSuggest(thisVal){
	//部门建议信息
//	var deptSuggest = $("#deptSuggest").val();
//	if($("#deptSuggest").val() == 'HRBMJY-03'){
//		$(".deptsuggestDismiss").show();
//		$(".deptsuggestPositive").hide();
//	}else{
//		$(".deptsuggestDismiss").hide();
//		$(".deptsuggestPositive").show();
//	}
}

//表单验证
function checkForm(){
	if($('#deptSuggest').val() ==　"HRBMJY-00"){
		alert('建议为暂无时不能提交部门建议');
		return false;
	}

	if($('#suggestion').val() ==　""){
		alert('请填写建议描述');
		return false;
	}

	var deptSuggest = $("#deptSuggest").val();
	//无建议
	if(deptSuggest == 'HRBMJY-00'){
		alert('请选择一个部门建议');
		return false;
	}

	//转正建议
	if(deptSuggest == 'HRBMJY-01' || deptSuggest == 'HRBMJY-02'){
		if($('#permanentDate').val() ==　""){
			alert('请填写转正日期');
			return false;
		}

		if($('#afterSalary').val() ==　""){
			alert('请填写转正后工资');
			return false;
		}

//		if($('#afterPositionName').val() ==　""){
//			alert('请填写转正后职位');
//			return false;
//		}

		if($('#levelName').val() ==　""){
			alert('请填写转正后职级');
			return false;
		}

		if($('#personLevel').val() ==　""){
			alert('请填写人员技术等级');
			return false;
		}
	}
	return true;
}


//新增 - 提交审批
function audit(thisType){
	if(thisType == 'audit'){
		document.getElementById('form1').action="?model=hr_personnel_personnel&action=deptSuggest&act=audit";
	}else{
		document.getElementById('form1').action="?model=hr_personnel_personnel&action=deptSuggest";
	}
}
