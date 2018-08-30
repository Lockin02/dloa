var show_page = function(page) {
	$("#purhasestorageGrid").yxsubgrid("reload");
};
$(function() {
	$("#purhasestorageGrid").yxsubgrid({
		model : 'stock_instock_stockin',
//		action : 'action',
		title : "�ݹ����",
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		param : {
			'docType' : $('#docType').val(),
			'docStatus' : 'YSH',
			'catchStatusNo' : 'CGFPZT-YGJ',
			'thisYear' : $("#thisYear").val(),
			'thisMonth' : $("#thisMonth").val(),
			'noPrice' : 1
		},
//		isShowNum : false,
//		usepager : false, // �Ƿ��ҳ

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
					sortable : true,
					width : 80
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
					datacode : 'RKDYDLX',
					width : 80,
					hide : true
				}, {
					name : 'relDocCode',
					display : 'Դ�����',
					sortable : true,
					hide : true
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
					sortable : true,
					hide : true
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
					display : '���ϲֿ�',
					sortable : true,
					width : 80
				}, {
					name : 'supplierId',
					display : '��Ӧ��id',
					sortable : true,
					hide : true
				}, {
					name : 'supplierName',
					display : '��Ӧ������',
					sortable : true,
					width : 150
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
					display : '�ɹ�Ա',
					sortable : true
				}, {
					name : 'docStatus',
					display : '����״̬',
					sortable : true,
					width : 80,
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
					datacode : 'CGFPZT',
					width : 80
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
					sortable : true,
					hide : true
				}, {
					name : 'auditerName',
					display : '�����',
					sortable : true
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=stock_instock_stockinitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '���ϱ��'
					}, {
						name : 'productName',
						width : 200,
						display : '��������'
					}, {
						name : 'productModel',
						width : 200,
						display : '����ͺ�'
					}, {
						name : 'actNum',
						display : "ʵ������"
					}, {
						name : 'price',
						display : "����",
						process : function(v){
							return moneyFormat2(v);
						}
					}, {
						name : 'subPrice',
						display : "���",
						process : function(v){
							return moneyFormat2(v);
						}
					}, {
						name : 'unHookNumber',
						display : 'δ��������',
						process : function(v){
							return moneyFormat2(v);
						}
					}, {
						name : 'unHookAmount',
						display : 'δ�������',
						process : function(v){
							return moneyFormat2(v);
						}
					}]
		},
		menusEx : [
			{
				name : 'edit',
				text : "�޸�",
				icon : 'edit',
				action : function(row, rows, grid) {
					showOpenWin("?model=stock_instock_stockin&action=toEditPrice&id="
							+ row.id
							+ "&docType="
							+ row.docType
							+ "&skey="
							+ row.skey_
							);
				}
			},
			{
				name : 'view',
				text : "�鿴",
				icon : 'view',
				action : function(row, rows, grid) {
					showThickboxWin("?model=stock_instock_stockin&action=toView&id="
							+ row.id
							+ "&docType="
							+ row.docType
							+ "&skey="
							+ row.skey_
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
		],
		searchitems : [ {
			display : '���ݱ��',
			name : 'docCode'
		}, {
			display : '���ϲֿ�����',
			name : 'inStockName'
		}],
		sortorder : "ASC"

	});
});