var show_page = function(page) {
	$("#taskGrid").yxsubgrid("reload");
};
$(function() {
	$("#taskGrid").yxsubgrid({
		model : 'asset_purchase_task_task',
		title : '�ҵ��ʲ��ɹ�����',
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		param : {
			"purcherId" : $("#purcherId").val()
		},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			width : 120
		}, {
			name : 'state',
			display : '����״̬',
			sortable : true,
			width : 90
		}, {
			name : 'sendTime',
			display : '�´�����',
			sortable : true
		}, {
			name : 'sendName',
			display : '�����´���',
			sortable : true
		}, {
			name : 'purcherName',
			display : '�ɹ�Ա',
			sortable : true,
			hide : true
		}, {
			name : 'acceptDate',
			display : '��������',
			sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_purchase_task_taskItem&action=pageJson',
			param : [{
				paramId : 'parentId',
				colId : 'id'
			}],
			colModel : [{
				display : '��������',
				name : 'productName',
				tclass : 'txtshort'
			}, {
				display : '���',
				name : 'pattem',
				tclass : 'txtshort'
			}, {
				display : '�ɹ�����',
				name : 'purchAmount',
				tclass : 'txtshort',
				width : 50
			}, {
				display : '��������',
				name : 'taskAmount',
				tclass : 'txtshort',
				width : 50
			},{
				display : '�´ﶩ������',
				name : 'issuedAmount',
				tclass : 'txtshort'
			},  {
				display : '����',
				name : 'price',
				tclass : 'txtshort',
				process : function(v, row) {
					return moneyFormat2(v);
				},
				width : 70
			}, {
				display : '���',
				name : 'moneyAll',
				tclass : 'txtshort',
				process : function(v, row) {
					return moneyFormat2(v);
				},
				width : 70
			}, {
				display : 'ϣ����������',
				name : 'dateHope',
				type : 'date',
				tclass : 'txtshort'
			}]
		},
		comboEx : [{
			text : '����״̬',
			key : 'state',
			data : [{
				text : 'δ����',
				value : 'δ����'
			}, {
				text : '�ѽ���',
				value : '�ѽ���'
			}
//			, {
//				text : '���',
//				value : '���'
//			}
			]
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���ݱ��',
			name : 'formCode'
		}, {
			display : '�����´���',
			name : 'sendName'
		}, {
			display : '�´�����',
			name : 'sendTime'
		}, {
			display : '��������',
			name : 'productName'
		}],
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == "δ����") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��������"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_purchase_task_task&action=submit&id="
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('���ճɹ���');
								$("#taskGrid").yxsubgrid("reload");// ���¼���
							} else {
								alert('����ʧ�ܣ�');
							}
						}
					});
				}
			}
		}
//		,{
//			text : '�´�ɹ�����',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.state == "�ѽ���") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				location = "?model=purchase_contract_purchasecontract&action=toAddOrderByAsset&applyId="+ row.id + "&orderType=asset&skey="+row['skey_'];
//			}
//		}
		],
		toViewConfig : {
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 900,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 600
		}
	});
});
