var show_page = function(page) {
	$("#prostockwarnGrid").yxgrid("reload");
};
$(function() {
	$("#prostockwarnGrid").yxgrid({
		model : 'stock_productinfo_prostockwarn',
		title : '���Ͽ��Ԥ����Ϣ����',
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
			sortable : true
		}, {
			name : 'productName',
			display : '��������',
			sortable : true,
			width:200
		}, {
			name : 'pattern',
			display : '����ͺ�',
			sortable : true
		}, {
			name : 'unitName',
			display : '��λ',
			sortable : true
		}, {
			name : 'maxNum',
			display : '�����',
			sortable : true
		}, {
			name : 'miniNum',
			display : '��С���',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true
		} ],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "���ϱ��",
			name : 'lproductCode'
		}, {
			display : "��������",
			name : 'lproductName'
		} ]
	});
});