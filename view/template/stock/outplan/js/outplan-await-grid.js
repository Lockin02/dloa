var show_page = function(page) {
	$("#awaitGrid").yxsubgrid("reload");
};
$(function() {
	$("#awaitGrid").yxsubgrid({
		model : 'stock_outplan_outplan',
		action : 'awaitJson',
		param : {
			'docStatusArr' : ['WFH', 'BFFH'],
			'docTypeArr' : ['oa_contract_contract', 'oa_present_present','oa_contract_exchangeapply'],
			'issuedStatus' : '1',
			'isNeedConfirm' : '0'
		},
		title : '�����ƻ�',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'planCode',
					display : '�ƻ����',
					sortable : true
				}, {
					name : 'rObjCode',
					display : '����ҵ����',
					width : 120,
					sortable : true
				}, {
					name : 'docId',
					display : '��ͬId',
					hide : true,
					sortable : true
				}, {
					name : 'docCode',
					display : 'Դ����',
					width : 160,
					sortable : true
				}, {
					name : 'docName',
					display : 'Դ������',
					width : 160,
					sortable : true
				}, {
					name : 'week',
					display : '�ܴ�',
					width : 40,
					sortable : true
				}, {
					name : 'planIssuedDate',
					display : '�´�����',
					width : 80,
					sortable : true
				}, {
					name : 'docType',
					display : '��������',
					width : 60,
					sortable : true,
					process : function(v, row, g) {
						if (v == 'oa_contract_exchangeapply') {
							return "��������";
						} else if (v == 'oa_contract_contract') {
							if( row.contractTypeName == '' ){
								return "��ͬ����";
							}else{
								return row.contractTypeName;
							}
						} else if (v == 'oa_borrow_borrow') {
							return "���÷���";
						} else if (v == 'oa_present_present') {
							return "���ͷ���";
						}
					}
				}, {
					name : 'stockName',
					display : '�����ֿ�',
					sortable : true
				}, {
					name : 'type',
					display : '����',
					width : 40,
					datacode : 'FHXZ',
					sortable : true
				}, {
					name : 'purConcern',
					display : '�ɹ���Ա��ע�ص�',
					hide : true,
					sortable : true
				}, {
					name : 'shipConcern',
					display : '������Ա��ע',
					hide : true,
					sortable : true
				}, {
					name : 'issuedStatus',
					display : '�´�״̬',
					width : 60,
					process : function(v) {
						return v = '��';
					},
					sortable : true
				}, {
					name : 'docStatus',
					display : '״̬',
					width : 60,
					process : function(v) {
						if (v == 'YWC') {
							return "�����";
						} else if (v == 'WFH') {
							return "δ����";
						} else
							return "���ַ���";
					},
					sortable : true
				}, {
					name : 'shipPlanDate',
					display : '�ƻ���������',
					width : 80,
					sortable : true
				}, {
					name : 'isOnTime',
					display : '��ʱ����',
					width : 60,
					process : function(v) {
						(v == '1') ? (v = '��') : (v = '��');
						return v;
					},
					sortable : true
				}, {
					name : 'delayType',
					display : '����ԭ�����',
					hide : true,
					sortable : true
				}, {
					name : 'delayReason',
					display : 'δ������ԭ��',
					hide : true,
					sortable : true
				}],
				// ���ӱ������
				//���ӱ��м��˸��ֶ�   ����ͺ�   2013.7.5
		subGridOptions : {
			url : '?model=stock_outplan_outplan&action=byOutplanJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productNo',
						width : 100,
						display : '��Ʒ���'

					}, {
						name : 'productName',
						width : 200,
						display : '��Ʒ����',
						process : function(v, data, rowData,$row) {
							if (data.BToOTips == 1) {
								$row.attr("title", "������Ϊ������ת���۵�����");
								return "<img src='images/icon/icon147.gif' />"+v;
							}
							return v;
						}
					}, {
						name : 'productModel',
						display : "����ͺ�",
						width : 150
					}, {
						name : 'productType',
						display : '�������',
						width : 150
					},{
						name : 'number',
						display : '����',
						width : 50
					}, {
						name : 'unitName',
						display : '��λ',
						width : 50
					}, {
						name : 'executedNum',
						display : '�ѷ�������',
						width : 60
					}]
		},

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=toView&id='
						+ row.id
						+ '&docType='
						+ row.docType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}, {
			name : 'business',
			text : "���Ƴ��ⵥ",
			icon : 'business',
			showMenuFn : function(row) {
				// if (row.docStatus == "YSH" && row.isRed == "0") {
				return true;
				// }
				// return false;
			},//TODO
			action : function(row, rows, grid) {
//				alert(row.docType);
				if(row.docType != "oa_contract_contract"){
					showModalWin("?model=stock_outstock_stockout&action=toBluePush&relDocId="
							+ row.id + "&docType=CKOTHER&relDocType=QTCKFHJH")
				}else{
					showModalWin("?model=stock_outstock_stockout&action=toBluePush&relDocId="
							+ row.id + "&docType=CKSALES&relDocType=XSCKFHJH")
				}
			}
		}],
		comboEx : [{
					text : '��������',
					key : 'docType',
					data : [{
								text : '��������',
								value : 'oa_contract_exchangeapply'
							}, {
								text : '��ͬ����',
								value : 'oa_contract_contract'
							}, {
								text : '���÷���',
								value : 'oa_borrow_borrow'
							}, {
								text : '���ͷ���',
								value : 'oa_present_present'
							}]
				}],
		/**
		 * ��������
		 */
		searchitems : [{
					display : '�ƻ����',
					name : 'planCode'
				}, {
					display : '����ҵ����',
					name : 'rObjCode'
				}, {
					display : 'Դ����',
					name : 'docCode'
				}]
	});
});