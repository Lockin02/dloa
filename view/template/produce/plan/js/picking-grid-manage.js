var show_page = function (page) {
	$("#pickingGrid").yxsubgrid("reload");
};

$(function () {
	//���ݹ�������
	var param = {
		ExaStatus: '���'
	};
	if ($("#perform").val() == 'yes') {
		param.docStatusIn = '5';
	} else {
		param.docStatusIn = '2,4';
	}

	$("#pickingGrid").yxsubgrid({
		model: 'produce_plan_picking',
		param: param,
		title: '�����������뵥',
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
			name: 'relDocCode',
			display: 'Դ�����',
			sortable: true,
			width: 130,
            process: function (v, row) {
            	if(row.relDocTypeCode == 'HTLX-XSHT' || row.relDocTypeCode == 'HTLX-FWHT' || 
            		row.relDocTypeCode == 'HTLX-ZLHT' || row.relDocTypeCode == 'HTLX-YFHT'){
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.relDocId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            	}
            	return v;
            }
		},{
			name: 'docCode',
			display: '���ݱ��',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docDate',
			display: '��������',
			sortable: true
		},  {
			name: 'relDocName',
			display: 'Դ������',
			sortable: true,
			width: 200
		}, {
			name: 'relDocType',
			display: 'Դ������',
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
			url: '?model=produce_plan_pickingitem&action=pageJson',
			param: [{
				paramId: 'pickingId',
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
				name: 'realityNum',
				display: '��������',
				sortable: true
			}, {
				name: 'planDate',
				display: '�ƻ���������',
				sortable: true
			}, {
				name: 'realityDate',
				display: 'ʵ����������',
				sortable: true
			}]
		},

		//�����Ҽ��˵�
		menusEx: [{
			text: "�����ֳ���",
			icon: 'add',
			showMenuFn: function (row) {
				if (row.ExaStatus == '���' && row.isCanOut > 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=produce_plan_picking&action=toAddOut&id=" + row.id, '1');
				}
			}
		}, {
			text: "���Ƴ��ⵥ",
			icon: 'add',
			showMenuFn: function (row) {
				if (row.ExaStatus == '���' && row.docStatus != 5) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=stock_outstock_stockout&action=toAddByPicking&id=" + row.id, '1');
				}
			}
		}, 
//		{
//			text: "��������",
//			icon: 'add',
//			showMenuFn: function (row) {
//				if (row.ExaStatus == '���' && row.isCanBack > 0) {
//					return true;
//				}
//				return false;
//			},
//			action: function (row, rows, grid) {
//				if (row) {
//					showModalWin("?model=produce_plan_picking&action=toAddBack&id=" + row.id, '1');
//				}
//			}
//		}, 
		{
			name: 'aduit',
			text: '�������',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_produce_picking&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_picking&action=toViewTab&id=" + get[p.keyField], '1');
				}
			}
		},
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