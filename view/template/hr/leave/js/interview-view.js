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
			display : '面谈者',
			name : 'interviewer',
			type : 'txt',
			width : '5%'
		}, {
			display : '面谈日期',
			name : 'interviewDate',
			type : 'date',
			width : '7%'
		},
//			{
//			display : '面谈内容',
//			name : 'interviewContent',
//			type : 'textarea',
//			align:'left',
//			width : '30%'
//		},
			{
			display : '离职原因',
			name : 'leaveReson',
			type : 'textarea',
			align:'left',
			width : '27%'
		}, {
			display : '对工作的看法或建议',
			name : 'jobAdvice',
			type : 'textarea',
			align:'left',
			width : '20%'
		}, {
			display : '对公司的看法或建议',
			name : 'companyAdvice',
			type : 'textarea',
			align:'left',
			width : '20%'
		}, {
			display : '面谈人的建议',
			name : 'interviewAdvice',
			type : 'textarea',
			align:'left',
			width : '20%'

		} ]
	});

})