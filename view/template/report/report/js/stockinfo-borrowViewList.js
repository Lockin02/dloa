var show_page = function(page) {
	$("#borrowGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = []
	$("#borrowGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		param : {
			"ids" : $("#bids").val()
		},
		title : '������',
		// ��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isToolBar : false, // �Ƿ���ʾ������
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'initTip',
			name : 'initTip',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceId',
			display : '�̻�Id',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '���',
			sortable : true
		}, {
			name : 'Type',
			display : '����',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'salesName',
			display : '���۸�����',
			sortable : true
		}, {
			name : 'beginTime',
			display : '��ʼ����',
			sortable : true
		}, {
			name : 'closeTime',
			display : '��ֹ����',
			sortable : true
		}, {
			name : 'scienceName',
			display : '����������',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 90
		}, {
			name : 'DeliveryStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
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
			name : 'backStatus',
			display : '�黹״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ�黹";
				} else if (v == '1') {
					return "�ѹ黹";
				} else if (v == '2') {
					return "���ֹ黹";
				}
			}
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true
		}, {
			name : 'objCode',
			display : 'ҵ����',
			width : 120
		}],

		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrowequ&action=listPageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'borrowId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
				name : 'productNo',
				width : 100,
				display : '��Ʒ���',
				process : function(v, row) {
					return v + "&nbsp;&nbsp;K3:" + row['productNoKS'];
				}
			}, {
				name : 'productName',
				width : 200,
				display : '��Ʒ����',
				process : function(v, row) {
					return v + "&nbsp;&nbsp;K3:" + row['productNameKS'];
				}
			}, {
				name : 'productModel',
				width : 200,
				display : '���ϰ汾/�ͺ�'
			}, {
				name : 'number',
				display : '��������',
				width : 80
			}, {
				name : 'executedNum',
				display : '��ִ������',
				width : 80
			}, {
				name : 'backNum',
				display : '�ѹ黹����',
				width : 80
			}]
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���',
			name : 'Code'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : 'ҵ����',
			name : 'objCode'
		}, {
			display : '���۸�����',
			name : 'salesName'
		}, {
			display : '������',
			name : 'createNmae'
		}, {
			display : '��������',
			name : 'createTime'
		}, {
			display : 'K3��������',
			name : 'productNameKS'
		}, {
			display : 'ϵͳ��������',
			name : 'productName'
		}, {
			display : 'K3���ϱ���',
			name : 'productNoKS'
		}, {
			display : 'ϵͳ���ϱ���',
			name : 'productNo'
		}],
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id="
							+ row.id + "&skey=" + row['skey_']);

				}
			}

		}
       ],
		buttonsEx : buttonsArr

	});

});