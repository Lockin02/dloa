var show_page = function(page) {
	$("#auditnoGrid").yxgrid("reload");
};

$(function() {
	$("#auditnoGrid").yxgrid({

		model : 'projectmanagent_present_present',
		action : 'pageJsonAuditNo',
		// title:'���������۶���',
		isToolBar : false,
		showcheckbox : false,
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'orderCode',
					display : 'Դ�����',
					sortable : true
				}, {
					name : 'orderName',
					display : 'Դ������',
					sortable : true
				}, {
					name : 'Code',
					display : '���',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true
				}, {
					name : 'salesName',
					display : '������',
					sortable : true
				}, {
					name : 'reason',
					display : '��������',
					sortable : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true
				}, {
					name : 'objCode',
					display : 'ҵ����',
					width : 120
				}, {
					name : 'rObjCode',
					display : 'Դ��ҵ����',
					width : 120
				}],

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=projectmanagent_present_present&action=init&perm=view&id="
						+ row.presentId
						+ "&skey="
						+ row['skey_']
						+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
			}
		}, {
			text : '����',
			icon : 'edit',
			action : function(row) {
				location = 'controller/projectmanagent/present/ewf_present.php?taskId='
						+ row.task
						+ '&spid='
						+ row.id
						+ '&billId='
						+ row.presentId
						+ '&actTo=ewfExam'
						+ "&skey="
						+ row['skey_'];
			}
		}],
		searchitems : [{
					display : '���',
					name : 'Code'
				}],
		sortname : 'id',
		sortorder : 'DESC'
	});
});