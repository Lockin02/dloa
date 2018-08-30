$(document).ready(function() {

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
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '30%'
		}, {
			display : '具体培养措施',
			name : 'fosterMeasure',
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '30%'
		}, {
			display : '达成情况（员工）',
			name : 'reachinfoStu',
			type : 'statictext',
			isSubmit : true,
			width : '10%'
		}, {
			display : '补充说明（员工）',
			name : 'remarkStu',
			type : 'statictext',
			isSubmit : true,
			width : '10%'
		}, {
			display : '达成情况（导师）',
			name : 'reachinfoTut',
			type : 'select',
			options : [{
				name : "..请选择..",
				value : ""
			},{
				name : "未完成",
				value : "未完成"
			}, {
				name : "一般",
				value : "一般"
			}, {
				name : "比较好",
				value : "比较好"
			}, {
				name : "非常好",
				value : "非常好"
			}],
			width : '10%'
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
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '30%'
		}, {
			display : '具体培养措施',
			name : 'fosterMeasure',
			type : 'statictext',
			isSubmit : true,
			width : '30%'
		}, {
			display : '达成情况（员工）',
			name : 'reachinfoStu',
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '10%'
		}, {
			display : '补充说明（员工）',
			name : 'remarkStu',
			type : 'statictext',
			isSubmit : true,
			width : '10%'
		}, {
			display : '达成情况（导师）',
			name : 'reachinfoTut',
			type : 'select',
			options : [{
				name : "..请选择..",
				value : ""
			},{
				name : "未完成",
				value : "未完成"
			}, {
				name : "一般",
				value : "一般"
			}, {
				name : "比较好",
				value : "比较好"
			}, {
				name : "非常好",
				value : "非常好"
			}],
			width : '10%'
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
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '30%'
		}, {
			display : '具体培养措施',
			name : 'fosterMeasure',
			type : 'statictext',
			align:'left',
			isSubmit : true,
			width : '30%'
		}, {
			display : '达成情况（员工）',
			name : 'reachinfoStu',
			type : 'statictext',
			isSubmit : true,
			width : '10%'
		}, {
			display : '补充说明（员工）',
			name : 'remarkStu',
			type : 'statictext',
			isSubmit : true,
			width : '10%'
		}, {
			display : '达成情况（导师）',
			name : 'reachinfoTut',
			type : 'select',
			options : [{
				name : "..请选择..",
				value : ""
			},{
				name : "未完成",
				value : "未完成"
			}, {
				name : "一般",
				value : "一般"
			}, {
				name : "比较好",
				value : "比较好"
			}, {
				name : "非常好",
				value : "非常好"
			}],
			width : '10%'
		}, {
			display : '补充说明（导师）',
			name : 'remarkTut',
			width : '10%'
		}]
	});
})