var show_page = function() {
	$("#expenseGrid").yxgrid("reload");
};

//�鿴���� - �����¾ɱ�����
function viewBill(id, billNo, isNew) {
	if (isNew == '1') {
		showModalWin("?model=finance_expense_exsummary&action=toView&id=" + id);
	} else {
		showOpenWin("general/costmanage/reim/summary_detail.php?status=���ɸ���&BillNo=" + billNo);
	}
}

$(function() {
	//���Ȩ��
	var checkLimit = $("#checkLimit").val();
	var thisAction; //����Դ
	if (checkLimit == "1") {//�����Ȩ�ޣ����ȥ�������Դ
		thisAction = "pageJson";
	} else {
		thisAction = "myPageJson";
	}

	var thisHeight = document.documentElement.clientHeight - 125;
	$("#expenseGrid").yxgrid({
		model: 'finance_expense_expense',
		action: thisAction,
		title: '���ż���б�',
		height: thisHeight,
		isDelAction: false,
		param: {isNew: 1, 'needExpenseCheck': 1, isPush: 0, checkList: 1},
		customCode: 'checkexpense',
		showcheckbox: false,
		isAddAction: false,
		isOpButton: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			display: '�յ�',
			name: 'recView',
			sortable: true,
			align: 'center',
			width: 30,
			process: function(v, row) {
				if (row.needExpenseCheck == "1") {
					if (row.IsFinRec == '1') {
						return '<img title="�����յ�[' + row.RecInvoiceDT + '] \n�Ͻ�����[' + row.HandUpDT + '] \n�����յ�[' + row.FinRecDT + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
					} else {
						if (row.isHandUp == "1") {
							return '<img title="�����յ�[' + row.RecInvoiceDT + '] \n�Ͻ�����[' + row.HandUpDT + ']" src="images/icon/ok2.png" style="width:15px;height:15px;">';
						} else {
							if (row.isNotReced == '0') {
								return '<img title="�����յ�[' + row.RecInvoiceDT + ']" src="images/icon/ok1.png" style="width:15px;height:15px;">';
							}
						}
					}
				} else {
					if (row.IsFinRec == '1') {
						return '<img title="�����յ�[' + row.FinRecDT + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
					}
				}
			}
		}, {
			display: '�����յ�״̬',
			name: 'isNotReced',
			sortable: true,
			width: 60,
			hide: true,
			process: function(v) {
				return v == '0' ? '���յ�' : 'δ�յ�';
			}
		}, {
			display: '�����յ�ʱ��',
			name: 'RecInvoiceDT',
			sortable: true,
			width: 120,
			hide: true
		}, {
			display: '�����Ͻ�״̬',
			name: 'isHandUp',
			sortable: true,
			width: 60,
			hide: true,
			process: function(v) {
				return v == '1' ? '���Ͻ�' : 'δ�Ͻ�';
			}
		}, {
			display: '�����Ͻ�ʱ��',
			name: 'HandUpDT',
			sortable: true,
			width: 120,
			hide: true
		}, {
			display: '�����յ�״̬',
			name: 'IsFinRec',
			sortable: true,
			width: 60,
			hide: true,
			process: function(v) {
				return v == '1' ? '���յ�' : 'δ�յ�';
			}
		}, {
			display: '�����յ�ʱ��',
			name: 'FinRecDT',
			sortable: true,
			width: 120,
			hide: true
		}, {
			display: '�±�����',
			name: 'isNew',
			sortable: true,
			width: 50,
			process: function(v) {
				return v == '1' ? '��' : '��';
			},
			hide: true
		}, {
			name: 'BillNo',
			display: '��������',
			sortable: true,
			width: 130,
			process: function(v, row) {
				return "<a href='javascript:void(0)' onclick='viewBill(\"" + row.id + "\",\"" + row.BillNo + "\",\"" + row.isNew + "\")'>" + v + "</a>";
			}
		}, {
			name: 'DetailType',
			display: '��������',
			sortable: true,
			width: 70,
			process: function(v, row) {
				if (v * 1 > 0) {
					switch (v) {
//						case '0' : return '�ɱ�����';break;
						case '1' :
							return '���ŷ���';
						case '2' :
							return '��ͬ��Ŀ����';
						case '3' :
							return '�з�����';
						case '4' :
							return '��ǰ����';
						case '5' :
							return '�ۺ����';
						default :
							return v;
					}
				} else {
					switch (row.CostBelongTo) {
						case '1' :
							return '���ŷ���';
						default :
							return '���̷���';
					}
				}
			}
		}, {
			name: 'CostManName',
			display: '������',
			sortable: true,
			width: 90
		}, {
			name: 'CostDepartName',
			display: '�����˲���',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'CostManCom',
			display: '�����˹�˾',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'CostBelongDeptName',
			display: '���ù�������',
			sortable: true,
			width: 75,
			hide: true
		}, {
			name: 'CostBelongCom',
			display: '���ù�����˾',
			sortable: true,
			width: 75,
			hide: true
		}, {
			name: 'Amount',
			display: '�������',
			sortable: true,
			width: 80,
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			name: 'feeRegular',
			display: '�������',
			sortable: true,
			width: 80,
			process: function(v) {
				return moneyFormat2(v);
			},
			hide: true
		}, {
			name: 'feeSubsidy',
			display: '��������',
			sortable: true,
			width: 80,
			process: function(v) {
				return moneyFormat2(v);
			},
			hide: true
		}, {
			name: 'invoiceMoney',
			display: '��Ʊ���',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'invoiceNumber',
			display: '��Ʊ����',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'CheckAmount',
			display: '�����',
			sortable: true,
			hide: true,
			width: 80,
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			name: 'isProject',
			display: '��Ŀ����',
			sortable: true,
			process: function(v) {
				return v == '1' ? '��' : '��';
			},
			width: 60,
			hide: true
		}, {
			name: 'ProjectNO',
			display: '��Ŀ���',
			sortable: true,
			width: 150,
			hide: true
		}, {
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width: 150,
			hide: true
		}, {
			name: 'proManagerName',
			display: '��Ŀ����',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'contractCode',
			display: '��ͬ���',
			sortable: true,
			width: 120,
			hide: true
		}, {
			name: 'contractName',
			display: '��ͬ����',
			sortable: true,
			width: 120,
			hide: true
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			sortable: true,
			width: 120,
			hide: true
		}, {
			name: 'CustomerType',
			display: '�ͻ�����',
			sortable: true,
			hide: true
		}, {
			name: 'Purpose',
			display: '����',
			sortable: true,
			width: 180
		}, {
			name: 'InputManName',
			display: '¼����',
			sortable: true,
			width: 90,
			hide: true
		}, {
			name: 'Status',
			display: '����״̬',
			sortable: true,
			width: 70,
			process: function(v, row) {
				if (v == '��������' && row.isFinAudit == "1") {
					return '��������';
				} else {
					return v;
				}
			}
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			width: 70,
			sortable: true,
			hide: true
		}, {
			name: 'ExaDT',
			display: '��������',
			width: 80,
			sortable: true,
			hide: true
		}, {
			name: 'InputDate',
			display: '¼��ʱ��',
			sortable: true,
			width: 130
		}, {
			name: 'subCheckDT',
			display: '�ύ���ʱ��',
			sortable: true,
			width: 130
		}, {
			name: 'UpdateDT',
			display: '����ʱ��',
			sortable: true,
			width: 130,
			hide: true
		}],
		toAddConfig: {
			toAddFn: function(p) {
				showModalWin("?model=finance_expense_expense&action=toAdd");
			}
		},
		//�򿪵���expense�еķ��� -- ���αȽ�����
		toEditConfig: {
			showMenuFn: function(row) {
				return row.isNew == '1' && row.Status == '���ż��' && row.ExaStatus == '�༭' && checkLimit == "1";
			},
			toEditFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_expense_expense&action=toEditCheck&id=" + rowData.id);
			}
		},
		//�򿪵���expense�еķ��� -- ���αȽ�����
		toViewConfig: {
			showMenuFn: function(row) {
				return row.isNew == '1';
			},
			toViewFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_expense_expense&action=toView&id=" + rowData.id)
			}
		},
		menusEx: [{
			text: "�����յ�",
			icon: 'edit',
			showMenuFn: function(row) {
				return row.isNotReced == '1' && row.Status != "�༭" && checkLimit == "1";
			},
			action: function(row) {
				if (confirm('ȷ���յ���')) {
					$.ajax({
						type: "POST",
						url: "?model=finance_expense_expense&action=ajaxDeptRec",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == '1') {
								alert('�յ��ɹ���');
								show_page(1);
							} else {
								alert("�յ�ʧ��! ");
							}
						}
					});
				}
			}
		}, {
			text: "�Ͻ�����",
			icon: 'edit',
			showMenuFn: function(row) {
				return row.isNotReced == '0' && row.isHandUp == "0" && checkLimit == "1";
			},
			action: function(row) {
				if (confirm('ȷ���Ͻ�������')) {
					$.ajax({
						type: "POST",
						url: "?model=finance_expense_expense&action=ajaxHandFinance",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == '1') {
								alert('�Ͻ��ɹ���');
								show_page(1);
							} else {
								alert("�Ͻ�ʧ��! ");
							}
						}
					});
				}
			}
		}, {
			text: "���±༭",
			icon: 'edit',
			showMenuFn: function(row) {
				return row.isNew == '1' && row.Status == '���ż��' && row.ExaStatus == '���' && checkLimit == "1";
			},
			action: function(row) {
				showModalWin("?model=finance_expense_expense&action=toEditCheck&id=" + row.id);
			}
		}, {
			text: "�ύ����",
			icon: 'edit',
			showMenuFn: function(row) {
				return row.isNew == '1' && row.Status == '���ż��' && checkLimit == "1";
			},
			action: function(row) {
				if (row.CheckAmount != row.Amount) {
					alert('���ݽ�����������ǰ���ݽ��Ϊ: ' + row.Amount + ',���󵥾ݽ��Ϊ:' + row.CheckAmount + ',������ȷ�ϴ˱�����');
					return false;
				}

				//��������ǹ��̱��������Һ�����Ŀ��Ϣ,��ȡ��Ŀ����
				var rangeId = '';
				if (row.projectType == "esm" && row.projectId != "0") {
					$.ajax({
						type: "POST",
						url: "?model=engineering_project_esmproject&action=getRangeId",
						data: {'projectId': row.projectId},
						async: false,
						success: function(data) {
							rangeId = '&billArea=' + data;
						}
					});
				}
				if (row.isLate == "1") {
					showThickboxWin('controller/finance/expense/ewf_indexlate.php?actTo=ewfSelect&billId='
					+ row.id + '&flowMoney=' + row.Amount
					+ '&billDept=' + row.CostBelongDeptId
					+ '&billCompany=' + row.CostBelongComId
					+ rangeId
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					showThickboxWin('controller/finance/expense/ewf_index.php?actTo=ewfSelect&billId='
					+ row.id + '&flowMoney=' + row.Amount
					+ '&billCompany=' + row.CostBelongComId
					+ rangeId
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		}, {
			text: "�������",
			icon: 'view',
			showMenuFn: function(row) {
				return row.ExaStatus != '�༭';
			},
			action: function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=cost_summary_list'
				+ '&pid=' + row.id
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text: "���",
			icon: 'delete',
			showMenuFn: function(row) {
				return (row.Status == '���' || row.Status == '���ż��') && checkLimit == "1";
			},
			action: function(row) {
				if (confirm('ȷ��Ҫ��ش˵�����')) {
					$.ajax({
						type: "POST",
						url: "?model=finance_expense_expense&action=ajaxBack",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == '1') {
								alert('��سɹ���');
								show_page(1);
							} else {
								alert("���ʧ��! ");
							}
						}
					});
				}
			}
		}, {
			text: "��",
			icon: 'print',
			showMenuFn: function(row) {
				return row.Status != '�༭' && row.Status != '���' && row.Status != '���ż��' && checkLimit == "1";
			},
			action: function(row) {
				showOpenWin("?model=cost_bill_billcheck&action=print_bill&billno=" + row.BillNo);
			}
		}],
		//��������
		comboEx: [{
			text: '��������',
			key: 'DetailType',
			data: [{
				text: '���ŷ���',
				value: '1'
			}, {
				text: '��ͬ��Ŀ����',
				value: '2'
			}, {
				text: '�з�����',
				value: '3'
			}, {
				text: '��ǰ����',
				value: '4'
			}, {
				text: '�ۺ����',
				value: '5'
			}, {
				text: '�ɱ�����',
				value: '0'
			}]
		}, {
			text: '����״̬',
			key: 'Status',
			value: '���ż��',
			data: [{
				text: '�༭',
				value: '�༭'
			}, {
				text: '���ż��',
				value: '���ż��'
			}, {
				text: '�ȴ�ȷ��',
				value: '�ȴ�ȷ��'
			}, {
				text: '��������',
				value: '��������'
			}, {
				text: '�������',
				value: '�������'
			}, {
				text: '���ɸ���',
				value: '���ɸ���'
			}, {
				text: '���',
				value: '���'
			}]
		}],
		searchitems: [{
			display: "��������",
			name: 'BillNoSearch'
		}, {
			display: "��Ŀ����",
			name: 'projectNameSearch'
		}, {
			display: "��Ŀ���",
			name: 'projectCodeSearch'
		}, {
			display: "�̻����",
			name: 'chanceCodeSearch'
		}, {
			display: "�̻�����",
			name: 'chanceNameSearch'
		}, {
			display: "��ͬ���",
			name: 'contractCodeSearch'
		}, {
			display: "��ͬ����",
			name: 'contractNameSearch'
		}, {
			display: "�������",
			name: 'Amount'
		}, {
			display: "������",
			name: 'InputManNameSearch'
		}, {
			display: "����",
			name: 'PurposeSearch'
		}],
		sortname: "c.UpdateDT"
	});
});