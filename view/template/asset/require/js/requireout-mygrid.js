var show_page = function(page) {
	$("#requireoutGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireoutGrid").yxsubgrid({
		model : 'asset_require_requireout',
		action : 'myPageJson',
		title : '�ҵ��ʲ�ת��������',
		isToolBar : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '������',
			width : 120,
			sortable : true,
			width : 150
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
			},{
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
				width : 80
			}, {
				name : 'executedNum',
				display : '���������',
				width : 80
			}]
		},
		buttonsEx : [{
			name : 'add',
			text : "����",
			icon : 'add',
			action : function(row) {
				showOpenWin("?model=asset_require_requireout&action=toadd")
			}
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requireout&action=toView&id="
							+ row.id
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requireout&action=toEdit&id="
							+ row.id 
							+ "&skey=" + row['skey_']);
				}
			}
		}, {
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_require_requireout&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page(1);
							} else {
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
		}],
		comboEx : [{
			text : '���״̬',
			key : 'inStockStatus',
			data : [{
				text : 'δ���',
				value : 'WRK'
			}, {
				text : '�������',
				value : 'BFRK'
			}, {
				text : '�����',
				value : 'YRK'
			}]
		}],
		searchitems : [{
			display : "������",
			name : 'requireCode'
		}]
	});
});