var show_page = function(page) {
	$("#systemwarning_mail_logsGrid").yxgrid("reload");
};
$(function() {
	$("#systemwarning_mail_logsGrid").yxgrid({
		model : 'system_warningmaillogs_warningmaillogs',
		title : '预警邮件通知情况',
		isAddAction :false,
		isEditAction :false,
		isViewAction :false,
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'objId',
			display : '业务ID',
			sortable : true
		}, {
			name : 'objName',
			display : '业务名称',
			sortable : true,
			width : 120
		}, {
			name : 'logId',
			display : '预警执行记录id',
			sortable : true
		}, {
			name : 'mailUserIds',
			display : '邮件接收人',
			sortable : true
		}, {
			name : 'mailUserNames',
			display : '邮件接收人名',
			sortable : true
		}, {
			name : 'ccmailUserIds',
			display : '邮件抄送人',
			sortable : true
		}, {
			name : 'ccmailUserNames',
			display : '邮件抄送人名',
			sortable : true
		}, {
			name : 'mailFeedback',
			display : '邮件发送回执',
			sortable : true
		}, {
			name : 'excuteTime',
			display : '执行时间',
			sortable : true,
			width : 150
		}, {
			name : 'result',
			display : '查询结果',
			sortable : true,
			width : 500
		}  ],
		// 主从表格设置
		subGridOptions : {
			url : '?model=system_system_warning_mail_logs_NULL&action=pageItemJson',
			param : [ {
				paramId : 'mainId',
				colId : 'id'
			} ],
			colModel : [ {
				name : 'XXX',
				display : '从表字段'
			} ]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "搜索字段",
			name : 'XXX'
		} ]
	});
});