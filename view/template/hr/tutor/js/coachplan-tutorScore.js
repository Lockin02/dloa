$(document).ready(function() {

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
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '30%'
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '30%'
		}, {
			display : '��������Ա����',
			name : 'reachinfoStu',
			type : 'statictext',
			isSubmit : true,
			width : '10%'
		}, {
			display : '����˵����Ա����',
			name : 'remarkStu',
			type : 'statictext',
			isSubmit : true,
			width : '10%'
		}, {
			display : '����������ʦ��',
			name : 'reachinfoTut',
			type : 'select',
			options : [{
				name : "..��ѡ��..",
				value : ""
			},{
				name : "δ���",
				value : "δ���"
			}, {
				name : "һ��",
				value : "һ��"
			}, {
				name : "�ȽϺ�",
				value : "�ȽϺ�"
			}, {
				name : "�ǳ���",
				value : "�ǳ���"
			}],
			width : '10%'
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
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '30%'
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			type : 'statictext',
			isSubmit : true,
			width : '30%'
		}, {
			display : '��������Ա����',
			name : 'reachinfoStu',
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '10%'
		}, {
			display : '����˵����Ա����',
			name : 'remarkStu',
			type : 'statictext',
			isSubmit : true,
			width : '10%'
		}, {
			display : '����������ʦ��',
			name : 'reachinfoTut',
			type : 'select',
			options : [{
				name : "..��ѡ��..",
				value : ""
			},{
				name : "δ���",
				value : "δ���"
			}, {
				name : "һ��",
				value : "һ��"
			}, {
				name : "�ȽϺ�",
				value : "�ȽϺ�"
			}, {
				name : "�ǳ���",
				value : "�ǳ���"
			}],
			width : '10%'
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
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '30%'
		}, {
			display : '����������ʩ',
			name : 'fosterMeasure',
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '30%'
		}, {
			display : '��������Ա����',
			name : 'reachinfoStu',
			type : 'statictext',
			isSubmit : true,
			width : '10%'
		}, {
			display : '����˵����Ա����',
			name : 'remarkStu',
			type : 'statictext',
			isSubmit : true,
			width : '10%'
		}, {
			display : '����������ʦ��',
			name : 'reachinfoTut',
			type : 'select',
			options : [{
				name : "..��ѡ��..",
				value : ""
			},{
				name : "δ���",
				value : "δ���"
			}, {
				name : "һ��",
				value : "һ��"
			}, {
				name : "�ȽϺ�",
				value : "�ȽϺ�"
			}, {
				name : "�ǳ���",
				value : "�ǳ���"
			}],
			width : '10%'
		}, {
			display : '����˵������ʦ��',
			name : 'remarkTut',
			width : '10%'
		}]
	});
})