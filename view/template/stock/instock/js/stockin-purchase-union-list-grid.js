var show_page = function(page) {
	$("#purhasestorageGrid").yxsubgrid("reload");
};
$(function() {
	$("#purhasestorageGrid").yxsubgrid({
		model : 'stock_instock_stockin',
		action : 'pageListGridJson',
		title : "�⹺��ⵥ����Դ������",
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

		menusEx : [{
			name : 'view',
			text : "�鿴",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_instock_stockin&action=toView&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		}, {
			name : 'business',
			text : "��������",
			icon : 'business',
			action : function(row, rows, grid) {
				showModalWin("?model=stock_instock_stockin&action=toUnionOrderForm&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_']);
			}
		}, {
			name : 'business',
			text : "�������ϵ�",
			icon : 'business',
			action : function(row, rows, grid) {
				showModalWin("?model=stock_instock_stockin&action=toUnionArrivalForm&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_']);
			}
		}],
		param : {
			'docType' : 'RKPURCHASE',
			'relDocId' : '0'
		},

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'isRed',
					display : '����ɫ',
					sortable : true,
					align : 'center',
					width : '35',
					process : function(v, row) {
						if (row.isRed == '0') {
							return "<img src='images/icon/hblue.gif' />";
						} else {
							return "<img src='images/icon/hred.gif' />";
						}
					}
				}, {
					name : 'docCode',
					display : '���ݱ��',
					sortable : true
				}, {
					name : 'auditDate',
					display : '��������',
					sortable : true
				}, {
					name : 'docType',
					display : '�������',
					sortable : true,
					hide : true
				}, {
					name : 'relDocId',
					display : 'Դ��id',
					sortable : true,
					hide : true
				}, {
					name : 'relDocType',
					display : 'Դ������',
					sortable : true,
					datacode : 'RKDYDLX'
				}, {
					name : 'relDocCode',
					display : 'Դ�����',
					sortable : true
				}, {
					name : 'relDocName',
					display : 'Դ������',
					sortable : true,
					hide : true

				}, {
					name : 'contractId',
					display : '��ͬid',
					sortable : true,
					hide : true
				}, {
					name : 'contractCode',
					display : '��ͬ���',
					sortable : true,
					hide : true
				}, {
					name : 'contractName',
					display : '��ͬ����',
					sortable : true,
					hide : true
				}, {
					name : 'purOrderCode',
					display : '�ɹ��������',
					sortable : true
				}, {
					name : 'inStockId',
					display : '���ϲֿ�id',
					sortable : true,
					hide : true
				}, {
					name : 'inStockCode',
					display : '���ϲֿ����',
					sortable : true,
					hide : true
				}, {
					name : 'inStockName',
					display : '���ϲֿ�����',
					sortable : true
				}, {
					name : 'supplierId',
					display : '��Ӧ��id',
					sortable : true,
					hide : true
				}, {
					name : 'supplierName',
					display : '��Ӧ������',
					sortable : true
				}, {
					name : 'clientName',
					display : '�ͻ�����',
					sortable : true,
					hide : true
				}, {
					name : 'purchMethod',
					display : '�ɹ���ʽ',
					sortable : true,
					datacode : 'cgfs',
					hide : true
				}, {
					name : 'payDate',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'accountingCode',
					display : '������Ŀ',
					sortable : true,
					datacode : 'KJKM',
					hide : true
				}, {
					name : 'purchaserName',
					display : '�ɹ�Ա����',
					sortable : true
				}, {
					name : 'docStatus',
					display : '����״̬',
					sortable : true,
					width : 50,
					process : function(v, row) {
						if (row.docStatus == 'WSH') {
							return "δ���";
						} else {
							return "�����";
						}
					}

				}, {
					name : 'catchStatus',
					display : '����״̬',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					hide : true
				}, {
					name : 'purchaserCode',
					display : '�ɹ�Ա���',
					sortable : true,
					hide : true
				}, {
					name : 'acceptorName',
					display : '����������',
					sortable : true,
					hide : true
				}, {
					name : 'acceptorCode',
					display : '�����˱��',
					sortable : true,
					hide : true
				}, {
					name : 'chargeName',
					display : '����������',
					sortable : true,
					hide : true
				}, {
					name : 'chargeCode',
					display : '�����˱��',
					sortable : true,
					hide : true
				}, {
					name : 'custosName',
					display : '����������',
					sortable : true,
					hide : true
				}, {
					name : 'custosCode',
					display : '�����˱��',
					sortable : true,
					hide : true
				}, {
					name : 'auditerName',
					display : '���������',
					sortable : true,
					hide : true
				}, {
					name : 'auditerCode',
					display : '����˱��',
					sortable : true,
					hide : true
				}, {
					name : 'accounterName',
					display : '������',
					sortable : true,
					hide : true
				}, {
					name : 'accounterCode',
					display : '�����˱��',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '�Ƶ�',
					sortable : true
				}, {
					name : 'auditerName',
					display : '�����',
					sortable : true
				}],
		// ���ӱ������
				//���ӱ��м��˸��ֶ�   ����ͺ�   2013.7.5
		subGridOptions : {
			url : '?model=stock_instock_stockinitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '���ϱ��',
						width : '80'
					}, {
						name : 'productName',
						width : 150,
						display : '��������'
					},{
						name : 'pattern',
						width : 200,
						display : '����ͺ�'
					}, {
						name : 'actNum',
						display : "ʵ������",
						width : '60'
					}, {
						name : 'serialnoName',
						display : "���к�",
						width : '450'
					}, {
						name : 'unHookNumber',
						display : 'δ��������',
						width : 80
						
					}, {
						name : 'unHookAmount',
						display : 'δ�������'
					}]
		},
		comboEx : [{
					text : '����״̬',
					key : 'docStatus',
					data : [{
								text : 'δ���',
								value : 'WSH'
							}, {
								text : '�����',
								value : 'YSH'
							}]
				}],
		searchitems : [{
					display : "���к�",
					name : 'serialnoName'
				}, {
					display : '���κ�',
					name : 'batchNum'
				}, {
					display : '���ݱ��',
					name : 'docCode'
				}, {
					display : '���ϲֿ�����',
					name : 'inStockName'
				}, {
					display : '���ϴ���',
					name : 'productCode'
				}, {
					display : '��������',
					name : 'productName'
				}, {
					display : '���Ϲ���ͺ�',
					name : 'pattern'
				}],
		sortorder : "DESC",
		buttonsEx : [{
			name : 'Add',
			text : "�߼�����",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=stock_instock_stockin&action=toAdvancedSearch&docType=RKPURCHASE"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600")
			}
		}],
		toAddConfig : {
			toAddFn : function(p) {
				action : showModalWin("?model=stock_instock_stockin&action=toAdd&docType=RKPURCHASE")
			},
			formWidth : 880,
			formHeight : 600
		}

	});
});
