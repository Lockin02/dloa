$(document).ready(function() {
    /**
	 * ��֤��Ϣ
	 */
	validate({
		"goal" : {
			required : true
		}
	});
	// ��ϸ
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
			display : '����Ŀ��',
			name : 'fosterGoal',
			width : '50%',
			validation : {
				required : true
			}
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			width : '50%',
			validation : {
				required : true
			}
		}]
	});
	// ��ϸ
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
			display : '����Ŀ��',
			name : 'fosterGoal',
			width : '50%'
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			width : '50%'
		}]
	});
	// ��ϸ
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
			display : '����Ŀ��',
			name : 'fosterGoal',
			width : '50%'
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			width : '50%'
		}]
	});
})

 	   //����
function toSubmit(){
	document.getElementById('form1').action = "?model=hr_tutor_coachplan&action=edit&actType=audit";
}