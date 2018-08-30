var show_page = function(page) {
	$("#shipGrid").yxgrid("reload");
};
$(function() {
	$("#shipGrid").yxgrid({
		model : 'stock_outplan_ship',
		title : '������',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'shipGrid',

		// ��չ��ť
		buttonsEx : [{
			name : 'import',
			text : '����������',
			icon : 'add',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_outplan_ship&action=toAddWithoutPlan"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=950");
			}
		}],

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_outplan_ship&action=toView&id='
						+ row.id
						+ '&skey='
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=550&width=800');
			}
		}, {
			text : '�鿴�ʼ���Ϣ',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=mail_mailinfo&action=listByShip&docId='
						+ row.id);
			}
		}, {
			text : '��ӡ',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_outplan_ship&action=toPrint&id='
						+ row.id
						+ '&skey='
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
			}
		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.shipStatus == "2") {
					return false;
				} else if (row.isSign == "1") {
					return false;
				} else {
					return true;
				}

			},
			action : function(row) {
				showThickboxWin('?model=stock_outplan_ship&action=toEdit&id='
						+ row.id
						+ '&skey='
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			text : 'ǩ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.shipStatus == "2" && row.isSign != 1) {
					return true;
				} else if (row.shipStatus == "0" && row.isSign != 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=stock_outplan_ship&action=toSign&id='
						+ row.id
						+ '&skey='
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			text : '����',
			icon : 'edit',
			action : function(row) {
				window
						.open('?model=stock_outplan_ship&action=toExportExcel&id='
								+ row.id
								+ '&skey='
								+ row['skey_']
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}, {
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.shipStatus == "2") {
					return false;
				} else if (row.isSign == "1") {
					return false;
				} else {
					return true;
				}
			},
			action : function(row) {
				if (confirm('ȷ��Ҫɾ����������')) {
					$.ajax({
						type : 'POST',
						url : '?model=stock_outplan_ship&action=ajaxDelete&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						success : function(data) {
							if (data == 0) {
								alert("ɾ��ʧ��");
							} else {
								alert("ɾ���ɹ�");
								show_page();
							}
							return false;
						}
					});
				}
			}
		}],
		comboEx : [{
					text : '������ʽ',
					key : 'shipType',
					data : [{
								text : '����',
								value : 'order'
							}, {
								text : '����',
								value : 'borrow'
							}, {
								text : '����',
								value : 'lease'
							}, {
								text : '����',
								value : 'trial'
							}, {
								text : '����',
								value : 'change'
							}]
				}, {
					text : '����״̬',
					key : 'shipStatus',
					data : [{
								text : 'δ����',
								value : 1
							}, {
								text : '�ѷ���',
								value : 2
							}, {
								text : '- - - -',
								value : 0
							}]
				}],
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'shipCode',
					display : '��������',
					width : 120,
					sortable : true,
					hide : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					width : 180,
					sortable : true
				}, {
					name : 'planCode',
					display : '�����ƻ���',
					width : 90,
					sortable : true,
					hide : true
				}, {
					name : 'docType',
					display : 'Դ������',
					sortable : true,
					width : 60,
					process : function(v, row, g) {
						if (v == 'oa_contract_contract') {
							if( row.contractTypeName == '' ){
								return "��ͬ����";
							}else{
								return row.contractTypeName;
							}
						} else if (v == 'oa_borrow_borrow') {
							return "���÷���";
						} else if (v == 'oa_service_accessorder') {
							return "�������";
						} else if (v == 'oa_service_repair_apply') {
							return "ά�����뵥";
						}
					}
				}, {
					name : 'rObjCode',
					display : '����ҵ����',
					width : 120,
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : 'Դ�����',
					width : 180,
					sortable : true
				}, {
					name : 'customerContCode',
					display : '�ͻ���ͬ��',
					width : 120,
					sortable : true,
					hide : true
				}, {
					name : 'shipType',
					display : '������ʽ',
					sortable : true,
					width : 60,
					process : function(v) {
						if (v == 'order') {
							return "����";
						} else if (v == 'borrow') {
							return "����";
						} else if (v == 'lease') {
							return "����";
						} else if (v == 'trial') {
							return "����";
						} else if (v == 'change') {
							return "����";
						}
					}

				}, {
					name : 'shipStatus',
					display : '����״̬',
					process : function(v) {
						if (v == '2') {
							return "�ѷ���";
						} else if (v == '1') {
							return 'δ����';
						} else {
							return '- - - -';
						}
					},
					width : 60,
					sortable : true
				}, {
					name : 'shipDate',
					display : '��������',
					width : 75,
					sortable : true
				}, {
					name : 'isSign',
					display : '�Ƿ�ǩ��',
					process : function(v) {
						(v == '1') ? (v = '��') : (v = '��');
						return v;
					},
					width : 60,
					sortable : true
				}, {
					name : 'shipman',
					display : '������',
					width : 80,
					sortable : true
				}, {
					name : 'outstockman',
					display : '������',
					width : 80,
					sortable : true
				}, {
					name : 'auditman',
					display : '�����',
					width : 80,
					sortable : true
				}, {
					name : 'signDate',
					display : 'ǩ������',
					hide : true,
					sortable : true
				}],
		/**
		 * ��������
		 */
		searchitems : [{
					display : '��������',
					name : 'shipCode'
				}, {
					display : '�����ƻ���',
					name : 'planCode'
				}, {
					display : '����ҵ�񵥱��',
					name : 'rObjCode'
				}, {
					display : '������ͬ��',
					name : 'docCode'
				}, {
					display : '��ע',
					name : 'itemRemark'
				}]
	});
});