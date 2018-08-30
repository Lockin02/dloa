var show_page = function(page) {
	$("#stockoutGrid").yxsubgrid("reload");
};
$(function() {

	var buttonArr = [{
		name : 'search',
		text : "�߼�����",
		icon : 'search',
		action : function(row) {
			showThickboxWin("?model=stock_outstock_stockout&action=toAdvancedSearch&docType=CKSALES"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600")
		}
	}];

	$("#stockoutGrid").yxsubgrid({
		model : 'stock_outstock_stockout',
		action : 'pageListGridJson',
		title : '���۳��ⵥ����',
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
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
					width : '35',
					align : 'center',
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
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'relDocType',
					display : 'Դ������',
					sortable : true,
					datacode : "XSCKYDLX"
				}, {
					name : 'relDocId',
					display : 'Դ��id',
					sortable : true,
					hide : true
				}, {
					name : 'relDocName',
					display : 'Դ������',
					sortable : true,
					hide : true
				}, {
					name : 'relDocCode',
					display : 'Դ�����',
					sortable : true
				}, {
					name : 'rObjCode',
					display : 'Դ��ҵ����',
					sortable : true,
					hide:true

				}, {
					name : 'contractId',
					display : '��ͬid',
					sortable : true,
					hide : true
				}, {
					name : 'contractName',
					display : '��ͬ����',
					sortable : true
				}, {
					name : 'contractCode',
					display : '��ͬ���',
					sortable : true
				}, {
					name : 'contractObjCode',
					display : '��ͬҵ����',
					sortable : true
				}, {
					name : 'stockId',
					display : '���ϲֿ�id',
					sortable : true,
					hide : true
				}, {
					name : 'stockCode',
					display : '���ϲֿ����',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '���ϲֿ�',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�(��λ)����',
					sortable : true
				}, {
					name : 'customerId',
					display : '�ͻ�(��λ)id',
					sortable : true,
					hide : true
				}, {
					name : 'saleAddress',
					display : '������ַ',
					sortable : true,
					hide : true
				}, {
					name : 'linkmanId',
					display : '������ϵ��id',
					sortable : true,
					hide : true
				}, {
					name : 'linkmanName',
					display : '������ϵ��',
					sortable : true,
					hide : true
				}, {
					name : 'linkmanTel',
					display : '������ϵ�˵绰',
					sortable : true,
					hide : true
				}, {
					name : 'pickingType',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'deptName',
					display : '���ϲ�������',
					sortable : true,
					hide : true
				}, {
					name : 'deptCode',
					display : '���ϲ��ű���',
					sortable : true,
					hide : true
				}, {
					name : 'toUse',
					display : '������;',
					sortable : true,
					hide : true
				}, {
					name : 'salesmanCode',
					display : '����Ա���',
					sortable : true,
					hide : true
				}, {
					name : 'salesmanName',
					display : '����Ա',
					sortable : true,
					hide : true
				}, {
					name : 'otherSubjects',
					display : '�Է���Ŀ',
					sortable : true,
					hide : true
				}, {
					name : 'docStatus',
					display : '����״̬',
					sortable : true,
					process : function(v, row) {
						if (v == "WSH") {
							return "δ���";
						} else {
							return "�����";
						}
					},
					width : 50
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
					name : 'pickName',
					display : '����������',
					sortable : true,
					hide : true
				}, {
					name : 'pickCode',
					display : '�����˱���',
					sortable : true,
					hide : true

				}, {
					name : 'auditerCode',
					display : '����˱��',
					sortable : true,
					hide : true

				}, {
					name : 'custosCode',
					display : '�����˱��',
					sortable : true,
					hide : true
				}, {
					name : 'custosName',
					display : '����������',
					sortable : true,
					hide : true
				}, {
					name : 'chargeCode',
					display : '�����˱��',
					sortable : true,
					hide : true
				}, {
					name : 'chargeName',
					display : '����������',
					sortable : true,
					hide : true
				}, {
					name : 'acceptorCode',
					display : '�����˱��',
					sortable : true,
					hide : true
				}, {
					name : 'acceptorName',
					display : '����������',
					sortable : true,
					hide : true
				}, {
					name : 'accounterCode',
					display : '�����˱��',
					sortable : true,
					hide : true
				}, {
					name : 'accounterName',
					display : '����������',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '����ʱ��',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '�Ƶ�',
					sortable : true
				}, {
					name : 'createId',
					display : '������',
					sortable : true,
					hide : true,
					hide : true
				}, {
					name : 'updateName',
					display : '�޸�������',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '�޸�ʱ��',
					sortable : true,
					hide : true
				}, {
					name : 'updateId',
					display : '�޸���',
					sortable : true,
					hide : true
				}, {
					name : 'auditerName',
					display : '�����',
					sortable : true
				}],
		// ���ӱ�������
		//���ӱ��м��˸��ֶ�   ����ͺ�   2013.7.5
		subGridOptions : {
			url : '?model=stock_outstock_stockoutitem&action=pageJson',
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
						width : 200,
						display : '��������'
					}, {
						name : 'pattern',
						width : 200,
						display : '����ͺ�'
					}, {
						name : 'actOutNum',
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
						width : '500'
					}, {
						name : 'batchNum',
						display : "���κ�"
					}]
		},
		param : {
			'docType' : 'CKSALES',
			'relDocId' : $("#id").val(),
			'relDocType' : 'XSCKTHTZ'
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
		buttonsEx : buttonArr,
		searchitems : [{
					display : "���к�",
					name : 'serialnoName'
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
				}, {
					display : '��ͬ���',
					name : 'contractCode'
				}],
		sortorder : "DESC",
		menusEx : [{
			name : 'view',
			text : "�鿴",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_outstock_stockout&action=toView&id="
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
				showModalWin("?model=stock_outstock_stockout&action=toEdit&id="
						+ row.id + "&docType=" + row.docType + "&skey="
						+ row['skey_'])
			}
		}, {
			name : 'addred',
			text : "���ƺ�ɫ��",
			icon : 'business',
			showMenuFn : function(row) {
				if (row.docStatus == "YSH" && row.isRed == "0") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showModalWin("?model=stock_outstock_stockout&action=toAddRed&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_'])
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
						url : "?model=stock_outstock_stockout&action=cancelAuditLimit",
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
								url : "?model=stock_outstock_stockout&action=cancelAudit",
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
						url : "?model=stock_outstock_stockout&action=ajaxdeletes",
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
			icon : 'print',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_outstock_stockout&action=toPrint&id="
						+ row.id
						+ "&docType="
						+ row.docType
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

			}
		}]
	});
});