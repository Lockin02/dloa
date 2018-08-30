var show_page = function() {
	$("#withdrawGrid").yxsubgrid("reload");
};
$(function() {
	$("#withdrawGrid").yxsubgrid({
		model: 'stock_withdraw_withdraw',
		title: '�ջ�֪ͨ��',
		showcheckbox: false,
		isAddAction: false,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		isOpButton: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'docStatus',
			display: '�⳥״̬',
			sortable: true,
			align: 'center',
			process: function(v) {
				switch (v) {
					case '0' :
						return '<img src="images/icon/cicle_grey.png" title="����"/>';
					case '1' :
						return '<img src="images/icon/cicle_yellow.png" title="�������⳥��"/>';
					case '2' :
						return '<img src="images/icon/cicle_green.png" title="������⳥��"/>';
					default:
				}
			},
			width: 50
		}, {
			name: 'planCode',
			display: '֪ͨ����',
			width: 110,
			sortable: true
		}, {
			name: 'docType',
			display: 'Դ������',
			sortable: true,
			width: 60,
			process: function(v) {
				if (v == 'oa_contract_exchange') {
					return '����';
				}
			}
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			width: 180,
			sortable: true
		}, {
			name: 'docId',
			display: 'Դ��Id',
			sortable: true,
			hide: true
		}, {
			name: 'docCode',
			display: 'Դ����',
			width: 120,
			sortable: true
		}, {
			name: 'planIssuedDate',
			display: '֪ͨ���´�����',
			width: 80,
			sortable: true
		}, {
			name: 'issuedStatus',
			display: '�´�״̬',
			width: 80,
			process: function(v) {
				return v == '1' ? "���´�" : "δ�´�";
			},
			sortable: true,
			hide: true
		}, {
		//	name: 'docStatus',
		//	display: '�ջ�״̬',
		//	width: 80,
		//	sortable: true,
		//	hide: true
		//}, {
			name: 'shipPlanDate',
			display: '�ƻ��ջ�����',
			width: 80,
			sortable: true
		}, {
			name: 'status',
			display: '����״̬',
			process: function(v) {
				switch (v) {
					case 'YZX' :
						return '�Ѵ���';
					case 'BFZX' :
						return '���ڴ���';
					case 'WZX' :
						return '������';
					default :
						return '������';
				}
			},
			width: 80,
			sortable: true
		}, {
			name: 'docApplicant',
			display: 'Դ��������',
			sortable: true
		}],
		// ���ӱ������
		subGridOptions: {
			url: '?model=stock_withdraw_withdraw&action=equJson',
			param: [{
				paramId: 'mainId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				width: 80,
				display: '���ϱ��'
			}, {
				name: 'productName',
				width: 150,
				display: '��������'
			}, {
				name: 'productModel',
				width: 120,
				display: '�ͺ�/�汾'
			}, {
				name: 'unitName',
				display: '��λ',
				width: 60
			}, {
				name: 'number',
				display: '����',
				width: 70
			}, {
				name: 'qualityNum',
				display: '��������',
				width: 70
			}, {
				name: 'qPassNum',
				display: '�ϸ�����',
				width: 70
			}, {
				name: 'qBackNum',
				display: '���ϸ�����',
				width: 70
			}, {
				name: 'executedNum',
				display: '���ջ�����',
				width: 70
			}, {
				name: 'compensateNum',
				display: '�⳥����',
				width: 70
			}]
		},
		searchitems: [{
			display: "֪ͨ����",
			name: 'planCode'
		}, {
			display: "Դ����",
			name: 'docCode'
		}, {
			display: "Դ������",
			name: 'docName'
		}],
		//��������
		comboEx: [{
			text: '����״̬',
			key: 'statusArr',
			value: 'WZX,BFZX',
			data: [{
				text: '�Ѵ���',
				value: 'YZX'
			}, {
				text: '���ڴ���',
				value: 'BFZX'
			}, {
				text: '������',
				value: 'WZX'
			}, {
				text: '������,���ڴ���',
				value: 'WZX,BFZX'
			}]
		}, {
			text: '�⳥״̬',
			key: 'docStatus',
			data: [{
				text: '����',
				value: '0'
			}, {
				text: '�������⳥��',
				value: '1'
			}, {
				text: '�������⳥��',
				value: '2'
			}]
		}],
		menusEx: [{
			text: '�鿴',
			icon: 'view',
			action: function(row) {
				showOpenWin('?model=stock_withdraw_withdraw&action=toView&id='
				+ row.id);
			}
		}, {
			text: '�ύ�ʼ�',
			icon: 'add',
			showMenuFn: function(row) {
				return row.status == "BFZX" || row.status == "WZX";
			},
			action: function(row) {
				if (row) {
					showOpenWin("?model=produce_quality_qualityapply&action=toAdd&relDocId="
						+ row.id
						+ "&relDocType=ZJSQYDHH"
						+ "&relDocCode=" + row.planCode
						, 1, 500, 900, row.id
					);
				}
			}
		}, {
			text: '��д���֪ͨ��',
			icon: 'add',
			showMenuFn: function(row) {
				return row.status == "BFZX";
			},
			action: function(row) {
				showOpenWin('?model=stock_withdraw_innotice&action=toAdd&id='
				+ row.id
				+ "&skey="
				+ row['skey_']
				+ '&docType='
				+ row.docType
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			text: '�´��⳥��',
			icon: 'add',
			showMenuFn: function(row) {
				//������豸��ʧ��ֱ��ȷ��
				return row.docStatus == "1";
			},
			action: function(row) {
				if (row) {
					showOpenWin("?model=finance_compensate_compensate&action=toAdd&relDocId="
						+ row.id + "&skey=" + row['skey_'] + "&relDocType=PCYDLX-02"
						, 1, 700, 1100, row.id);
				}
			}
		}, {
			text: '����⳥',
			icon: 'delete',
			showMenuFn: function(row) {
				//�豸��ʧ���ܽ��д˲���
				return row.docStatus == "1";
			},
			action: function(row) {
				if (window.confirm(("ȷ��Ҫ���д˲�����?"))) {
					$.ajax({
						type: "POST",
						url: "?model=stock_withdraw_withdraw&action=ajaxState",
						data: {
							id: row.id,
							state: 2
						},
						success: function(msg) {
							if (msg == 1) {
								alert('�����ɹ���');
							} else {
								alert('����ʧ�ܣ�');
							}
							show_page();
						}
					});
				}
			}
		}]
	});
});