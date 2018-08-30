$(document).ready(function() {
    /**
	 * 验证信息
	 */
	validate({
		"goal" : {
			required : true
		}
	});
	// 明细
	$("#infoATable").yxeditgrid({
		objName : 'coachplan[infoA]',
		url : '?model=hr_tutor_coachplaninfo&action=listJson',
		param : {
			'coachplanId' : $("#id").val(),
			'containMonth' : '1'
		},
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
	// 明细
	$("#infoBTable").yxeditgrid({
		objName : 'coachplan[infoB]',
		url : '?model=hr_tutor_coachplaninfo&action=listJson',
		param : {
			'coachplanId' : $("#id").val(),
			'containMonth' : '2'
		},
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
	// 明细
	$("#infoCTable").yxeditgrid({
		objName : 'coachplan[infoC]',
		url : '?model=hr_tutor_coachplaninfo&action=listJson',
		param : {
			'coachplanId' : $("#id").val(),
			'containMonth' : '3'
		},
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
})

 	   //保存
function toSubmit(){
	document.getElementById('form1').action = "?model=hr_tutor_coachplan&action=edit&actType=audit";
}