var show_page = function(page) {
	$("#periodconfigGrid").yxgrid("reload");
};
$(function() {
	$("#periodconfigGrid").yxgrid({
		model: 'contract_config_periodconfig',
		title: '�ؿ���ڼ�',
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'periodName',
			display: '�ڼ�����',
			sortable: true
		},
		{
			name: 'periodTypeName',
			display: '�ڼ�����',
			sortable: true
		},
		{
			name: 'periodType',
			display: '�ڼ����ͱ���',
			sortable: true,
			hide: true
		},
		{
			name: 'beginDays',
			display: '��ʼ����',
			sortable: true
		},
		{
			name: 'endDays',
			display: '��������',
			sortable: true
		},
		{
			name: 'description',
			display: '˵��',
			sortable: true,
			width : 200
		}],
		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			action: 'toView'
		},
		searchitems: [{
			display: "�����ֶ�",
			name: 'XXX'
		}]
	});
});