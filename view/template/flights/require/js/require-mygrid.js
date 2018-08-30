var show_page = function() {
	$("#myRequireGrid").yxsubgrid("reload");
};

//������ڸ���
function requireNoRed(v) {
	var strArr = v.split('');
	var newStr = '';
	for (var i = 0; i < strArr.length; i++) {
		if (i == 4) {
			newStr += "<span class='blue'>" + strArr[i];
		} else if (i == 11) {
			newStr += strArr[i] + "</span>";
		} else {
			newStr += strArr[i];
		}
	}
	return newStr;
}

$(function() {
	$("#myRequireGrid").yxsubgrid({
		model: 'flights_require_require',
		action: 'myPageJson',
		title: '�ҵĶ�Ʊ����',
		showcheckbox: false,
		isOpButton: false,
		isDelAction: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'requireNo',
			display: '��Ʊ�����',
			sortable: true,
			width: 120,
			process: function(v) {
				return requireNoRed(v);
			}
		}, {
			name: 'requireId',
			display: '������ID',
			hide: true,
			sortable: true
		}, {
			name: 'requireName',
			display: '������',
			hide: true,
			sortable: true
		}, {
			name: 'requireTime',
			display: '��������',
			sortable: true,
			hide: true,
			width: 70
		}, {
			name: 'companyName',
			display: '���ڹ�˾',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'deptName',
			display: '���ڲ���',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'airName',
			display: '�˻���',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'airPhone',
			display: '�ֻ�����',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'cardTypeName',
			display: '֤������',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'birthDate',
			display: '��������',
			hide: true,
			sortable: true
		}, {
			name: 'flyStartTime',
			display: '��ʼʱ��',
			hide: true,
			sortable: true
		}, {
			name: 'flyEndTime',
			display: '����ʱ��',
			hide: true,
			sortable: true
		}, {
			name: 'ticketType',
			display: '��Ʊ����',
			sortable: true,
			process: function(v) {
				if (v == "10") {
					return '����';
				} else if (v == "11") {
					return '����';
				} else if (v == "12") {
					return '����';
				}
			},
			width: 80
		}, {
			name: 'startPlace',
			display: '��������',
			sortable: true,
			width: 80
		}, {
			name: 'middlePlace',
			display: '��ת����',
			sortable: true,
			width: 80
		}, {
			name: 'endPlace',
			display: '�������',
			sortable: true,
			width: 80
		}, {
			name: 'startDate',
			display: '����ʱ��',
			sortable: true,
			width: 80
		}, {
			name: 'twoDate',
			display: '�ڶ�����ת����',
			sortable: true,
			width: 85
		}, {
			name: 'comeDate',
			display: '����ʱ��',
			sortable: true,
			width: 80
		}, {
			display: '��Ʊ״̬',
			name: 'ticketMsg',
			sortable: true,
			process: function(v) {
				if (v == "0") {
					return 'δ����';
				} else if (v == "1") {
					return ' ������';
				}
			},
			width: 70
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			width: 70
		}, {
			name: 'ExaDT',
			display: '����ʱ��',
			sortable: true,
			width: 70
		}, {
			name: 'detailType',
			display: '���ù�������',
			sortable: true,
			process: function(v) {
				if (v == "1") {
					return '���ŷ���';
				} else if (v == "2") {
					return '������Ŀ����';
				} else if (v == "3") {
					return '�з���Ŀ����';
				} else if (v == "4") {
					return '��ǰ����';
				} else if (v == "5") {
					return '�ۺ����';
				}
			},
			hide: true,
			width: 80
		}, {
			name: 'costBelongComId',
			display: '���ù�����˾Id',
			hide: true,
			sortable: true
		}, {
			name: 'costBelongCom',
			display: '���ù�����˾',
			hide: true,
			sortable: true
		}, {
			name: 'costBelongDeptId',
			display: '���ù�������Id',
			hide: true,
			sortable: true
		}, {
			name: 'costBelongDeptName',
			display: '���ù�������',
			hide: true,
			sortable: true
		}, {
			name: 'projectCode',
			display: '��ͬ���',
			hide: true,
			sortable: true
		}, {
			name: 'projectId',
			display: '��ͬId',
			hide: true,
			sortable: true
		}, {
			name: 'projectName',
			display: '��ͬ����',
			hide: true,
			sortable: true
		}, {
			name: 'proManagerName',
			display: '��Ŀ����',
			hide: true,
			sortable: true
		}, {
			name: 'contractCode',
			display: '��ͬ����',
			hide: true,
			sortable: true
		}, {
			name: 'contractId',
			display: '��ͬId',
			hide: true,
			sortable: true
		}, {
			name: 'contractName',
			display: '��ͬ����',
			hide: true,
			sortable: true
		}, {
			name: 'customerId',
			display: '�ͻ�ID',
			hide: true,
			sortable: true
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			hide: true,
			sortable: true
		}, {
			name: 'createName',
			display: '������',
			hide: true,
			sortable: true
		}, {
			name: 'createTime',
			display: '����ʱ��',
			hide: true,
			sortable: true
		}, {
			name: 'updateName',
			display: '������',
			hide: true,
			sortable: true
		}, {
			name: 'updateTime',
			display: '����ʱ��',
			sortable: true,
			width: 130
		}],
		// ���ӱ������
		subGridOptions: {
			url: '?model=flights_require_requiresuite&action=pageJson',
			param: [{
				paramId: 'mainId',
				colId: 'id'
			}],
			colModel: [{
				name: 'employeeTypeName',
				display: 'Ա������',
				sortable: true
			}, {
				name: 'airName',
				display: '����',
				sortable: true
			}, {
				name: 'airPhone',
				display: '�ֻ�����',
				sortable: true
			}, {
				name: 'cardTypeName',
				display: '֤������',
				sortable: true,
				width: 80
			}, {
				name: 'cardNo',
				display: '֤������',
				sortable: true,
				width: 140
			}, {
				name: 'validDate',
				display: '֤����Ч��',
				sortable: true,
				width: 80
			}, {
				name: 'birthDate',
				display: '��������',
				sortable: true,
				width: 80
			}, {
				name: 'nation',
				display: '����',
				sortable: true
			}, {
				name: 'tourAgencyName',
				display: '���ÿͻ���',
				sortable: true
			}, {
				name: 'tourCardNo',
				display: '���ÿͿ���',
				sortable: true
			}]
		},
		toAddConfig: {
			toAddFn: function() {
				alert("���!��Ʊģ������������,�뵽��OAϵͳ�ύ��������,лл!");
				return false;
				showModalWin("?model=flights_require_require&action=toAdd");
			}
		},
		toEditConfig: {
			showMenuFn: function(row) {
				return row.ExaStatus == '���ύ' || row.ExaStatus == '���';
			},
			toEditFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=flights_require_require&action=toEdit&id=" + rowData[p.keyField]);
			}
		},
		toViewConfig: {
			toViewFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=flights_require_require&action=viewTab&id=" + rowData[p.keyField]);
			}
		},
		// ��չ�Ҽ��˵�
		menusEx: [
			{
				name: 'status',
				text: '�ύ����',
				icon: 'view',
				showMenuFn: function(row) {
					return !(row.ExaStatus == "���" || row.ExaStatus == "��������");
				},
				action: function(row) {
					$.ajax({
						type: "POST",
						url: "?model=flights_require_require&action=isUserNeedAudit",
						async: false,
						success: function(data) {
							if (data == 1) {
								var days = DateDiff(row.requireTime, row.startDate);
								if (days > 3) {
									showThickboxWin('controller/flights/require/ewf_index.php?actTo=ewfSelect&billId=' + row.id + "&flowMoney=6&billDept=" + row.costBelongDeptId + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								} else {
									showThickboxWin('controller/flights/require/ewf_index.php?actTo=ewfSelect&billId=' + row.id + "&flowMoney=3&billDept=" + row.costBelongDeptId + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
								}
							} else {
								// �첽�ύ
								$.ajax({
									type: "POST",
									url: "?model=flights_require_require&action=ajaxSubmit",
									data: {
										id: row.id
									},
									success: function(data) {
										if (data == 1) {
											alert("�ύ�ɹ�����ǰ��Ʊ��������������");
											show_page();
										} else {
											alert("�ύʧ��");
										}
									}
								});
							}
						}
					});
				}
			}, {
				name: 'aduit',
				text: '�������',
				icon: 'view',
				showMenuFn: function(row) {
					return row.ExaStatus != "���ύ";
				},
				action: function(row) {
					if (row) {
						showThickboxWin("controller/common/readview.php?itemtype=oa_flights_require&pid=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
					}
				}
			},
			{
				name: 'delete',
				text: 'ɾ��',
				icon: 'delete',
				showMenuFn: function(row) {
					return !(row.ExaStatus == "���" || row.ExaStatus == "��������" || row.ticketMsg == '1');
				},
				action: function(row) {
					if (confirm("ȷ��Ҫɾ����?")) {
						$.ajax({
							type: "POST",
							url: "?model=flights_require_require&action=ajaxdeletes",
							data: {
								id: row.id
							},
							async: false,
							success: function(data) {
								if (data == 1) {
									alert("ɾ���ɹ�");
									show_page();
								} else {
									alert("ɾ��ʧ��");
								}
							}
						});
					}
				}
			}],
		//��������
		comboEx: [{
			text: '��Ʊ״̬',
			key: 'ticketMsg',
			data: [{
				text: '������',
				value: '1'
			}, {
				text: 'δ����',
				value: '0'
			}]
		}, {
			text: '����״̬',
			key: 'ExaStatus',
			type: 'workFlow'
		}],
		searchitems: [{
			display: "��Ʊ�����",
			name: 'requireNoSearch'
		}, {
			display: "�˻���",
			name: 'airNameSearch'
		}, {
			display: "��������",
			name: 'startPlaceSearch'
		}, {
			display: "��ת����",
			name: 'middlePlaceSearch'
		}, {
			display: "�������",
			name: 'endPlaceSearch'
		}],
		sortname: "c.updateTime"
	});
});