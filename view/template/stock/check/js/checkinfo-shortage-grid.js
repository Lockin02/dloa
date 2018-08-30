var show_page = function(page) {
	$("#checkinfoGrid").yxgrid("reload");

};
$(function() {
	$("#checkinfoGrid").yxgrid({
		model : 'stock_check_checkinfo',
		title : '�̿����� ����',
		param : {
			checkType : "SHORTAGE"
		},
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true

				}, {
					name : 'docCode',
					display : '���ݱ��',
					sortable : true,
					width : 150

				}, {
					name : 'checkType',
					display : '�̵�����',
					sortable : true,
					process : function(val) {
						if (val = "SHORTAGE") {
							return "�̿�����";
						} else {
							return "��ӯ���"
						}
					}
				}, {
					name : 'stockId',
					display : '�ֿ�id',
					sortable : true,
					hide : true

				}, {
					name : 'stockName',
					display : '�ֿ�����',
					sortable : true,
					width : 200
				}, {
					name : 'auditStatus',
					display : '����״̬',
					sortable : true,
					process : function(v, row) {
						if (row.auditStatus == "WPD") {
							return "δ�̵�";
						} else {
							return "���̵�"
						}
					}
				}, {
					name : 'dealUserId',
					display : '������id',
					sortable : true,
					hide : true
				}, {
					name : 'dealUserName',
					display : '������',
					sortable : true,
					hide : true

				}, {
					name : 'auditUserId',
					display : '�����id',
					sortable : true,
					hide : true
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true,
					hide : true
				}, {
					name : 'ExaDT',
					display : '����ʱ��',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '�Ƶ�',
					sortable : true
				}, {
					name : 'createId',
					display : '������id',
					sortable : true,
					hide : true

				}, {
					name : 'createTime',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '¼����',
					sortable : true,
					hide : true
				}, {
					name : 'updateId',
					display : '�޸���id',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '�޸�����',
					sortable : true,
					hide : true
				}, {
					name : 'auditUserName',
					display : '�����',
					sortable : true
				}],
		// ��ť
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		showcheckbox : false,
		menuWidth : 130,
		// �Ҽ��˵���ť
		menusEx : [{
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=stock_check_checkinfo&action=init&id="
							+ row.id
							+ "&checkType="
							+ row['checkType']
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 500 + "&width=" + 1000);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'delete',
			text : 'ɾ��',
			icon : 'delete',
			// �÷����ж��Ҽ��˵��Ƿ�Ҫ���ָ���
			showMenuFn : function(row) {
				if (row.auditStatus == "WPD") {
					return true;
				} else {
					return false;
				}

			},
			action : function(row) {
				if (confirm('ȷ��ɾ����')) {
					$.ajax({
						type : "POST",
						url : "?model=stock_check_checkinfo&action=ajaxdeletes",
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
					})
				}
			}
		}, {
			name : 'edit',
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.auditStatus == "WPD") {
					return true;
				} else {
					return false;
				}

			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=stock_check_checkinfo&action=toEdit&id="
							+ row.id
							+ "&checkType="
							+ row['checkType']
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 500 + "&width=" + 1000);
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			name : 'view',
			text : "��ӡ",
			icon : 'print',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_check_checkinfo&action=toPrint&id="
						+ row.id
						+ "&checkType="
						+ row['checkType']
						+ "&skey="
						+ row['skey_']
						+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 500 + "&width=" + 1000);
			}
		}, {
			name : 'unlock',
			text : "�����",
			icon : 'unlock',
			showMenuFn : function(row) {
				if (row.auditStatus == "YPD") {
					return true;
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
								url : "?model=stock_check_checkinfo&action=cancelAudit",
								data : {
									id : row.id,
									checkType : row.checkType
								},
								success : function(result) {
									show_page();

									if (result == 1) {
										alert('���ݷ���˳ɹ���');
									} else {
										alert('���ݷ����ʧ��,��ȷ��!');
									}
								}
							});

						}
					} else {
						alert("�������ڲ��������Ѿ����ˣ����ܽ��з���ˣ�����ϵ������Ա���з����ˣ�");
					}
				} else {
					alert("�����ѹ��ˣ����ܽ��з���ˣ�����ϵ������Ա���з����ˣ�");
				}
			}
		}

		],
		// ��������
		searchitems : [{
					display : '���ݱ��',
					name : 'docCode'
				}, {
					display : '�ֿ�����',
					name : 'stockName'
				}],
		comboEx : [{
					text : '����״̬',
					key : 'auditStatus',
					data : [{
								text : 'δ�̵�',
								value : 'WPD'
							}, {
								text : '���̵�',
								value : 'YPD'
							}]
				}],
		sortorder : "DESC",
		toAddConfig : {
			toAddFn : function(p) {
				action : showModalWin("?model=stock_check_checkinfo&action=toAdd&checkType=SHORTAGE")
			}
		}

	})

})