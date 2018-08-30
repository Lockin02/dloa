var show_page = function() {
	$("#proborrowAllGrid").yxsubgrid("reload");
};

$(function() {
	var buttonsArr = [{
		name: 'Add',
		text: "����",
		icon: 'add',
		action: function() {
			showOpenWin('?model=projectmanagent_borrow_borrow&action=toProAdd');
		}
	}];

	$("#proborrowAllGrid").yxsubgrid({
		model: 'projectmanagent_borrow_borrow',
		action: 'pageJsonStaff',
		param: {
			'limits': 'Ա��'
		},
		title: '���н�����(Ա��)',
		//��ť
		isViewAction: false,
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'Code',
			display: '���',
			sortable: true,
			width: 150
		}, {
			name: 'Type',
			display: '����',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'limits',
			display: '��Χ',
			sortable: true,
			width: 60
		}, {
			name: 'createName',
			display: '������',
			sortable: true
		}, {
			name: 'beginTime',
			display: '��ʼ����',
			sortable: true
		}, {
			name: 'closeTime',
			display: '��ֹ����',
			sortable: true
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true,
			width: 90,
			process: function (v,row) {
				if(row.lExaStatus != '���������'){
					return v;
				}else{
					return '���������';
				}
			}
		}, {
			name: 'ExaDT',
			display: '����ʱ��',
			sortable: true,
			hide: true,
			process: function (v,row){
				if(row['ExaStatus'] == "��������"){
					return '';
				}else{
					return v;
				}
			}
		}, {
			name: 'status',
			display: '����״̬',
			sortable: true,
			process: function(v) {
				if (v == '0') {
					return "����";
				} else if (v == '1') {
					return "���ֹ黹";
				} else if (v == '2') {
					return "�ر�";
				} else if (v == '3') {
					return "�˻�";
				} else if (v == '4') {
					return "����������"
				} else if (v == '5') {
					return "ת��ִ�в�"
				} else if (v == '6') {
					return "ת��ȷ����"
				}
			}
		}, {
			name: 'DeliveryStatus',
			display: '����״̬',
			sortable: true,
			process: function(v) {
				if (v == 'WFH') {
					return "δ����";
				} else if (v == 'YFH') {
					return "�ѷ���";
				} else if (v == 'BFFH') {
					return "���ַ���";
				} else if (v == 'TZFH') {
					return "ֹͣ����";
				}
			}
		}, {
			name: 'backStatus',
			display: '�黹״̬',
			sortable: true,
			process: function(v) {
				if (v == '0') {
					return "δ�黹";
				} else if (v == '1') {
					return "�ѹ黹";
				} else if (v == '2') {
					return "���ֹ黹";
				}
			}
		}, {
			name: 'reason',
			display: '��������',
			sortable: true,
			width: 200
		}, {
			name: 'timeType',
			display: '��������',
			sortable: true,
			width: 60
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			width: 200
		}],
		// ���ӱ������
		subGridOptions: {
			url: '?model=projectmanagent_borrow_borrowequ&action=listPageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param: [{
				paramId: 'borrowId',// ���ݸ���̨�Ĳ�������
				colId: 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel: [{
				name: 'productNo',
				width: 200,
				display: '��Ʒ���',
				process: function(v, row) {
					return v + "&nbsp;&nbsp;K3:" + row['productNoKS'];
				}
			}, {
				name: 'productName',
				width: 200,
				display: '��Ʒ����',
				process: function(v, row) {
					return v + "&nbsp;&nbsp;K3:" + row['productNameKS'];
				}
			}, {
				name: 'number',
				display: '��������',
				width: 80
			}, {
				name: 'executedNum',
				display: '��ִ������',
				width: 80
			}, {
				name: 'backNum',
				display: '�ѹ黹����',
				width: 80
			}]
		},
		comboEx: [{
			text: '����״̬',
			key: 'ExaStatus',
			data: [{
				text: 'δ����',
				value: 'δ����'
			}, {
				text: '����ȷ��',
				value: '����ȷ��'
			}, {
				text: '���������',
				value: '���������'
			}, {
				text: '��������',
				value: '��������'
			}, {
				text: '���',
				value: '���'
			},{
				text: '����',
				value: '����'
			}]
		}, {
			text: '����״̬',
			key: 'DeliveryStatus',
			data: [{
				text: 'δ����',
				value: 'WFH'
			}, {
				text: '�ѷ���',
				value: 'YFH'
			}, {
				text: '���ַ���',
				value: 'BFFH'
			}, {
				text: 'ֹͣ����',
				value: 'TZFH'
			}]
		}, {
			text: '����״̬',
			key: 'status',
			data: [{
				text: '����',
				value: '0'
			}, {
				text: '���ֹ黹',
				value: '1'
			}, {
				text: '�ر�',
				value: '2'
			}, {
				text: '�˻�',
				value: '3'
			}, {
				text: '����������',
				value: '4'
			}, {
				text: 'ת��ִ�в�',
				value: '5'
			}, {
				text: 'ת��ȷ����',
				value: '6'
			}]
		}],
		/**
		 * ��������
		 */
		searchitems: [{
			display: '���',
			name: 'Code'
		}, {
			display: '������',
			name: 'createName'
		}, {
			display: '��������',
			name: 'createTime'
		}, {
			display: 'K3��������',
			name: 'productNameKS'
		}, {
			display: 'ϵͳ��������',
			name: 'productName'
		}, {
			display: 'K3���ϱ���',
			name: 'productNoKS'
		}, {
			display: 'ϵͳ���ϱ���',
			name: 'productNo'
		},{
			display : '���к�',
			name : 'serialName2'
		}],
		buttonsEx: buttonsArr,
		// ��չ�Ҽ��˵�
		menusEx: [{
			text: '�鿴',
			icon: 'view',
			action: function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=proViewTab&id="
					+ row.id + "&skey=" + row['skey_']);
				}
			}
		}, {
			text: '�黹����',
			icon: 'add',
			showMenuFn: function(row) {
				return (row.ExaStatus == '���' || row.ExaStatus == '����') && row.backStatus != '1'
					&& $("#returnLimit").val() == "1";
			},
			action: function(row) {
				showOpenWin('?model=projectmanagent_borrowreturn_borrowreturn&action=toAdd&id=' + row.id);
			}
		}]
	});
});