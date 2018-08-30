var show_page = function() {
	$("#payablesapplyGrid").yxsubgrid("reload");
};

$(function() {
	$("#payablesapplyGrid").yxsubgrid({
		model: 'finance_payablesapply_payablesapply',
		action: "myApplyJson",
		title: '�ҵĸ�������',
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		customCode: 'myPayablesapplyGrid',
		sortname: 'c.status,c.auditDate',
		sortorder: 'ASC',
		//����Ϣ
		colModel: [{
			display: '��ӡ',
			name: 'printId',
			width: 30,
			align: 'center',
			sortable: false,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				if (row.printCount > 0) {
					return '<img src="images/icon/print.gif" title="�Ѵ�ӡ����ӡ����Ϊ:' + row.printCount + '"/>';
				} else {
					return '<img src="images/icon/print1.gif" title="δ��ӡ���ĵ���"/>';
				}
			}
		}, {
			display: '�����',
			name: 'id',
			width: 60,
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') {
					return v;
				}
				if (row.payFor == 'FKLX-03') {
					if (row.sourceType != '') {
						return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					} else {
						return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}
				} else {
					if (row.sourceType != '') {
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					} else {
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}
				}
			}
		}, {
			name: 'formNo',
			display: '���뵥���',
			sortable: true,
			width: 140,
			process: function(v, row) {
				if (row.id == 'noId') {
					return v;
				}
				if (row.payFor == 'FKLX-03') {
					if (row.sourceType != '') {
						return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					} else {
						return "<a href='javascript:void(0)' title='�˿�����' style='color:red' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}
				} else {
					if (row.sourceType != '') {
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					} else {
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
					}
				}
			}
		}, {
			name: 'formDate',
			display: '��������',
			sortable: true,
			width: 80
		}, {
			name: 'payDate',
			display: '������������',
			sortable: true,
			width: 80
		}, {
			name: 'actPayDate',
			display: 'ʵ�ʸ�������',
			sortable: true,
			width: 80
		}, {
			name: 'sourceType',
			display: 'Դ������',
			sortable: true,
			datacode: 'YFRK',
			width: 80
		}, {
			name: 'payFor',
			display: '��������',
			sortable: true,
			datacode: 'FKLX',
			width: 80
		}, {
			name: 'supplierName',
			display: '��Ӧ������',
			sortable: true,
			width: 150
		}, {
			name: 'payMoney',
			display: '������',
			sortable: true,
			process: function(v) {
				if (v >= 0) {
					return moneyFormat2(v);
				} else {
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width: 80
		},
        {
                name: 'pchMoney',//Դ�����
                display: 'Դ����ͬ���',
                sortable: false,
                process: function(v) {
                    if (v >= 0) {
                        return moneyFormat2(v);
                    } else {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                },
                width: 80
            },
            {
			name: 'payedMoney',
			display: '�Ѹ����',
			sortable: true,
			process: function(v) {
				if (v >= 0) {
					return moneyFormat2(v);
				} else {
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width: 80
		}, {
            name: 'payMoneyCur',
            display: '��λ�ҽ��',
            sortable: true,
            process: function (v) {
                if (v >= 0) {
                    return moneyFormat2(v);
                } else {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
            },
            width: 80
        }, {
            name: 'currency',
            display: '�������',
            sortable: true,
            width: 60
        }, {
            name: 'rate',
            display: '����',
            sortable: true,
            width: 60
        }, {
			name: 'status',
			display: '����״̬',
			sortable: true,
			datacode: 'FKSQD',
			width: 70
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true,
			width: 80
		}, {
			name: 'ExaDT',
			display: '����ʱ��',
			sortable: true,
			width: 80
		}, {
			name: 'deptName',
			display: '���벿��',
			sortable: true
		}, {
			name: 'salesman',
			display: '������',
			sortable: true
		}, {
			name: 'feeDeptName',
			display: '���ù�������',
			sortable: true,
			width: 80
		}, {
			name: 'feeDeptId',
			display: '���ù�������id',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'businessBelongName',
			display: '������˾',
			sortable: true,
			width: 80
		}, {
			name: 'isInvoice',
			display: '�Ƿ񿪾ݷ�Ʊ',
			sortable: true,
			width: 80,
			process: function(v, row) {
				if (row.sourceType == 'YFRK-02') {
					if (v == '1') {
						return '��';
					} else if (v == '0') {
						return '��';
					}
				}
				else
					return '-';
			}
		}, {
			name: 'comments',
			display: '��ע',
			sortable: true,
			width: 80
		}, {
			name: 'createName',
			display: '������',
			sortable: true
		}, {
			name: 'createTime',
			display: '��������',
			sortable: true,
			width: 120,
			hide: true
		}, {
			name: 'printCount',
			display: '��ӡ����',
			sortable: true,
			width: 80
		}],
		// ���ӱ������
		subGridOptions: {
			url: '?model=finance_payablesapply_detail&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param: [
				{
					paramId: 'payapplyId',// ���ݸ���̨�Ĳ�������
					colId: 'id'// ��ȡ���������ݵ�������
				}
			],
			// ��ʾ����
			colModel: [{
				name: 'objType',
				display: 'Դ������',
				datacode: 'YFRK'
			}, {
				name: 'objCode',
				display: 'Դ�����',
				width: 150
			}, {
				name: 'money',
				display: '������',
				process: function(v) {
					return moneyFormat2(v);
				}
			}, {
				name: 'purchaseMoney',
				display: 'Դ�����',
				process: function(v) {
					return moneyFormat2(v);
				}
			}]
		},
		toAddConfig: {
			toAddFn: function() {
				showModalWin("?model=finance_payablesapply_payablesapply&action=toAddDept&sourceType=YFRK-04");
			}
		},
		toViewConfig: {
			toViewFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					if (rowData.sourceType != '') {
						showModalWin("?model=finance_payablesapply_payablesapply&action=toView&id=" + rowData.id + keyUrl, 1);
					} else {
						showModalWin("?model=finance_payablesapply_payablesapply&action=init&perm=view&id=" + rowData.id + keyUrl, 1);
					}
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		menusEx: [
			{
				text: '�༭',
				icon: 'edit',
				showMenuFn: function(row) {
					return row.ExaStatus == '���ύ';
				},
				action: function(row) {
					if (row.sourceType != '') {
						showModalWin("?model=finance_payablesapply_payablesapply&action=toEdit&id=" + row.id + '&skey=' + row['skey_'], 1);
					} else {
						showModalWin("?model=finance_payablesapply_payablesapply&action=init&owner=my&id=" + row.id + '&skey=' + row['skey_'], 1);
					}
				}
			},
			{
				text: '�ύ����',
				icon: 'add',
				showMenuFn: function(row) {
					return row.ExaStatus == '���ύ';
				},
				action: function(row) {
					if (row.sourceType != 'YFRK-01') {
						//add chenrf 20130504    ������ͬ�˿�����
						if (row.payFor == 'FKLX-03') {
							showThickboxWin('controller/finance/payablesapply/ewf_indexpayback.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + Math.abs(row.payMoney)
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row.skey_
							+ '&billCompany=' + row.businessBelong
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						} else {
							//��������������
							showThickboxWin('controller/finance/payablesapply/ewf_index1.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + row.payMoney
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row.skey_
							+ '&billCompany=' + row.businessBelong
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						}
					} else {
						if (row.payFor == 'FKLX-03') {
							showThickboxWin('controller/finance/payablesapply/ewf_indexback.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + Math.abs(row.payMoney)
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row.skey_
							+ '&billCompany=' + row.businessBelong
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");

						} else {
							showThickboxWin('controller/finance/payablesapply/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id + '&flowMoney=' + row.payMoney
							+ '&billDept=' + row.feeDeptId
							+ '&skey=' + row.skey_
							+ '&billCompany=' + row.businessBelong
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
						}
					}
				}
			},
			{
				text: 'ɾ��',
				icon: 'delete',
				showMenuFn: function(row) {
					return row.ExaStatus == '���ύ';
				},
				action: function(row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type: "POST",
							url: "?model=finance_payablesapply_payablesapply&action=ajaxdeletes",
							data: {
								id: row.id
							},
							success: function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									show_page();
								} else {
									alert('ɾ��ʧ�ܣ�');
								}
							}
						});
					}
				}
			},
			{
				text: '�ύ����֧��',
				icon: 'edit',
				showMenuFn: function(row) {
					return row.status == 'FKSQD-00' && row.ExaStatus == '���';
				},
				action: function(row) {
					if (row.payDate == "") {
						showThickboxWin('?model=finance_payablesapply_payablesapply&action=toConfirm&id='
						+ row.id
						+ '&supplierName=' + row['supplierName']
						+ '&payMoney=' + row['payMoney']
						+ '&skey=' + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
					} else {
						var thisDate = formatDate(new Date());
						var s = DateDiff(thisDate, row.payDate);
						// if (s > 0) {
						// 	alert('���������������ڻ��� ' + s + " �죬�ݲ����ύ����֧��");
						// } else {
							showThickboxWin('?model=finance_payablesapply_payablesapply&action=toConfirm&id='
							+ row.id
							+ '&supplierName=' + row['supplierName']
							+ '&payMoney=' + row['payMoney']
							+ '&skey=' + row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
						// }
					}
				}
			}, {
				name: 'file',
				text: '�ϴ�����',
				icon: 'add',
				showMenuFn: function(row) {
					if (row.status == 3) {
						return false;
					}
				},
				action: function(row) {
					showThickboxWin("?model=finance_payablesapply_payablesapply&action=toUploadFile&id="
					+ row.id
					+ "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				}
			}, {
				text: '��ӡ',
				icon: 'print',
				showMenuFn: function(row) {
					return row.status != 'FKSQD-04';
				},
				action: function(row) {
					showModalWin("?model=finance_payablesapply_payablesapply&action=toPrint&id=" + row.id + '&skey=' + row['skey_'], 1);
				}
			}, {
				text: '�ر�',
				icon: 'delete',
				showMenuFn: function(row) {
					return row.ExaStatus == '���' && (row.status == 'FKSQD-01' || row.status == 'FKSQD-00');
				},
				action: function(row) {
					showThickboxWin('?model=finance_payablesapply_payablesapply&action=toClose&id='
					+ row.id
					+ '&skey=' + row['skey_']
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}, {
				text: '��������',
				icon: 'edit',
				showMenuFn: function(row) {
					return row.ExaStatus == '��������';
				},
				action: function(row) {
					if (row) {
						if (row.sourceType != 'YFRK-01') {
							//add chenrf 20130504    ������ͬ�˿�����
							if (row.payFor == 'FKLX-03') {
								var ewfurl = 'controller/finance/payablesapply/ewf_indexpayback.php?actTo=delWork&billId=';
							} else {
								//��������
								var ewfurl = 'controller/finance/payablesapply/ewf_index1.php?actTo=delWork&billId=';
							}

						} else {//�ɹ�����
							if (row.payFor == 'FKLX-03') {
								var ewfurl = 'controller/finance/payablesapply/ewf_indexpayback.php?actTo=delWork&billId=';
							} else {
								var ewfurl = 'controller/finance/payablesapply/ewf_index.php?actTo=delWork&billId=';
							}
						}

						$.ajax({
							type: "POST",
							url: "?model=common_workflow_workflow&action=isAudited",
							data: {
								billId: row.id,
								examCode: 'oa_finance_payablesapply'
							},
							success: function(msg) {
								if (msg == '1') {
									alert('�����Ѿ�����������Ϣ�����ܳ���������');
									show_page();
								} else {
									if (confirm('ȷ��Ҫ����������')) {
										$.ajax({
											type: "GET",
											url: ewfurl,
											data: {"billId": row.id},
											async: false,
											success: function(data) {
												alert(data);
												show_page();
											}
										});
									}
								}
							}
						});
					} else {
						alert("��ѡ��һ������");
					}
				}
			}
		],
		//��������
		comboEx: [{
			text: '����״̬',
			key: 'ExaStatus',
			type: 'workFlow'
		}, {
			text: '����״̬',
			key: 'status',
			datacode: 'FKSQD'
		}, {
			text: 'Դ������',
			key: 'sourceType',
			datacode: 'YFRK'
		}],
		searchitems: [{
			display: '��Ӧ������',
			name: 'supplierName'
		}, {
			display: '���뵥���',
			name: 'formNoSearch'
		}, {
			display: '�������',
			name: 'objCodeSearch'
		}, {
			display: '������',
			name: 'payMoney'
		}, {
			display: 'id',
			name: 'id'
		}]
	});
});