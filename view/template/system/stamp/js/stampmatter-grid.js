var show_page = function(page) {
	$("#stampmatterGrid").yxgrid("reload");
};
$(function() {
	$("#stampmatterGrid").yxgrid({
		model: 'system_stamp_stampmatter',
		title: '����ʹ����������',
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'matterName',
			display: '��������',
			sortable: true,
			width : 200,
			align : 'center'
		},
			{
				name : 'stamp_cId',
				display : '�������Id',
				sortable : true,
				hide : true
			},{
				name : 'stamp_cName',
				display : '�������',
				sortable : true,
			},
		{
			name: 'directions',
			display: '�ر�˵��',
			sortable: true,
			width : 150,
			align : 'center'
		},
		{
			name: 'needAudit',
			display: '�Ƿ���Ҫ����',
			sortable: true,
			process : function(v){
				if(v == 1){
					return '��';
				}
				else{
					return '��';
				}
			},
			width : 100,
			align : 'center'
		},
		{
			name: 'status',
			display: '״̬',
			sortable: true,
			process : function(v){
				if(v == 1){
					return '����';
				}
				else{
					return '�ر�';
				}
			},
			width : 100,
			align : 'center'
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