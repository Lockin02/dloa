var show_page = function(page) {
	$("#dateconfigGrid").yxgrid("reload");
};
$(function() {
	$("#dateconfigGrid").yxgrid({
		model: 'contract_config_dateconfig',
		title: '��������',
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'fieldName',
			display: '�ֶ�����',
			sortable: true
		},
		{
			name: 'fieldCode',
			display: '�ֶα���',
			sortable: true
		},
		{
			name: 'fieldDesc',
			display: '�ֶ�����',
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