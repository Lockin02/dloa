var show_page = function(page) {
	$("#myinvotherGrid").yxsubgrid("reload");
};
$(function() {
	$("#myinvotherGrid").yxsubgrid({
		model: 'finance_invother_invother',
		action: 'myInvotherListPageJson',
		title: 'Ӧ��������Ʊ',
		isDelAction: false,
		param: {
			createId: $("#createId").val()
		},
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'invoiceCode',
			display: '��Ʊ���',
			sortable: true,
			width: 140,
			hide: true
		}, {
			name: 'invoiceNo',
			display: '��Ʊ����',
			sortable: true
		}, {
			name: 'supplierName',
			display: '��Ӧ������',
			sortable: true,
			width: 120
		}, {
			name: 'formDate',
			display: '��������',
			sortable: true,
			width: 80
		}, {
			name: 'payDate',
			display: '��������',
			sortable: true,
			width: 80
		}, {
			name: 'isRed',
			display: '�Ƿ����',
			sortable: true,
			hide: true
		}, {
			name: 'taxRate',
			display: '˰��(%)',
			sortable: true,
			width: 60
		}, {
			name: 'invType',
			display: '��Ʊ����',
			sortable: true,
			datacode: 'FPLX',
			width: 80
		}, {
			name: 'formNumber',
			display: '������',
			sortable: true,
			width: 80
		}, {
			name: 'amount',
			display: '�ܽ��',
			sortable: true,
			width: 80,
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			name: 'formAssessment',
			display: '����˰��',
			sortable: true,
			width: 80,
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			name: 'formCount',
			display: '��˰�ϼ�',
			sortable: true,
			width: 80,
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			name: 'departments',
			display: '����',
			sortable: true,
			width: 80
		}, {
			name: 'salesman',
			display: 'ҵ��Ա',
			sortable: true,
			width: 80
		}, {
			name: 'ExaStatus',
			display: '���״̬',
			sortable: true,
			width: 80,
			process: function(v) {
				if (v == 1) {
					return '�����';
				} else {
					return 'δ���';
				}
			}
		}, {
			name: 'ExaDT',
			display: '�������',
			sortable: true,
			hide: true
		}, {
			name: 'exaMan',
			display: '�����',
			sortable: true,
			width: 80
		}, {
			name: 'belongId',
			display: '������Ʊid',
			sortable: true,
			hide: true
		}, {
			name: 'updateTime',
			display: '�������',
			sortable: true,
			width: 130
		}
		],
		// ���ӱ������
		subGridOptions: {
			url: '?model=finance_invother_invotherdetail&action=pageJson',
			param: [
				{
					paramId: 'mainId',// ���ݸ���̨�Ĳ�������
					colId: 'id'// ��ȡ���������ݵ�������
				}
			],
			colModel: [{
				name: 'productName',
				display: '��Ʊ��Ŀ',
				width: 140
			}, {
				display: '����',
				name: 'number'
			}, {
				name: 'price',
				display: '����',
				process: function(v, row, parentRow) {
					return moneyFormat2(v, 6, 6);
				}
			}, {
				name: 'taxPrice',
				display: '��˰����',
				process: function(v) {
					return moneyFormat2(v, 6, 6);
				}
			}, {
				name: 'assessment',
				display: '˰��',
				process: function(v) {
					return moneyFormat2(v);
				},
				width: 70
			}, {
				name: 'amount',
				display: '���',
				process: function(v) {
					return moneyFormat2(v);
				},
				width: 80
			}, {
				name: 'allCount',
				display: '��˰�ϼ�',
				process: function(v) {
					return moneyFormat2(v);
				},
				width: 80
			}, {
				name: 'objCode',
				display: 'Դ�����',
				width: 120
			}]
		},
		toAddConfig: {
			toAddFn: function() {
				showModalWin("?model=finance_invother_invother&action=toAdd");
			}
		},
		toEditConfig: {
			showMenuFn: function(row) {
				return row.ExaStatus == "0";
			},
			toEditFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_invother_invother&action=toEdit&id=" + rowData[p.keyField]);
			}
		},
		toViewConfig: {
			toViewFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_invother_invother&action=toView&id=" + rowData[p.keyField]);
			}
		},
		menusEx: [{
			text: "ɾ��",
			icon: 'delete',
			showMenuFn: function(row) {
				return row.ExaStatus;
			},
			action: function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type: "POST",
						url: "?model=finance_invother_invother&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function(msg) {
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
		searchitems: [{
			display: "��Ʊ����",
			name: 'invoiceNoSearch'

		}, {
			display: "��Ʊ���",
			name: 'invoiceCodeSearch'

		}, {
			display: "��Ӧ��",
			name: 'supplierName'

		}, {
			display: "��������",
			name: 'departments'

		}, {
			display: "ҵ����Ա",
			name: 'salesman'

		}, {
			display: "�����",
			name: 'exaMan'
		}],
		comboEx: [{
			text: "���״̬",
			key: 'ExaStatus',
			value: '0',
			data: [{
				text: 'δ���',
				value: '0'
			}, {
				text: '�����',
				value: '1'
			}]
		}]
	});
});