var show_page = function(page) {
	$("#presentGrid").yxgrid("reload");
};
$(function() {
	$("#presentGrid").yxgrid({
		model : 'projectmanagent_present_present',
		title : '��������',
		param : {
			"orderId" : $("#orderId").val()
		},
		// ��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		comboEx : [{
					text : '����״̬',
					key : 'ExaStatus',
					data : [{
								text : 'δ����',
								value : 'δ����'
							}, {
								text : '��������',
								value : '��������'
							}, {
								text : '���',
								value : '���'
							}]
				}],
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
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_present_present&action=init&perm=view&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
					display : '���',
					name : 'Code'
				}]
	});
});