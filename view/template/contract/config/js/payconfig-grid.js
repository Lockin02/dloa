var show_page = function(page) {
	$("#payconfigGrid").yxgrid("reload");
};
$(function() {
	$("#payconfigGrid").yxgrid({
		model: 'contract_config_payconfig',
		title: '������������',
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'configName',
			display: '��������',
			sortable: true
		},
		{
			name: 'isNeedDate',
			display: '�Ƿ��������',
			sortable: true,
			process : function(v){
				if(v == "1"){
					return '��';
				}else{
					return '��';
				}
			}
		},
		{
			name: 'dateName',
			display: '��������',
			sortable: true
		},
		{
			name: 'dateCode',
			display: '�������Ա���',
			sortable: true,
			hide: true
		},
		{
			name: 'days',
			display: '��ֹ����',
			sortable: true
		},
			{
				name: 'schePct',
				display: '�Ƿ��ѡ���Ȱٷֱ�',
				sortable: true,
				process : function(v){
					if(v == "1"){
						return '��';
					}else{
						return '��';
					}
				}
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