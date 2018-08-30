var show_page = function (page) {
	$("#productGrid").yxgrid("reload");
};

$(function () {
	$("#productGrid").yxgrid({
		model: 'produce_apply_produceapplyitem',
		action: 'productPageJson',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		title: '����/��ͬ���ϻ�������',
		// ����Ϣ
		colModel: [{
			display: 'productId',
			name: 'productId',
			sortable: true,
			hide: true
		}, {
			name: 'proType',
			display: '��������',
			sortable: true,
			width: 100
		}, {
			name: 'productCode',
			display: '���ϱ���',
			sortable: true,
			width: 100
		}, {
			name: 'productName',
			display: '��������',
			sortable: true,
			width: 300
		}, {
			name: 'pattern',
			display: '����ͺ�',
			sortable: true,
			width: 150
		}, {
			name: 'unitName',
			display: '��λ',
			sortable: true,
			width: 60,
			align: 'center'
		}, {
			name: 'produceNumAll',
			display: '����������',
			sortable: true,
			align: 'center',
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct&code=" +
					row.productCode + "&num=" + row.produceNumAll + "\",1)'>" + v + "</a>";
			}
		}, {
			name: 'exeNumAll',
			display: '���´���������',
			sortable: true,
			align: 'center'
		}, {
			name: 'inventory',
			display: '�������',
			width: 80,
			sortable: true,
			align: 'center'
		}, {
			name: 'detail',
			display: '��ϸ',
			sortable: true,
			align: 'center',
			width: 60,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toViewProduct&productId=" +
					row.productId + "\",1)'>��ϸ</a>";
			}
		}],

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var row = g.getSelectedRow().data('data');
					showModalWin("?model=produce_apply_produceapply&action=toViewProduct&productId=" + row.productId, '1');
				}
			}
		},

		//��չ�Ҽ��˵�
		menusEx: [],

		searchitems: [{
			display: "��ͬ���",
			name: 'relDocCode'
		}, {
			display: "��������",
			name: 'proType'
		}, {
			display: "���ϱ���",
			name: 'productCode'
		}, {
			display: "��������",
			name: 'productName'
		}]
	});
});