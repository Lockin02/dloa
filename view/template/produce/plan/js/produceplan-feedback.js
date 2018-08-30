$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
	}

	var feedbackObj = $("#feedbackInfo");
	feedbackObj.yxeditgrid({
		objName : 'produceplan[feedback]',
		url : '?model=produce_plan_planprocess&action=listJson',
		param : {
			planId : $("#id").val()
		},
		realDel : true,
		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '工  序',
			name : 'process',
			width : '8%',
			type : 'statictext',
			align : 'left'
		},{
			display : '工序隐藏后台用',
			name : 'process',
			type : 'hidden'
		},{
			display : '项目名称',
			name : 'processName',
			width : '13%',
			type : 'statictext',
			align : 'left'
		},{
			display : '项目名称隐藏后台用',
			name : 'processName',
			type : 'hidden'
		},{
			display : '工序时间（秒）',
			name : 'processTime',
			width : '8%',
			type : 'statictext'
		},{
			display : '工序时间隐藏后台用',
			name : 'processTime',
			type : 'hidden'
		},{
			display : '接收数量',
			name : 'recipientNum',
			width : '5%',
			type : 'statictext'
		},{
			display : '接收数量隐藏后台用',
			name : 'recipientNum',
			type : 'hidden'
		},{
			display : '接收人',
			name : 'recipient',
			width : '10%',
			type : 'statictext',
			align : 'left'
		},{
			display : '接收人隐藏后台用',
			name : 'recipient',
			type : 'hidden'
		},{
			display : '接收人ID',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '接收时间',
			name : 'recipientTime',
			width : '8%',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		},{
			display : '完成时间',
			name : 'finishTime',
			width : '8%',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			},
			process : function($input) {
				$input.val('');
			}
		},{
			display : '合格数量',
			name : 'qualifiedNum',
			width : '8%',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			process : function($input) {
				$input.val('');
			}
		},{
			display : '不合格数量',
			name : 'unqualifiedNum',
			width : '8%',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			process : function($input) {
				$input.val('');
			}
		},{
			display : '物料批次号',
			name : 'productBatch',
			width : '10%',
			process : function($input) {
				$input.val('');
			}
		},{
			display : '备注',
			name : 'remark',
			type : 'textarea',
			width : '20%'
		}]
	});

	validate({
		"id" : {
			required : true
		}
	});
});