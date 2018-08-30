var show_page = function(page) {
	$("#logoperationGrid").yxgrid("reload");
};
$(function() {
	$("#logoperationGrid").yxgrid({
		model : 'syslog_operation_logoperation',
		action : 'pageDetailJson',
		param : {
			"tableName" : $("#tableName").val()
		},
		title : '������־',
		isAddAction : false,
		isViewAction : true,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'logSettingId',
					display : '��־����id',
					sortable : true,
					hide : true
				}, {
					name : 'businessName',
					display : 'ҵ������',
					sortable : true
				}, {
					name : 'tableName',
					display : '����',
					sortable : true,
					width : 150,
					hide : true
				}, {
					name : 'operationType',
					display : '��������',
					sortable : true,
					width : 50
				}, {
					name : 'pkValue',
					display : 'ҵ�������ֶ�ֵ',
					sortable : true,
					hide : true
				}, {
					name : 'logContent',
					display : '��־��ϸ����',
					sortable : true,
					width : 500
				}, {
					name : 'createName',
					display : '������',
					sortable : true
				}, {
					name : 'createTime',
					display : '����ʱ��',
					sortable : true,
					width : 150

				}],
        buttonsEx : [{
			name : 'view',
			text : "�߼���ѯ",
			icon : 'view',
			action : function() {
				showThickboxWin("?model=syslog_operation_logoperation&action=toSearch&"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
			}
        }],
		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				return true;
			},
			action : function(row, rows, grid) {
				if (window.confirm("ȷ��Ҫɾ��?")) {
					$.ajax({
						type : "POST",
						url : "?model=syslog_operation_logoperation&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('ɾ���ɹ���');
							} else {
								alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!');
							}
						}
					});
				}
			}
		}],
		searchitems : [{
					display : "ҵ������",
					name : 'businessName'
				}, {
					display : "������",
					name : 'createName'
				}]
	});
});