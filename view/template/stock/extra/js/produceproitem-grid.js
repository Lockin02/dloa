var show_page = function(page) {
	$("#produceproitemGrid").yxgrid("reload");
};
$(function() {
	$("#produceproitemGrid").yxgrid({
		model : 'stock_extra_produceproitem',
		title : '������������',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'productId',
			display : '����id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '���ϱ��',
			sortable : true,
			width:80
		}, {
			name : 'productName',
			display : '��������',
			sortable : true,
			width:150
		}, {
			name : 'pattern',
			display : '����ͺ�',
			sortable : true
		}, {
			name : 'unitName',
			display : '��λ',
			sortable : true,
			width:50
		}, {
			name : 'actNum',
			display : '�������',
			sortable : true,
			width:50
		}, {
			name : 'planInstockNum',
			display : '��;����',
			sortable : true,
			width:50
		}, {
			name : 'relDocType',
			display : '��������',
			process:function(v,row){
				if(v==0){
					return "��ͬ";
				}else if(v==1){
					return "������";
				}else{
					return "����";
				}
			},
			sortable : true
		}, {
			name : 'relDocCode',
			display : '������',
			sortable : true
		}, {
			name : 'relDocId',
			display : '����id',
			sortable : true,
			hide : true
		
		}, {
			name : 'configName',
			display : '����',
			sortable : true
		}, {
			name : 'configNum',
			display : '����',
			sortable : true,
			width:50
		}, {
			name : 'sendDate',
			display : '����ʱ��',
			sortable : true,
			width:80
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width:200
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '��������',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '�޸���',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '�޸���id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '�޸�����',
			sortable : true,
			hide : true
		} ],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "���ϱ��",
			name : 'productCode'
		},{

			display : "��������",
			name : 'productCode'
		},{
			display : "����",
			name : 'configName'
		},{
			display : "������",
			name : 'relDocCode'
		} ]
	});
});