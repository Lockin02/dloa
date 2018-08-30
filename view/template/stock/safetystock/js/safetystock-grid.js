var show_page = function() {
	$("#safetystockGrid").yxgrid("reload");
};
$(function() {
	$("#safetystockGrid").yxgrid({
		model : 'stock_safetystock_safetystock',
		action : 'pageJsonCount',
		title : '��ȫ����б�',
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
            name : 'manageDept',
            display : '������',
            width : '80',
            sortable : true
        }, {
			name : 'productId',
			display : '����id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '���ϱ��',
			width : '80',
			sortable : true
		}, {
			name : 'productName',
			display : '��������',
			width : '200',
			sortable : true
		}, {
			name : 'pattern',
			display : '����ͺ�',
			width : '100',
			sortable : true
		}, {
			name : 'unitName',
			display : '��λ',
			width : '50',
			sortable : true
		}, {
			name : 'saleStock',
			display : '�������',
			width : '60',
			sortable : true
		}, {
			name : 'oldEquStock',
			display : '���豸����',
			width : '60',
			sortable : true
		}, {
			name : 'minNum',
			display : '��Ϳ��',
			width : '50',
			process : function(v, row) {
				return '<span class="red">' + v + '</span>';
			},
			sortable : true
		}, {
			name : 'maxNum',
			display : '��߿��',
			width : '50',
			sortable : true
		}, {
			name : 'loadNum',
			display : '��;����',
			width : '50',
			sortable : true
		}, {
			name : 'useFull',
			display : '��;',
			sortable : true,
			hide : true
		}, {
			name : 'moq',
			display : 'MOQ',
			sortable : true,
			hide : true
		}, {
			name : 'price',
			display : '�ɹ�����',
			width : '50',
			sortable : true,
			hide : true
		}, {
			name : 'purchUserCode',
			display : '�ɹ�Ա����',
			sortable : true,
			hide : true
		}, {
			name : 'purchUserName',
			display : '�ɹ�Ա',
			sortable : true,
            width : 80
		}, {
			name : 'prepareDay',
			display : '��������(��)',
			width : '80',
			sortable : true
		}, {
			name : 'minAmount',
			display : '��С�����',
			width : '80',
			sortable : true
		}, {
			name : 'isFillUp',
			display : '�Ƿ��´ﲹ��',
			sortable : true,
			hide : true
		}, {
			name : 'fillNum',
			display : '��������',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			hide : true
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
			sortable : true
		}, {
			name : 'updateId',
			display : '�޸���id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '�޸�ʱ��',
			sortable : true,
			width : '150'
		}],
		buttonsEx : [{
			name : 'analyse',
			text : "����",
			icon : 'business',
			action : function(row, rows, grid) {
				showModalWin("?model=stock_safetystock_safetystock&action=toAnalyse");
			}
		}, {
			name : 'expport',
			text : "����",
			icon : 'excel',
			action : function(row) {
				window.open("?model=stock_safetystock_safetystock&action=toExportExcel","", "width=200,height=200,top=200,left=200");
			}
		}, {
			name : 'business',
			text : "��Ч������",
			icon : 'edit',
			action : function(row) {
				showThickboxWin("?model=system_configenum_configenum&action=toEdit&id=3&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400");
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "���ϱ��",
			name : 'productCode'
		}, {
			display : "��������",
			name : 'productName'
		}]
	});
});