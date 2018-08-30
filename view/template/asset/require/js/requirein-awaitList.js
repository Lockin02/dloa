var show_page = function(page) {
	$("#requireinGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireinGrid").yxsubgrid({
		model : 'asset_require_requirein',
		title : '�ʲ�����',
		param : {'statusNotArr':'���ύ,��ȷ��,���'},
		isToolBar : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'billNo',
			display : '���ݱ��',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requirein&action=toView&id=" + row.id
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 130
		}, {
			display : '����id',
			name : 'requireId',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '������',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requirement&action=toView&id=" + row.requireId
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 130
		}, {
			name : 'applyName',
			display : '������',
			sortable : true,
			width : 100
		}, {
			name : 'applyDeptName',
			display : '���벿��',
			sortable : true,
			width : 100
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'outStockStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if(v == 'WCK')
					return "δ����";
				if(v == 'BFCK')
					return "���ֳ���";
				if(v == 'YCK')
					return "�ѳ���";
			},
			width : 80
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 250
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_require_requireinitem&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'name',
				display : '�豸����',
				width : 120
			}, {
				name : 'description',
				display : '�豸����',
				width : 120
			}, {
				name : 'productName',
				display : '��������',
				width : 120
			}, {
				name : 'productCode',
				display : '���ϱ��',
				width : 120
			}, {
				name : 'productPrice',
				display : '���Ͻ��',
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'brand',
				display : '����Ʒ��',
				width : 80
			}, {
				name : 'spec',
				display : '����ͺ�',
				width : 80
			}, {
				name : 'number',
				display : '����',
				width : 60
			}, {
				name : 'executedNum',
				display : '�ѳ�������',
				width : 60
			}]
		},
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requirein&action=toView&id="
							+ row.id
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : 'ȷ������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.outStockStatus == 'YCK') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requirein&action=toConfirm&id="
							+ row.id
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : "����",
			icon : 'business',
			showMenuFn : function(row) {
				if (row.outStockStatus == 'WCK' || row.outStockStatus == 'BFCK') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showModalWin("?model=stock_outstock_stockout&action=toAddBlueByAsset&id="
						+ row.id
						+ "&skey="
						+ row['skey_'])
			}
		},{
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.outStockStatus == 'WCK') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_require_requirein&action=toBack&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		comboEx : [{
			text : '����״̬',
			key : 'outStockStatusArr',
			value : 'WCK,BFCK',
			data : [{
				text : 'δ����',
				value : 'WCK'
			}, {
				text : '���ֳ���',
				value : 'BFCK'
			}, {
				text : '�ѳ���',
				value : 'YCK'
			}, {
				text : 'δ���⣬���ֳ���',
				value : 'WCK,BFCK'
			}]
		}],
		searchitems : [{
			display : "���ݱ��",
			name : 'billNo'
		}, {
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