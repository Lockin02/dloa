$(document).ready(function() {
   if($("#actType").val()!=""){
			$("#closeBtn").hide();
			$("#approval").hide();
		}
	// 明细
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
			display : '培养目标',
			name : 'fosterGoal',
			align:'left',
			width : '29%'
		}, {
			display : '具体培养措施',
			name : 'fosterMeasure',
			align:'left',
			width : '30%'
		}, {
			display : '达成情况（员工）',
			name : 'reachinfoStu',
			width : '9%'
		}, {
			display : '补充说明（员工）',
			name : 'remarkStu',
			width : '10%'
		}, {
			display : '达成情况（导师）',
			name : 'reachinfoTut',
			width : '9%'
		}, {
			display : '补充说明（导师）',
			name : 'remarkTut',
			width : '10%'
		}]
	});
	// 明细
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
			display : '培养目标',
			name : 'fosterGoal',
			align:'left',
			width : '29%'
		}, {
			display : '具体培养措施',
			name : 'fosterMeasure',
			align:'left',
			width : '30%'
		}, {
			display : '达成情况（员工）',
			name : 'reachinfoStu',
			width : '9%'
		}, {
			display : '补充说明（员工）',
			name : 'remarkStu',
			width : '10%'
		}, {
			display : '达成情况（导师）',
			name : 'reachinfoTut',
			width : '9%'
		}, {
			display : '补充说明（导师）',
			name : 'remarkTut',
			width : '10%'
		}]
	});
	// 明细
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
			display : '培养目标',
			name : 'fosterGoal',
			align:'left',
			width : '29%'
		}, {
			display : '具体培养措施',
			name : 'fosterMeasure',
			align:'left',
			width : '30%'
		}, {
			display : '达成情况（员工）',
			name : 'reachinfoStu',
			width : '9%'
		}, {
			display : '补充说明（员工）',
			name : 'remarkStu',
			width : '10%'
		}, {
			display : '达成情况（导师）',
			name : 'reachinfoTut',
			width : '9%'
		}, {
			display : '补充说明（导师）',
			name : 'remarkTut',
			width : '10%'
		}]
	});
})