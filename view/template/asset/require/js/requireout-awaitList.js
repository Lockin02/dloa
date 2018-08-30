var show_page = function(page) {
	$("#requireoutGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireoutGrid").yxsubgrid({
		model : 'asset_require_requireout',
		title : '�ʲ����',
		isToolBar : false,
		showcheckbox : false,
		param : {
			 'ExaStatus' : '���'
		},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '������',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requireout&action=toView&id=" + row.id
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 130
		}, {
			name : 'applyName',
			display : '������',
			sortable : true,
			width : 80
		}, {
			name : 'applyDeptName',
			display : '���벿��',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 70
		}, {
			name : 'inStockStatus',
			display : '���״̬',
			sortable : true,
			process : function(v) {
				if(v == 'WRK')
					return "δ���";
				if(v == 'BFRK')
					return "�������";
				if(v == 'YRK')
					return "�����";
			},
			width : 80
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 80
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 200
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_require_requireoutitem&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'assetName',
				display : '�ʲ�����',
				width : 150
			}, {
				name : 'assetCode',
				display : '�ʲ����',
				width : 150
			}, {
				display : '�ʲ���ֵ',
				name : 'salvage',
				tclass : 'txt',
				process : function(v) {
					return moneyFormat2(v);
				},
				width : 120
			}, {
				name : 'productName',
				display : '��������',
				width : 150
			}, {
				name : 'productCode',
				display : '���ϱ��',
				width : 150
			}, {
				name : 'number',
				display : '����',
				width : 60
			}, {
				name : 'executedNum',
				display : '���������',
				width : 60
			}]
		},
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					if (row) {
						showThickboxWin("?model=asset_require_requireout&action=toView&id="
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
					}
				}
			}
		}, {
			text : 'ȷ������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.inStockStatus == 'YRK') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requireout&action=toConfirm&id="
							+ row.id
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : "���",
			icon : 'business',
			showMenuFn : function(row) {
				if (row.inStockStatus == 'WRK' || row.inStockStatus == 'BFRK') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showModalWin("?model=stock_instock_stockin&action=toAddBlueByAsset&id="
						+ row.id
						+ "&skey="
						+ row['skey_'])
			}
		}],
		comboEx : [{
			text : '���״̬',
			key : 'inStockStatusArr',
			value : 'WRK,BFRK',
			data : [{
				text : 'δ���',
				value : 'WRK'
			}, {
				text : '�������',
				value : 'BFRK'
			}, {
				text : '�����',
				value : 'YRK'
			}, {
				text : 'δ��⣬�������',
				value : 'WRK,BFRK'
			}]
		}],
		searchitems : [{
			display : "������",
			name : 'requireCode'
		}, {
			display : "������",
			name : 'applyName'
		}, {
			display : "���벿��",
			name : 'applyDeptName'
		}]
	});
});