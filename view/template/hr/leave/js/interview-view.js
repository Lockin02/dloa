$(document).ready(function() {

	$("#interviewRecordList").yxeditgrid({
		objName : 'interview[record]',
		type : 'view',
		url : '?model=hr_leave_interviewDetail&action=listJson',
		param : {
			parentId : $("#id").val(),
			dir : 'ASC'
		},
		tableClass : 'form_in_table',
		colModel : [ {
			display : 'parentId',
			name : 'parentId',
			sortable : true,
			type : 'hidden'
		}, {
			display : '��̸��',
			name : 'interviewer',
			type : 'txt',
			width : '5%'
		}, {
			display : '��̸����',
			name : 'interviewDate',
			type : 'date',
			width : '7%'
		},
//			{
//			display : '��̸����',
//			name : 'interviewContent',
//			type : 'textarea',
//			align:'left',
//			width : '30%'
//		},
			{
			display : '��ְԭ��',
			name : 'leaveReson',
			type : 'textarea',
			align:'left',
			width : '27%'
		}, {
			display : '�Թ����Ŀ�������',
			name : 'jobAdvice',
			type : 'textarea',
			align:'left',
			width : '20%'
		}, {
			display : '�Թ�˾�Ŀ�������',
			name : 'companyAdvice',
			type : 'textarea',
			align:'left',
			width : '20%'
		}, {
			display : '��̸�˵Ľ���',
			name : 'interviewAdvice',
			type : 'textarea',
			align:'left',
			width : '20%'

		} ]
	});

})