var show_page = function(page) {
	$("#systemwarning_mail_logsGrid").yxgrid("reload");
};
$(function() {
	$("#systemwarning_mail_logsGrid").yxgrid({
		model : 'system_warningmaillogs_warningmaillogs',
		title : 'Ԥ���ʼ�֪ͨ���',
		isAddAction :false,
		isEditAction :false,
		isViewAction :false,
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'objId',
			display : 'ҵ��ID',
			sortable : true
		}, {
			name : 'objName',
			display : 'ҵ������',
			sortable : true,
			width : 120
		}, {
			name : 'logId',
			display : 'Ԥ��ִ�м�¼id',
			sortable : true
		}, {
			name : 'mailUserIds',
			display : '�ʼ�������',
			sortable : true
		}, {
			name : 'mailUserNames',
			display : '�ʼ���������',
			sortable : true
		}, {
			name : 'ccmailUserIds',
			display : '�ʼ�������',
			sortable : true
		}, {
			name : 'ccmailUserNames',
			display : '�ʼ���������',
			sortable : true
		}, {
			name : 'mailFeedback',
			display : '�ʼ����ͻ�ִ',
			sortable : true
		}, {
			name : 'excuteTime',
			display : 'ִ��ʱ��',
			sortable : true,
			width : 150
		}, {
			name : 'result',
			display : '��ѯ���',
			sortable : true,
			width : 500
		}  ],
		// ���ӱ������
		subGridOptions : {
			url : '?model=system_system_warning_mail_logs_NULL&action=pageItemJson',
			param : [ {
				paramId : 'mainId',
				colId : 'id'
			} ],
			colModel : [ {
				name : 'XXX',
				display : '�ӱ��ֶ�'
			} ]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "�����ֶ�",
			name : 'XXX'
		} ]
	});
});