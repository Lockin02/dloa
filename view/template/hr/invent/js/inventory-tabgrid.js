(function($) {
	$.woo.yxgrid.subclass('woo.yxgrid_inventory', {
		options : {
			model : 'hr_invent_inventory',
			// ��
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
				sortable : true
			}, {
//				name : 'userAccount',
//				display : 'Ա���˺�',
//				sortable : true
//			}, {
				name : 'userName',
				display : 'Ա������',
				sortable : true
			}, {
//				name : 'companyType',
//				display : '��˾����',
//				sortable : true
//			}, {
				name : 'companyName',
				display : '��˾����',
				sortable : true
			}, {
//				name : 'deptNameS',
//				display : '��������',
//				sortable : true
//			}, {
//				name : 'deptNameT',
//				display : '��������',
//				sortable : true
//			}, {
				name : 'entryDate',
				display : '��ְ����',
				sortable : true
			}, {
				name : 'inventoryDate',
				display : '�̵�����',
				sortable : true
			}, {
				name : 'alternative',
				display : '�����',
				sortable : true
			}, {
				name : 'matching',
				display : 'ƥ���',
				sortable : true
			}, {
				name : 'critical',
				display : 'Ա���ؼ���',
				sortable : true
			}, {
				name : 'isCore',
				display : '�Ƿ����',
				sortable : true
			}, {
				name : 'recruitment',
				display : '��Ƹ�Ѷ�',
				sortable : true
			}, {
				name : 'examine',
				display : '����',
				sortable : true
			}, {
				name : 'preEliminated',
				display : 'Ԥ��̭',
				sortable : true
			}, {
				name : 'remark',
				display : '�Ƿ������ʧ',
				sortable : true
			}],
			/**
			 * ��������
			 */
			searchitems : [{
				display : 'Ա������',
				name : 'userName'
			}],
			sortorder : "DESC",
			sortname : "id",
			title : 'Ա���̵���Ϣ'
		}
	});
})(jQuery);