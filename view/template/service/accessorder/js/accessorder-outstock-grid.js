var show_page = function(page) {
	$("#accessorderGrid").yxsubgrid("reload");
};
$(function() {
	$("#accessorderGrid").yxsubgrid({
		model : 'service_accessorder_accessorder',
		title : '���������',
		isDelAction : false,
		isEditAction : false,
		isViewAction : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : '���ݱ��',
					sortable : true
				}, {
					name : 'docDate',
					display : 'ǩ������',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 200
				}, {
					name : 'contactUserName',
					display : '�ͻ���ϵ��',
					sortable : true
				}, {
					name : 'telephone',
					display : '��ϵ�绰',
					sortable : true
				}, {
					name : 'adress',
					display : '�ͻ���ַ',
					sortable : true,
					hide : true
				}, {
					name : 'chargeUserName',
					display : '����������',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '����ʱ��',
					sortable : true,
					hide : true
				}, {
					name : 'auditerUserName',
					display : '����������',
					sortable : true,
					hide : true
				}, {
					name : 'docStatus',
					display : '����״̬',
					sortable : true,
					process : function(v, row) {
						if (row.docStatus == 'WZX') {
							return "δִ��";
						} else if (row.docStatus == 'ZXZ') {
							return "ִ����";
						} else if (row.docStatus == 'YWC') {
							return "�����";
						}
					}
				}, {
					name : 'saleAmount',
					display : '�������',
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'areaLeaderName',
					display : '������������',
					sortable : true,
					hide : true
				}, {
					name : 'areaName',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '������',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '�޸���',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '�޸�����',
					sortable : true,
					hide : true
				}],

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row.ExaStatus == "���" || row.ExaStatus == "���") {
					if (row) {
						showModalWin("?model=service_accessorder_accessorder&action=viewTab&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				} else {
					if (row) {
						showThickboxWin("?model=service_accessorder_accessorder&action=toView&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				}
			}
		},{
			name : 'business',
			text : "���Ƴ��ⵥ",
			icon : 'business',
			showMenuFn : function(row) {
				// if (row.docStatus == "YSH" && row.isRed == "0") {
				return true;
				// }
				// return false;
			},
			action : function(row, rows, grid) {
				showModalWin("?model=stock_outstock_stockout&action=toBluePush&relDocId="
						+ row.id + "&docType=CKSALES&relDocType=XSCKFHJH")
			}
		}],

		// ���ӱ������
		subGridOptions : {
			url : '?model=service_accessorder_accessorderitem&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '���ϱ��'
					}, {
						name : 'productName',
						display : '��������',
						width : 250
					}, {
						name : 'pattern',
						display : '����ͺ�'
					}, {
						name : 'price',
						display : '����',
						process : function(v, row) {
							return moneyFormat2(v);
						}
					}, {
						name : 'proNum',
						display : '��������',
						width : '80'
					}, {
						name : 'actOutNum',
						display : '�ѳ�������',
						width : '80'
					}, {
						name : 'subCost',
						display : '���',
						process : function(v, row) {
							return moneyFormat2(v);
						}
					}]
		},
		toAddConfig : {
			formWidth : '1100px',
			formHeight : 600
		},
		toEditConfig : {
			action : 'toEdit',
			formWidth : '1100px',
			formHeight : 600
		},
		searchitems : [{
					display : '���ϱ��',
					name : 'productCode'
				}, {
					name : 'productName',
					display : '��������'
				}, {
					display : '���ݱ��',
					name : 'docCode'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}, {
					display : '�ͻ���ϵ��',
					name : 'contactUserName'
				}],
		comboEx : [{
					text : '����״̬',
					key : 'docStatus',
					data : [{
								text : 'δִ��',
								value : 'WZX'
							}, {
								text : 'ִ����',
								value : 'ZXZ'
							}, {
								text : '�����',
								value : 'YWC'
							}]
				}, {
					text : '����״̬',
					key : 'ExaStatus',
					data : [{
								text : '���ύ',
								value : '���ύ'
							}, {
								text : '��������',
								value : '��������'
							}, {
								text : '���',
								value : '���'
							}, {
								text : '���',
								value : '���'
							}]
				}]
	});
});