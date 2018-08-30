var show_page = function(page) {
	$("#taskGrid").yxsubgrid("reload");
};
$(function() {
	$("#taskGrid").yxsubgrid({
		model : 'asset_purchase_task_task',
		title : '�ʲ��ɹ�����',
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
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
			width : 150
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
			sortable : true
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
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '���·���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == "δ����") {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=asset_purchase_task_task&action=init&id='
						+ row.id +

						'&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
			}
		}],
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
			display : '�ɹ�Ա',
			name : 'purcherName'
		}, {
			display : '�´�����',
			name : 'sendTime'
		}, {
			display : '��������',
			name : 'productName'
		}],
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