// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".assworklogweekGrid").esmprojectgrid("reload");
};

(function($) {
	$.woo.yxgrid.subclass('woo.esmprojectgrid', {
		options: {
			sortname: "id",
			// Ĭ������˳��
			model : 'engineering_worklog_esmworklogweek',
			title : '�ܱ�������Ϣ',
			isAddAction: false,
			isDelAction: false,
			isViewAction: false,
			isEditAction: false,
			showcheckbox: false,
			sortorder: "ASC",
		colModel: [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'weekTitle',
			display : '��־����',
			width : 300,
			sortable : true
		}, {
			name : 'weekTimes',
			display : '�ܴ�',
			sortable : true
		}, {
			name : 'rankCode',
			display : '����',
			sortable : true
		}, {
			name : 'directlyId',
			display : 'ֱ���ϼ�id',
			sortable : true,
			hide : true
		}, {
			name : 'directlyName',
			display : 'ֱ���ϼ�����',
			sortable : true
		}, {
			name : 'createName',
			display : '����������',
			sortable : true
		}, {
			name : 'subStatus',
			display : '�ύ״̬',
			datacode : 'ZBZT',
			sortable : true
		}],
			// ��������
			searchitems: [{
			display : '��־����',
			name : 'weekTitle'
		}],
			comboEx: [{
				text: "�ύ״̬",
				key: 'subStatus',
				datacode: 'ZBZT'
			}]
		}
	});
})(jQuery);