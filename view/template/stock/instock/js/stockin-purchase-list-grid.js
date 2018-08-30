var show_page = function() {
	$("#purhasestorageGrid").yxsubgrid("reload");
};
$(function() {
	var buttonArr = [{
		name: 'Add',
		text: "�߼�����",
		icon: 'search',
		action: function() {
			showThickboxWin("?model=stock_instock_stockin&action=toAdvancedSearch&docType=RKPURCHASE"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=620")
		}
	}, {
		name: 'Add',
		text: "�ϲ�",
		icon: 'search',
		action: function(row, rows, idArr) {
			if (row) {
				if (idArr.length > 1) {
					alert('һ��ֻ�ܶ�һ����¼�����ϲ�');
					return false;
				}
				if (row.relDocType != "") {
					showModalWin("?model=common_search_searchSource&action=upList&objType=stockin&orgObj="
					+ row.relDocType + "&ids=" + row.relDocId);
				} else {
					alert('û��������ĵ���');
				}
			} else {
				alert('����ѡ���¼');
			}
		}
	}, {
		name: 'Add',
		text: "�²�",
		icon: 'search',
		action: function(row, rows, idArr) {
			if (row) {
				if (idArr.length > 1) {
					alert('һ��ֻ�ܶ�һ����¼�����²�');
					return false;
				}
				$.ajax({
					type: "POST",
					url: "?model=common_search_searchSource&action=checkDown",
					data: {
						"objId": row.id,
						'objType': 'stockin'
					},
					async: false,
					success: function(data) {
						if (data != "") {
							showModalWin("?model=common_search_searchSource&action=downList&objType=stockin&orgObj="
							+ data + "&objId=" + row.id);
						} else {
							alert('û��������ĵ���');
						}
					}
				});
			} else {
				alert('����ѡ���¼');
			}
		}
	}];

	$("#purhasestorageGrid").yxsubgrid({
		model: 'stock_instock_stockin',
		action: 'pageListGridJson',
		param: {
			docType: 'RKPURCHASE'
		},
		title: "�⹺��ⵥ�б�",
		isAddAction: true,
		isViewAction: false,
		isEditAction: false,
		isDelAction: false,
		showcheckbox: false,
		isOpButton: false,
		customCode: 'stock_instock_purchase_list',
		// ����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'isRed',
			display: '����ɫ',
			sortable: true,
			align: 'center',
			width: '35',
			process: function(v, row) {
				if (row.isRed == '0') {
					return "<img src='images/icon/hblue.gif'/>";
				} else {
					return "<img src='images/icon/hred.gif'/>";
				}
			}
		}, {
			name: 'docCode',
			display: '���ݱ��',
			sortable: true
		}, {
			name: 'auditDate',
			display: '��������',
			sortable: true
		}, {
			name: 'docType',
			display: '�������',
			sortable: true,
			hide: true
		}, {
			name: 'relDocId',
			display: 'Դ��id',
			sortable: true,
			hide: true
		}, {
			name: 'relDocType',
			display: 'Դ������',
			sortable: true,
			datacode: 'RKDYDLX'
		}, {
			name: 'relDocCode',
			display: 'Դ�����',
			sortable: true
		}, {
			name: 'relDocName',
			display: 'Դ������',
			sortable: true,
			hide: true

		}, {
			name: 'contractId',
			display: '��ͬid',
			sortable: true,
			hide: true
		}, {
			name: 'contractCode',
			display: '��ͬ���',
			sortable: true,
			hide: true
		}, {
			name: 'contractName',
			display: '��ͬ����',
			sortable: true,
			hide: true
		}, {
			name: 'purOrderCode',
			display: '�ɹ��������',
			sortable: true
		}, {
			name: 'inStockId',
			display: '���ϲֿ�id',
			sortable: true,
			hide: true
		}, {
			name: 'inStockCode',
			display: '���ϲֿ����',
			sortable: true,
			hide: true
		}, {
			name: 'inStockName',
			display: '���ϲֿ�����',
			sortable: true
		}, {
			name: 'supplierId',
			display: '��Ӧ��id',
			sortable: true,
			hide: true
		}, {
			name: 'supplierName',
			display: '��Ӧ������',
			sortable: true
		}, {
			name: 'clientName',
			display: '�ͻ�����',
			sortable: true,
			hide: true
		}, {
			name: 'purchMethod',
			display: '�ɹ���ʽ',
			sortable: true,
			datacode: 'cgfs',
			hide: true
		}, {
			name: 'payDate',
			display: '��������',
			sortable: true,
			hide: true
		}, {
			name: 'accountingCode',
			display: '������Ŀ',
			sortable: true,
			datacode: 'KJKM',
			hide: true
		}, {
			name: 'purchaserName',
			display: '�ɹ�Ա����',
			sortable: true
		}, {
			name: 'docStatus',
			display: '����״̬',
			sortable: true,
			width: 50,
			process: function(v, row) {
				if (row.docStatus == 'WSH') {
					return "δ���";
				} else {
					return "�����";
				}
			}
		}, {
			name: 'catchStatus',
			display: '����״̬',
			sortable: true,
			hide: true,
			datacode: 'CGFPZT'
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			hide: true
		}, {
			name: 'purchaserCode',
			display: '�ɹ�Ա���',
			sortable: true,
			hide: true
		}, {
			name: 'acceptorName',
			display: '����������',
			sortable: true,
			hide: true
		}, {
			name: 'acceptorCode',
			display: '�����˱��',
			sortable: true,
			hide: true
		}, {
			name: 'chargeName',
			display: '����������',
			sortable: true,
			hide: true
		}, {
			name: 'chargeCode',
			display: '�����˱��',
			sortable: true,
			hide: true
		}, {
			name: 'custosName',
			display: '����������',
			sortable: true,
			hide: true
		}, {
			name: 'custosCode',
			display: '�����˱��',
			sortable: true,
			hide: true
		}, {
			name: 'auditerName',
			display: '���������',
			sortable: true,
			hide: true
		}, {
			name: 'auditerCode',
			display: '����˱��',
			sortable: true,
			hide: true
		}, {
			name: 'accounterName',
			display: '������',
			sortable: true,
			hide: true
		}, {
			name: 'accounterCode',
			display: '�����˱��',
			sortable: true,
			hide: true
		}, {
			name: 'createName',
			display: '�Ƶ�',
			sortable: true
		}, {
			name: 'auditerName',
			display: '�����',
			sortable: true
		}],
		// ���ӱ������
		//���ӱ���˸��ֶ�   ����ͺ�   2013.7.5
		subGridOptions: {
			url: '?model=stock_instock_stockinitem&action=pageJson',
			param: [{
				paramId: 'mainId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				width: 70,
				display: '���ϱ���'
			}, {
				name: 'k3Code',
				width: 70,
				display: 'k3����'
			}, {
				name: 'productName',
				width: 150,
				display: '��������'
			}, {
				name: 'pattern',
				width: 150,
				display: '����ͺ�'
			}, {
				name: 'actNum',
				width: 50,
				display: "ʵ������",
				process: function(v, row, prow) {
					if (prow['isRed'] == "1") {
						return -v;
					} else {
						return v;
					}
				}
			}, {
				name: 'serialnoName',
				display: "���к�",
				width: 350
			}, {
				name: 'batchNum',
				display: "���κ�",
				width: 70
			}, {
				name: 'unHookNumber',
				display: 'δ��������',
				width: 70
			}, {
				name: 'unHookAmount',
				display: 'δ�������',
				process: function(v) {
					return moneyFormat2(v);
				},
				width: 70
			}]
		},
		buttonsEx: buttonArr,
		toAddConfig: {
			toAddFn: function(p) {
				action: showModalWin("?model=stock_instock_stockin&action=toAdd&docType=RKPURCHASE")
			},
			formWidth: 880,
			formHeight: 600
		},
		menusEx: [{
			name: 'view',
			text: "�鿴",
			icon: 'view',
			action: function(row, rows, grid) {
				showModalWin("?model=stock_instock_stockin&action=toView&id="
				+ row.id
				+ "&docType="
				+ row.docType
				+ "&skey="
				+ row['skey_'], 1, row.id);
			}
		}, {
			name: 'edit',
			text: "�޸�",
			icon: 'edit',
			showMenuFn: function(row) {
				return row.docStatus == "WSH";
			},
			action: function(row, rows, grid) {
				showModalWin("?model=stock_instock_stockin&action=toEdit&id="
				+ row.id
				+ "&docType="
				+ row.docType
				+ "&skey="
				+ row['skey_'], 1, row.id);
			}
		}, {
			name: 'addred',
			text: "���ƺ�ɫ��",
			icon: 'business',
			showMenuFn: function(row) {
				return row.docStatus == "YSH" && row.isRed == "0";
			},
			action: function(row) {
				showModalWin("?model=stock_instock_stockin&action=toAddRed&id="
				+ row.id
				+ "&docType="
				+ row.docType
				+ "&skey="
				+ row['skey_']);
			}
		}, {
			name: 'pushpurch',
			text: "���Ʋɹ���Ʊ",
			icon: 'business',
			action: function(row, rows, grid) {
				showModalWin("?model=finance_invpurchase_invpurchase&action=toAddForPushDown&id="
				+ row.id);
			}
		}, {
			name: 'unlock',
			text: "�����",
			icon: 'unlock',
			showMenuFn: function(row) {
				if (row.docStatus == "YSH") {
					var cancelAudit = false;
					$.ajax({
						type: "POST",
						async: false,
						url: "?model=stock_instock_stockin&action=cancelAuditLimit",
						data: {
							docType: row.docType
						},
						success: function(result) {
							if (result == 1)
								cancelAudit = true;
						}
					});
					return cancelAudit;
				} else {
					return false;
				}
			},
			action: function(row, rows, grid) {
				var canAudit = true;// �Ƿ��ѽ���
				var closedAudit = true;// �Ƿ��ѹ���
				$.ajax({
					type: "POST",
					async: false,
					url: "?model=finance_period_period&action=isLaterPeriod",
					data: {
						thisDate: row.auditDate
					},
					success: function(result) {
						if (result == 0)
							canAudit = false;
					}
				});
				$.ajax({
					type: "POST",
					async: false,
					url: "?model=finance_period_period&action=isClosed",
					data: {},
					success: function(result) {
						if (result == 1)
							closedAudit = false;
					}
				});
				if (row.catchStatus == "CGFPZT-WGJ") {
					if (closedAudit) {
						if (canAudit) {
							if (window.confirm("ȷ�Ͻ��з������?")) {
								$.ajax({
									type: "POST",
									url: "?model=stock_instock_stockin&action=cancelAudit",
									data: {
										id: row.id,
										docType: row.docType
									},
									success: function(result) {
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
				} else {
					alert('�����ѱ�����,����ϵ������Ա���з�����!')
				}
			}
		}, {
            name: 'view',
            text: "��ӡ",
            icon: 'print',
            action: function(row, rows, grid) {
                showThickboxWin("?model=stock_instock_stockin&action=toPrint&id="
                    + row.id
                    + "&docType="
                    + row.docType
                    + "&skey="
                    + row['skey_']
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
            }
        }, {
            name : 'view',
            text : "������־",
            icon : 'view',
            action : function(row) {
                showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
                    + row.id
                    + "&tableName=oa_stock_instock"
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
            }
        }, {
			text: 'ɾ��',
			icon: 'delete',
			showMenuFn: function(row) {
				return row.docStatus == "WSH";
			},
			action: function(row, rows, grid) {
				if (window.confirm("ȷ��Ҫɾ��?")) {
					$.ajax({
						type: "POST",
						url: "?model=stock_instock_stockin&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function(msg) {
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
		}],
		comboEx: [{
			text: '����״̬',
			key: 'docStatus',
			data: [{
				text: 'δ���',
				value: 'WSH'
			}, {
				text: '�����',
				value: 'YSH'
			}]
		}, {
			text: '������',
			key: 'isRed',
			data: [{
				text: '����',
				value: '0'
			}, {
				text: '����',
				value: '1'
			}]
		}],
		searchitems: [{
			display: "���к�",
			name: 'serialnoName'
		}, {
			display: '��Ӧ������',
			name: 'supplierName'
		}, {
			display: '���κ�',
			name: 'batchNum'
		}, {
			display: '���ݱ��',
			name: 'docCode'
		}, {
			display: '���ϲֿ�����',
			name: 'inStockName'
		}, {
			display: '���ϴ���',
			name: 'productCode'
		}, {
			display: '��������',
			name: 'productName'
		}, {
			display: '���Ϲ���ͺ�',
			name: 'pattern'
		}],
		sortorder: "DESC"
	});
});