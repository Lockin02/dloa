var show_page = function (page) {
	$("#backGrid").yxsubgrid("reload");
};
$(function () {
	$("#backGrid").yxsubgrid({
		model: 'produce_plan_back',
		param: {docStatusIn: '1,2,3'},
		title: '��������������б�',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'pickingCode',
			display: '���ϵ����',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.pickingId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docCode',
			display: '���ݱ��',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_back&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docDate',
			display: '��������',
			sortable: true
		}, {
			name: 'createName',
			display: '������',
			sortable: true
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			width: 250
		}],

		// ���ӱ������
		subGridOptions: {
			url: '?model=produce_plan_backitem&action=pageJson',
			param: [{
				paramId: 'backId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				display: '���ϱ���',
				sortable: true,
				width: 150
			}, {
				name: 'productName',
				display: '��������',
				width: 200,
				sortable: true
			}, {
				name: 'pattern',
				display: '����ͺ�',
				sortable: true
			}, {
				name: 'unitName',
				display: '��λ',
				sortable: true
			}, {
				name: 'applyNum',
				display: '��������',
				sortable: true
			}, {
				name: 'backNum',
				display: '�������',
				sortable: true
			}]
		},

		menusEx : [{
			name : 'bluepush',
			text : '������ⵥ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == "1" || row.docStatus == "2") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("index1.php?model=stock_instock_stockin&action=toBluePush&docType=RKPRODUCEBACK&relDocId="
						+ row.id + "&relDocCode=" + row.docCode,1,row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_back&action=toView&id=" + get[p.keyField], '1');
				}
			}
		},
		comboEx : [{
			text : '���״̬',
			key : 'docStatusArr',
			value : '1,2',
			data : [{
				text : 'δ���',
				value : '1'
			}, {
				text : '�������',
				value : '2'
			}, {
				text : '�����',
				value : '3'
			}, {
				text : 'δ��⣬�������',
				value : '1,2'
			}]
		}],
		searchitems: [{
			name: 'docCode',
			display: '���ݱ��'
		}, {
			name: 'docDate',
			display: '��������'
		}, {
			name: 'relDocCode',
			display: 'Դ�����'
		}, {
			name: 'relDocName',
			display: 'Դ������'
		}, {
			name: 'relDocType',
			display: 'Դ������'
		}, {
			name: 'createName',
			display: '������'
		}]
	});
});