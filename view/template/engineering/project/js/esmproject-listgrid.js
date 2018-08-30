// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#esmprojectGrid").esmprojectgrid("reload");
};

(function($) {
	$.woo.yxgrid.subclass('woo.esmprojectgrid', {
		options: {
			sortname: "id",
			// Ĭ������˳��
			model: 'engineering_project_esmproject',
			isAddAction: false,
			isDelAction: false,
			isViewAction: false,
			isEditAction: false,
			showcheckbox: false,
			sortorder: "ASC",
			colModel: [{
				display: 'id',
				name: 'id',
				hide: true
			},{
				display: 'id',
				name: 'managerId',
				hide: true
			},
			{
				display: '��Ŀ����',
				name: 'projectName',
				sortable: true,
				width: '180'
			},
			{
				display: '��Ŀ���',
				name: 'projectCode',
				sortable: true,
				width: '130'
			},
			{
				display: '��Ŀ����',
				name: 'effortRate',
				sortable: true,
				process: function(v) {
					return v + ' <font color="blue">%</font>';
				},
				width: '60'
			},
			{
				display: '(Ԥ��)��ʼ',
				name: 'planDateStart',
				sortable: true
			},
			{
				display: '(Ԥ��)����',
				name: 'planDateClose',
				sortable: true
			},
			{
				display: '��Ŀ״̬',
				name: 'status',
				sortable: true,
				width: '60',
				process: function(v) {
					switch (v) {
					case '1':
						return '����';
						break;
					case '2':
						return '������';
						break;
					case '4':
						return '���';
						break;
					case '6':
						return 'ִ����';
						break;
					case '7':
						return '���';
						break;
					case '8':
						return '�ر�';
						break;
					case '9':
						return '������';
						break;
					case '10':
						return '�ѽ���';
						break;
					default:
						return '����';
					}
				}
			},
			{
				display: '��Ŀ����',
				name: 'projectType',
				datacode: 'GCXMXZ',
				sortable: true,
				width: '70'
			},
			{
				display: '��Ҫ����',
				name: 'mainNet',
				datacode: 'GCZYWL',
				sortable: true,
				width: '60'
			},
			{
				display: '��/����',
				name: 'cycle',
				datacode: 'GCCDQ',
				sortable: true,
				width: '60'
			},
			{
				display: '��Ŀ����',
				name: 'managerName',
				sortable: true
			},
			{
				display: '����',
				name: 'officeName',
				sortable: true,
				width: '70'
			},
			{
				display: '����ʡ',
				name: 'proName',
				sortable: true,
				width: '70'
			}],
			// ��������
			searchitems: [{
				display: '��Ŀ����',
				name: 'seachProjectName'
			},
			{
				display: '��Ŀ���',
				name: 'seachProjectCode'
			}],
			comboEx: [{
				text: "��Ŀ״̬",
				key: 'status',
				data : [{
					text : '����',
					value : '1'
					}, {
					text : '������',
					value : '2'
					}
					, {
					text : '���',
					value : '4'
					}
					, {
					text : 'ִ����',
					value : '6'
					}, {
					text : '���',
					value : '7'
					}, {
					text : '�ر�',
					value : '8'
					}, {
					text : '������',
					value : '9'
					}, {
					text : '�ѽ���',
					value : '10'
					}
				]
			},{
				text: "��Ŀ����",
				key: 'projectType',
				datacode: 'GCXMXZ'
			}]
		}
	});
})(jQuery);