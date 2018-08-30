$(document).ready(function() {

	//明细
	$("#infoATable").yxeditgrid({
		objName : 'coachplan[infoA]',
		isAddOneRow : true,
		colModel : [{
			display : 'month',
			name : 'containMonth',
			type : 'hidden',
			value : '1'
		}, {
			display : '培养目标',
			name : 'fosterGoal',
			width : '50%',
			validation : {
				required : true
			}
		}, {
			display : '具体培养措施',
			name : 'fosterMeasure',
			width : '50%',
			validation : {
				required : true
			}
		}]
	});

	//明细
	$("#infoBTable").yxeditgrid({
		objName : 'coachplan[infoB]',
		isAddOneRow : true,
		colModel : [{
			display : 'month',
			name : 'containMonth',
			type : 'hidden',
			value : '2'
		}, {
			display : '培养目标',
			name : 'fosterGoal',
			width : '50%'
		}, {
			display : '具体培养措施',
			name : 'fosterMeasure',
			width : '50%'
		}]
	});
	//明细
	$("#infoCTable").yxeditgrid({
		objName : 'coachplan[infoC]',
		isAddOneRow : true,
		colModel : [{
			display : 'month',
			name : 'containMonth',
			type : 'hidden',
			value : '3'
		}, {
			display : '培养目标',
			name : 'fosterGoal',
			width : '50%'
		}, {
			display : '具体培养措施',
			name : 'fosterMeasure',
			width : '50%'
		}]
	});
	/**
	 * 验证信息
	 */
	validate({
		"goal" : {
			required : true
		}
	});
})

 	   //保存
function toSubmit(){
	document.getElementById('form1').action = "?model=hr_tutor_coachplan&action=add&actType=audit";
}
//function sub() {
//	var rowNum = $("#infoATable").yxeditgrid('getCurShowRowNum');
//	alert(rowNum)
//	if (rowNum == '0') {
//		alert("请填写至少一个月的辅导计划");
//		return false;
//	}
//
//	$("input[type='button']").attr("disabled", true);
//	$("input[type='submit']").attr("disabled", true);
//	return false;
//
//}