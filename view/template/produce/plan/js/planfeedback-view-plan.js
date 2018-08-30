$(document).ready(function() {
	$("#feedbackInfo").yxeditgrid({
		url : '?model=produce_plan_planfeedback&action=listJson',
		param : {
			planId : $("#planId").val(),
			feedbackNum : $("#feedbackNum").val()
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '工  序',
			name : 'process',
			width : '8%',
			align : 'left'
		},{
			display : '项目名称',
			name : 'processName',
			width : '13%',
			align : 'left'
		},{
			display : '工序时间（秒）',
			name : 'processTime',
			width : '8%'
		},{
			display : '接收数量',
			name : 'recipientNum',
			width : '5%'
		},{
			display : '接收人',
			name : 'recipient',
			width : '10%',
			align : 'left'
		},{
			display : '接收人ID',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '接收时间',
			name : 'recipientTime',
			width : '8%'
		},{
			display : '完成时间',
			name : 'finishTime',
			width : '8%'
		},{
			display : '合格数量',
			name : 'qualifiedNum',
			width : '8%'
		},{
			display : '不合格数量',
			name : 'unqualifiedNum',
			width : '8%'
		},{
			display : '物料批次号',
			name : 'productBatch',
			width : '10%'
		},{
			display : '备注',
			name : 'remark',
			type : 'textarea',
			width : '20%',
			align : 'left'
		}]
	});
});