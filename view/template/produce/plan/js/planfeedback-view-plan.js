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
			display : '��  ��',
			name : 'process',
			width : '8%',
			align : 'left'
		},{
			display : '��Ŀ����',
			name : 'processName',
			width : '13%',
			align : 'left'
		},{
			display : '����ʱ�䣨�룩',
			name : 'processTime',
			width : '8%'
		},{
			display : '��������',
			name : 'recipientNum',
			width : '5%'
		},{
			display : '������',
			name : 'recipient',
			width : '10%',
			align : 'left'
		},{
			display : '������ID',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '����ʱ��',
			name : 'recipientTime',
			width : '8%'
		},{
			display : '���ʱ��',
			name : 'finishTime',
			width : '8%'
		},{
			display : '�ϸ�����',
			name : 'qualifiedNum',
			width : '8%'
		},{
			display : '���ϸ�����',
			name : 'unqualifiedNum',
			width : '8%'
		},{
			display : '�������κ�',
			name : 'productBatch',
			width : '10%'
		},{
			display : '��ע',
			name : 'remark',
			type : 'textarea',
			width : '20%',
			align : 'left'
		}]
	});
});