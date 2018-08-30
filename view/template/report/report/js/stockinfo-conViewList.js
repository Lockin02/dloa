var show_page = function(page) {
	$("#contractGrid").yxsubgrid("reload");
};
$(function() {
	$("#contractGrid").yxsubgrid({
		model : 'contract_contract_contract',
		title : '��ͬ����',
		param : {'ids' : $("#cids").val()},
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		// showcheckbox : false,
		isAddAction : false,
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			showMenuFn : function(row) {
				if (row) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toViewTab&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}],
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'contractType',
					display : "��ͬ����",
					sortable : true,
					datacode : 'HTLX',
					width : 60
				}, {
					name : 'contractCode',
					display : '��ͬ���',
					sortable : true,
					width : 180,
					process : function(v, row) {
						return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
								+ row.id
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
					}
				}, {
					name : 'contractProvince',
					display : 'ʡ��',
					sortable : true,
					width : 60
				}, {
					name : 'contractCity',
					display : '����',
					sortable : true,
					width : 60
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 100
				}, {
					name : 'customerType',
					display : '�ͻ�����',
					sortable : true,
					datacode : 'KHLX',
					width : 70
				}, {
					name : 'contractName',
					display : '��ͬ����',
					sortable : true,
					width : 150
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true,
					width : 60
				}, {
					name : 'winRate',
					display : '��ͬӮ��',
					sortable : true,
					width : 70
				}, {
					name : 'areaName',
					display : '��������',
					sortable : true,
					width : 60
				}, {
					name : 'areaPrincipal',
					display : '��������',
					sortable : true
				}, {
					name : 'prinvipalName',
					display : '��ͬ������',
					sortable : true,
					width : 80
				}, {
					name : 'state',
					display : '��ͬ״̬',
					sortable : true,
					process : function(v) {
						if (v == '0') {
							return "δ�ύ";
						} else if (v == '1') {
							return "������";
						} else if (v == '2') {
							return "ִ����";
						} else if (v == '3') {
							return "�ѹر�";
						} else if (v == '4') {
							return "�����";
						} else if (v == '5') {
							return "�Ѻϲ�";
						} else if (v == '6') {
							return "�Ѳ��";
						} else if (v == '7') {
							return "�쳣�ر�";
						}
					},
					width : 60
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=contract_contract_equ&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'contractId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
						name : 'productCode',
						width : 200,
						display : '��Ʒ���'
					},{
						name : 'productName',
						width : 200,
						display : '��Ʒ����'
					}, {
					    name : 'number',
					    display : '��������',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '��ִ������',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '��ִ������',
						width : 80
					}]
		},
		comboEx : [{
					text : '����',
					key : 'contractType',
					data : [{
								text : '���ۺ�ͬ',
								value : 'HTLX-XSHT'
							}, {
								text : '�����ͬ',
								value : 'HTLX-FWHT'
							}, {
								text : '���޺�ͬ',
								value : 'HTLX-ZLHT'
							}, {
								text : '�з���ͬ',
								value : 'HTLX-YFHT'
							}]
				}],

		/**
		 * ��������
		 */
		searchitems : [{
					display : '��ͬ���',
					name : 'contractCode'
				}, {
					display : '��ͬ����',
					name : 'contractName'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}, {
					display : 'ҵ����',
					name : 'objCode'
				}, {
					display : '��Ʒ����',
					name : 'conProductName'
				},{
				    display : '������Ŀ',
				    name : 'trialprojectCode'
				}],
		sortname : "createTime"

	});

});
