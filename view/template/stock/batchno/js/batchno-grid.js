var show_page = function(page) {
	$("#batchnoGrid").yxgrid("reload");
};
$(function() {
	$("#batchnoGrid").yxgrid({
		model : 'stock_batchno_batchno',
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		title : '�������κ�̨��',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'inDocId',
			display : '��ⵥid',
			sortable : true,
			hide : true
		}, {
			name : 'inDocCode',
			display : '��ⵥ���',
			sortable : true
		}, {
			name : 'inDocItemId',
			display : '����嵥id',
			sortable : true,
			hide : true
		}, {
			name : 'outDocCode',
			display : '���ⵥ���',
			sortable : true,
			hide : true
		}, {
			name : 'outDocId',
			display : '���ⵥid',
			sortable : true,
			hide : true
		}, {
			name : 'outDocItemId',
			display : '���ⵥ�嵥id',
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
			width : 200,
			sortable : true

		}, {
			name : 'batchNo',
			display : '���κ�',
			sortable : true
		}, {
			name : 'stockId',
			display : '�ֿ�id',
			sortable : true,
			hide : true
		}, {
			name : 'stockCode',
			display : '�ֿ����',
			sortable : true
		}, {
			name : 'stockName',
			display : '�ֿ�����',
			sortable : true

		}, {
			name : 'stockNum',
			display : '�������',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true
		} ],
		searchitems : [ {
			display : '���κ�',
			name : 'batchNo'
		}, {
			name : 'productName',
			display : '��������'

		} ]
	});
});