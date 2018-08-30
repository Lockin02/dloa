var show_page = function(page) {
	$("#fillupGrid").yxsubgrid("reload");
};
$(function() {
	$("#fillupGrid").yxsubgrid({
		model : 'stock_fillup_fillup',
		title : '����ƻ�',
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'fillupCode',
					display : '���ݱ��',
					sortable : true
				}, {
					name : 'stockId',
					display : '�ֿ�id',
					sortable : true,
					hide : true
				}, {
					name : 'stockCode',
					display : '�ֿ����',
					sortable : true
				}, {
					name : 'stockName',
					display : '�ֿ�����',
					sortable : true,
					width : 200

				}, {
					name : 'auditStatus',
					display : '�ύ״̬',
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
					name : 'updateId',
					display : '�޸���id',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '�޸���',
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
					display : '������id',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '�޸�����',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '��ע',
					width:'350',
					sortable : true
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=stock_fillup_filluppro&action=pageJson',
			param : [{
						paramId : 'fillUpId',
						colId : 'id'
					}],
			colModel : [{
						name : 'sequence',
						display : '���ϱ��'
					}, {
						name : 'productName',
						width : 200,
						display : '��������'
					}, {
						name : 'stockNum',
						display : "��������"
					}, {
						name : 'amountAllOld',
						display : "��������"
					},{
						name : 'issuedPurNum',
						display : "���´�ɹ�����"
					}]
		},
		toAddConfig : {
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 820,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 500
		},
		toViewConfig : {
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 820,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 500
		},
		toEditConfig : {
			action : 'toEdit',
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 820,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 500
		},
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		showcheckbox : false,
		menuWidth : 130,
		menusEx : [{
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=stock_fillup_fillup&action=init&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=780");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'delete',
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '�����' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȷ��ɾ����')) {
					$.ajax({
								type : "POST",
								url : "?model=stock_fillup_fillup&action=ajaxdeletes",
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
				if (row.ExaStatus == '�����' || row.ExaStatus == '���') {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=stock_fillup_fillup&action=toEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&&width=820");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'sumbit',
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '�����' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.get('index1.php', {
								model : 'stock_fillup_fillup',
								action : 'changeAuditStatus',
								id : row.id
							}, function(data) {
								if (data == 0) {
									showThickboxWin("controller/stock/fillup/ewf_index.php?actTo=ewfSelect&billId="
											+ row.id
											+ "&examCode=oa_stock_fillup&formName=��������"
											+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=780");
									// location =
									// 'controller/stock/fillup/ewf_index.php?actTo=ewfSelect&billId='
									// + row.id
									// +
									// '&examCode=oa_stock_fillup&formName=��������';
								} else {
									alert("��������");
								}
							})

				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'aduit',
			text : '�����Ĳɹ�����',
			icon : 'view',
			showMenuFn : function(row) {
				if ( row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
				showThickboxWin("?model=purchase_plan_basic&action=toSourceList&purchType=stock&sourceId="
						+ row.id
						+ "&sourceCode="
						+ row.fillupCode
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=950");
				}
			}
		}, {
			name : 'audit',
			text : '�´�ɹ�����', // add by can
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					var flag = false;
					$.ajax({ // �ж��Ƿ������ʾ���´�ɹ��ƻ��Ҽ���
						type : "post",
						async : false,
						url : "?model=stock_fillup_fillup&action=isAddPlan",
						data : "id=" + row.id,
						success : function(data) {
							if (data == 1) {
								return true;
							} else {
								return false;
							}
						}
					});
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=purchase_external_external&action=toAdd&purchType=stock&sourceId="
						+ row.id
						+ "&sourceCode="
						+ row.fillupCode
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=950");
			}
		}, {
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���'
						|| row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_stock_fillup&pid="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=900");
				}
			}
		}],
		// ��������
		searchitems : [{
					display : '���ݱ��',
					name : 'fillupCodeX'
				}, {
					display : '�ֿ�����',
					name : 'stockName'
				}],
		comboEx : [{
					text : '����״̬',
					key : 'ExaStatus',
					data : [{
								text : '��������',
								value : '��������'
							}, {
								text : '�����',
								value : '�����'
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