var show_page = function(page) {
	$("#auditYesGrid").yxgrid("reload");
};

$(function() {
	$("#auditYesGrid").yxgrid({

		model : 'projectmanagent_present_present',
		action : 'pageJsonAuditYes',
		title : '������',
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
		}],
		searchitems : [{
					display : '���',
					name : 'Code'
				}],
		sortname : 'id',
		sortorder : 'DESC'
	});
});