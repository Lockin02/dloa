var show_page = function(page) {
	$("#purhasestorageGrid").yxsubgrid("reload");
};
$(function() {
	$("#purhasestorageGrid").yxsubgrid({
		model : 'stock_instock_stockin',
		title : "������ⵥ�б�",
		action : 'pageListGridJson',
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		buttonsEx : [{
			name : 'Add',
			text : "�߼�����",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=stock_instock_stockin&action=toAdvancedSearch&docType=RKOTHER"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600")
			}
		}],
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
			name : 'edit',
			text : "�޸�",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.docStatus == "WSH") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showModalWin("?model=stock_instock_stockin&action=toEdit&id="
						+ row.id + "&docType=" + row.docType + "&skey="
						+ row['skey_']);
			}
		}, {
			name : 'addred',
			text : "���ƺ�ɫ��",
			icon : 'business',
			showMenuFn : function(row) {
				if (row.docStatus == "YSH" && row.isRed == "0") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showModalWin("?model=stock_instock_stockin&action=toAddRed&id="
						+ row.id + "&docType=" + row.docType + "&skey="
						+ row['skey_']);
			}
		}, {
			name : 'unlock',
			text : "�����",
			icon : 'unlock',
			showMenuFn : function(row) {
				if (row.docStatus == "YSH") {
					var cancelAudit = false;
					$.ajax({
						type : "POST",
						async : false,
						url : "?model=stock_instock_stockin&action=cancelAuditLimit",
						data : {
							docType : row.docType
						},
						success : function(result) {
							if (result == 1)
								cancelAudit = true;
						}
					})
					return cancelAudit;
				} else {
					return false;
				}

			},
			action : function(row, rows, grid) {
				var canAudit = true;// �Ƿ��ѽ���
				var closedAudit = true;// �Ƿ��ѹ���
				$.ajax({
					type : "POST",
					async : false,
					url : "?model=finance_period_period&action=isLaterPeriod",
					data : {
						thisDate : row.auditDate
					},
					success : function(result) {
						if (result == 0)
							canAudit = false;
					}
				})
				$.ajax({
					type : "POST",
					async : false,
					url : "?model=finance_period_period&action=isClosed",
					data : {},
					success : function(result) {
						if (result == 1)
							closedAudit = false;
					}
				})
				if (closedAudit) {
					if (canAudit) {
						if (window.confirm("ȷ�Ͻ��з������?")) {
							$.ajax({
								type : "POST",
								url : "?model=stock_instock_stockin&action=cancelAudit",
								data : {
									id : row.id,
									docType : row.docType
								},
								success : function(result) {
									show_page();
									if (result == 1) {
										alert('���ݷ���˳ɹ���');
									} else {
										alert(result);
									}
								}
							});
						}
					} else {
						alert("�������ڲ��������Ѿ����ˣ����ܽ��з���ˣ�����ϵ������Ա���з����ˣ�")
					}
				} else {
					alert("�����ѹ��ˣ����ܽ��з���ˣ�����ϵ������Ա���з����ˣ�")
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus == "WSH") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("ȷ��Ҫɾ��?")) {
					$.ajax({
						type : "POST",
						url : "?model=stock_instock_stockin&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('ɾ���ɹ���');
							} else {
								alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!');
							}
						}
					});
				}
			}
		}, {
			name : 'view',
			text : "��ӡ",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_instock_stockin&action=toPrint&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		}],
		param : {
			'docType' : 'RKOTHER',
			'exchangeId' : $("#exchangeId").val()
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
					sortable : true,
					width : 80
				}, {
					name : 'docType',
					display : '��ⵥ����',
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
					datacode : 'RKDYDLX3'
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
					name : 'inStockId',
					display : '���ϲֿ�id',
					sortable : true,
					hide : true
				}, {
					name : 'inStockCode',
					display : '���ϲֿ����',
					sortable : true,
					width : 80,
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
					sortable : true
				}, {
					name : 'clientName',
					display : '�ͻ�����',
					sortable : true,
					hide : true

				}, {
					name : 'payDate',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'docStatus',
					display : '����״̬',
					sortable : true,
					process : function(v, row) {
						if (row.docStatus == 'WSH') {
							return "δ���";
						} else {
							return "�����";
						}
					},
					width : 80
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
					name : 'purchaserName',
					display : '����',
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
					name : 'auditDate',
					display : '�������',
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
						width : 100,
						display : '���ϱ��'
					}, {
						name : 'productName',
						width : 230,
						display : '��������'
					},
					{
						name : 'pattern',
						width : 150,
						display : '����ͺ�'
					}, {
						name : 'actNum',
						display : "ʵ������",
						width : 80,
						process : function(v, row, prow) {
							if (prow['isRed'] == "1") {
								return -v;
							} else {
								return v;
							}
						}
					}, {
						name : 'serialnoName',
						display : "���к�",
						width : '400'
					}, {
						name : 'batchNum',
						display : "���κ�"
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
		}, {
			text : '������',
			key : 'isRed',
			data : [ {
				text : '����',
				value : '0'
			}, {
				text : '����',
				value : '1'
			} ]
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
		toAddConfig : {
			toAddFn : function(p) {
				action : showModalWin("?model=stock_instock_stockin&action=toAdd&docType=RKOTHER")
			}
		}

	});
});