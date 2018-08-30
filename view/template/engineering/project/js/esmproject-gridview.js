var show_page = function() {
	$("#esmprojectGrid").yxgrid("reload");
};

$(function() {
	$("#esmprojectGrid").yxgrid({
		model: 'engineering_project_esmproject',
		title: '��Ŀ���ܱ�',
		isDelAction: false,
		isAddAction: false,
		isViewAction: false,
		isEditAction: false,
		showcheckbox: false,
		customCode: 'esmprojectGrid',
		isOpButton: false,
		param: {
			"officeName": $("#officeName").val(),
			"province": $("#province").val(),
			"beginDateSearch": $("#beginDateSearch").val(),
			"endDateSearch": $("#endDateSearch").val(),
			"status": $("#status").val(),
			"nature": $("#nature").val(),
			"contractTypeMix": $("#contractTypeMix").val(),
			"productLine": $("#productLine").val()
		},
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width: 140,
			process: function(v, row) {
				return (row.contractId == "0" || row.contractId == "") && row.contractType != 'GCXMYD-04' ? "<span style='color:blue' title='δ������ͬ�ŵ���Ŀ'>" + v + "</span>" : v;
			}
		}, {
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true,
			width: 120,
			process: function(v, row) {
				return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
			}
		}, {
			name: 'exgross',
			display: 'ë����',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				if (v * 1 >= 0) {
					return v + " %";
				} else {
					return "<span class='red'>" + v + " %</span>";
				}
			},
			width: 60
		}, {
			name: 'budgetAll',
			display: '��Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeAllCount',
			display: '�ܾ���(ʵʱ)',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 70
		}, {
			name: 'feeAllProcessCount',
			display: '���ý���(ʵʱ)',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			width: 80
		}, {
			name: 'projectProcess',
			display: '���̽���',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			width: 60
		}, {
			name: 'processDiff',
			display: '���Ȳ���',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			width: 60
		}, {
			name: 'planBeginDate',
			display: 'Ԥ�ƿ�ʼ',
			sortable: true,
			width: 80
		}, {
			name: 'planEndDate',
			display: 'Ԥ�ƽ���',
			sortable: true,
			width: 80
		}, {
			name: 'officeId',
			display: '����ID',
			sortable: true,
			hide: true
		}, {
			name: 'officeName',
			display: '����',
			sortable: true,
			width: 70
		}, {
			name: 'country',
			display: '����',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'province',
			display: 'ʡ��',
			sortable: true,
			width: 70
		}, {
			name: 'city',
			display: '����',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'attributeName',
			display: '��Ŀ����',
			width: 70,
			process: function(v, row) {
				switch (row.attribute) {
					case 'GCXMSS-01' :
						return "<span class='red'>" + v + "</span>";
						break;
					case 'GCXMSS-02' :
						return "<span class='blue'>" + v + "</span>";
						break;
					case 'GCXMSS-03' :
						return "<span class='green'>" + v + "</span>";
						break;
					default :
						return v;
				}
			}
		}, {
			name: 'status',
			display: '��Ŀ״̬',
			sortable: true,
			datacode: 'GCXMZT',
			width: 60
		}, {
			name: 'budgetField',
			display: '�ֳ�Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'budgetPerson',
			display: '����Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'budgetEqu',
			display: '�豸Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'budgetOutsourcing',
			display: '���Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'budgetOther',
			display: '����Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeAll',
			display: '�ܾ���(����ȷ��)',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			hide: true
		}, {
			name: 'feeFieldCount',
			display: '�ֳ�����(ʵʱ)',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feePerson',
			display: '��������',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeEqu',
			display: '�豸����',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeOutsourcing',
			display: '�������',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeOther',
			display: '��������',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'feeAllProcess',
			display: '���ý���(����ȷ��)',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			hide: true
		}, {
			name: 'feeFieldProcessCount',
			display: '�ֳ����ý���(ʵʱ)',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			width: 110
		}, {
			name: 'contractTypeName',
			display: 'Դ������',
			sortable: true,
			hide: true
		}, {
			name: 'contractId',
			display: '������ͬid',
			sortable: true,
			hide: true
		}, {
			name: 'contractCode',
			display: '������ͬ���(Դ�����)',
			sortable: true,
			width: 160,
			hide: true
		}, {
			name: 'contractTempCode',
			display: '��ʱ��ͬ���',
			sortable: true,
			width: 160,
			hide: true
		}, {
			name: 'rObjCode',
			display: 'ҵ����',
			sortable: true,
			width: 120,
			hide: true
		}, {
			name: 'contractMoney',
			display: '��ͬ���',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'customerId',
			display: '�ͻ�id',
			sortable: true,
			hide: true
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			sortable: true,
			hide: true
		}, {
			name: 'depName',
			display: '��������',
			sortable: true,
			hide: true
		}, {
			name: 'actBeginDate',
			display: 'ʵ�ʿ�ʼ',
			sortable: true,
			width: 80
		}, {
			name: 'actEndDate',
			display: 'ʵ�����',
			sortable: true,
			width: 80
		}, {
			name: 'managerName',
			display: '��Ŀ����',
			sortable: true
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true,
			width: 70
		}, {
			name: 'ExaDT',
			display: '��������',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'peopleNumber',
			display: '������',
			sortable: true,
			width: 70
		}, {
			name: 'natureName',
			display: '����1',
			sortable: true
		}, {
			name: 'nature2Name',
			display: '����2',
			sortable: true
		}, {
			name: 'outsourcingName',
			display: '�������',
			sortable: true,
			width: 80
		}, {
			name: 'cycleName',
			display: '��/����',
			sortable: true,
			width: 80
		}, {
			name: 'categoryName',
			display: '��Ŀ���',
			sortable: true,
			width: 80
		}, {
			name: 'platformName',
			display: '������ƽ̨',
			sortable: true,
			width: 80
		}, {
			name: 'netName',
			display: '����',
			sortable: true,
			width: 80
		}, {
			name: 'createTypeName',
			display: '������ʽ',
			sortable: true,
			width: 80
		}, {
			name: 'signTypeName',
			display: 'ǩ����ʽ',
			sortable: true,
			width: 80
		}, {
			name: 'toolType',
			display: '��������',
			sortable: true,
			width: 80
		}, {
			name: 'updateTime',
			display: '�������',
			sortable: true,
			width: 120
		}],
		lockCol: ['projectName', 'projectCode'],//����������
		searchitems: [{
			display: '���´�',
			name: 'officeName'
		}, {
			display: '��Ŀ���',
			name: 'projectCodeSearch'
		}, {
			display: '��Ŀ����',
			name: 'projectName'
		}, {
			display: '��Ŀ����',
			name: 'managerName'
		}, {
			display: 'ҵ����',
			name: 'rObjCodeSearch'
		}, {
			display: '������ͬ��',
			name: 'contractCodeSearch'
		}, {
			display: '��ʱ��ͬ��',
			name: 'contractTempCodeSearch'
		}],
		// ����״̬���ݹ���
		comboEx: [{
			text: "����״̬",
			key: 'ExaStatus',
			type: 'workFlow'
		}, {
			text: "��Ŀ״̬",
			key: 'status',
			datacode: 'GCXMZT'
		}],
		// Ĭ�������ֶ���
		sortname: "c.updateTime",
		// Ĭ������˳�� ����
		sortorder: "DESC"
	});
});