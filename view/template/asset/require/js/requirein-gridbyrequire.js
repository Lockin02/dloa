var show_page = function(page) {
	$("#requireinGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireinGrid").yxsubgrid({
		model : 'asset_require_requirein',
		title : '����ת�ʲ�����',
		isAddAction : false,
		isToolBar : false,
		isOpButton : false,
		showcheckbox : false,
		param : {
			"requireId" : $("#requireId").val()
		},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'billNo',
			display : '���ݱ��',
			width : 120,
			sortable : true,
			width : 150
		}, {
			display : '����id',
			name : 'requireId',
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
			name : 'receiveStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if(v == '0')
					return "δ����";
				if(v == '1')
					return "��������";
				if(v == '2')
					return "������";
			},
			width : 80
		}, {
			name : 'status',
			display : '����״̬',
			sortable : true,
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
				process : function(v) {
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
			}, {
				name : 'receiveNum',
				display : '����������',
				width : 60
			}, {
				name : 'cardNum',
				display : '���ɿ�Ƭ����',
				width : 80
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
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '���ύ' || row.status == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requirein&action=toEdit&id="
							+ row.id 
							+ "&skey=" + row['skey_']);
				}
			}
		}, {
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == '���ύ' || row.status == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_require_requirein&action=ajaxdeletes",
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
		}, {
			text : '��д���յ�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.receiveStatus == "2" || row.outStockStatus == "WCK" || row.status == "�����") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_purchase_receive_receive&action=toRequireinAdd"
							+ "&requireinCode="
							+ row.billNo
							+ "&requireinId="
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		comboEx : [{
			text : '����״̬',
			key : 'outStockStatusArr',
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
				text : '���ֳ���,�ѳ���',
				value : 'BFCK,YCK'
			}]
		},{
			text : '����״̬',
			key : 'receiveStatusArr',
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '��������',
				value : '1'
			}, {
				text : '������',
				value : '2'
			}, {
				text : 'δ����,��������',
				value : '0,1'
			}]
		},{
			text : '����״̬',
			key : 'status',
			data : [{
				text : '���ύ',
				value : '���ύ'
			}, {
				text : '��ȷ��',
				value : '��ȷ��'
			}, {
				text : '��ȷ��',
				value : '��ȷ��'
			}, {
				text : '�������',
				value : '�������'
			}, {
				text : '�����',
				value : '�����'
			}, {
				text : '���',
				value : '���'
			}]
		}],
		searchitems : [{
			display : "���ݱ��",
			name : 'billNo'
		},{
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