$(document).ready(function() {
   if($("#actType").val()!=""){
			$("#closeBtn").hide();
			$("#approval").hide();
		}
	// ��ϸ
	$("#infoATable").yxeditgrid({
		objName : 'coachplan[infoA]',
		url : '?model=hr_tutor_coachplaninfo&action=listJson',
		type : 'view',
		tableClass : 'main_table',
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
			align:'left',
			width : '29%'
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			align:'left',
			width : '30%'
		}, {
			display : '��������Ա����',
			name : 'reachinfoStu',
			width : '9%'
		}, {
			display : '����˵����Ա����',
			name : 'remarkStu',
			width : '10%'
		}, {
			display : '����������ʦ��',
			name : 'reachinfoTut',
			width : '9%'
		}, {
			display : '����˵������ʦ��',
			name : 'remarkTut',
			width : '10%'
		}]
	});
	// ��ϸ
	$("#infoBTable").yxeditgrid({
		objName : 'coachplan[infoB]',
		url : '?model=hr_tutor_coachplaninfo&action=listJson',
		type : 'view',
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
			align:'left',
			width : '29%'
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			align:'left',
			width : '30%'
		}, {
			display : '��������Ա����',
			name : 'reachinfoStu',
			width : '9%'
		}, {
			display : '����˵����Ա����',
			name : 'remarkStu',
			width : '10%'
		}, {
			display : '����������ʦ��',
			name : 'reachinfoTut',
			width : '9%'
		}, {
			display : '����˵������ʦ��',
			name : 'remarkTut',
			width : '10%'
		}]
	});
	// ��ϸ
	$("#infoCTable").yxeditgrid({
		objName : 'coachplan[infoC]',
		url : '?model=hr_tutor_coachplaninfo&action=listJson',
		type : 'view',
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
			align:'left',
			width : '29%'
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			align:'left',
			width : '30%'
		}, {
			display : '��������Ա����',
			name : 'reachinfoStu',
			width : '9%'
		}, {
			display : '����˵����Ա����',
			name : 'remarkStu',
			width : '10%'
		}, {
			display : '����������ʦ��',
			name : 'reachinfoTut',
			width : '9%'
		}, {
			display : '����˵������ʦ��',
			name : 'remarkTut',
			width : '10%'
		}]
	});
})