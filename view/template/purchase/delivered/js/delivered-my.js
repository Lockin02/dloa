var show_page = function(page) {
	$("#myDeliveredGrid").yxsubgrid("reload");
};
$(function() {
	$("#myDeliveredGrid").yxsubgrid({
		model : 'purchase_delivered_delivered',
		title : '����֪ͨ��',
		action : 'myPageJson',
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
					name : 'returnCode',
					display : '���ϵ���',
					sortable : true,
					width : 120
				}, {
					name : 'state',
					display : '���ϵ�״̬',
					sortable : true,
					process : function(v, row) {
						if (row.state == '0') {
							return "δִ��";
						} else if (row.state == '2') {
							return "��ִ��";
						}
					}
				},{
    				name : 'ExaStatus',
  					display : '����״̬',
  					sortable : true
              }, {
					name : 'returnType',
					display : '��������',
					hide : true
				}, {
					name : 'sourceId',
					display : '����id',
					hide : true
				}, {
					name : 'sourceCode',
					display : '�������',
					sortable : true,
					width : 120
				}, {
					name : 'supplierName',
					display : '��Ӧ������',
					sortable : true,
					width : 180
				}, {
					name : 'supplierId',
					display : '��Ӧ��id',
					hide : true
				}, {
					name : 'purchManId',
					display : '�ɹ�ԱID',
					hide : true
				}, {
					name : 'purchManName',
					display : '�ɹ�Ա',
					sortable : true
				}, {
					name : 'stockId',
					display : '���ϲֿ�Id',
					hide : true
				}, {
					name : 'stockName',
					display : '���ϲֿ�����',
					sortable : true
				}, {
					name : 'returnDate',
					display : '��������',
					sortable : true
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=purchase_delivered_equipment&action=pageJson',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productNumb',
						display : '���ϱ��'
					}, {
						name : 'productName',
						width : 200,
						display : '��������'
					}, {
						name : 'batchNum',
						display : "���κ�"
					}, {
						name : 'deliveredNum',
						display : "��������"
					}, {
						name : 'factNum',
						display : "ʵ���������"
					}]
		},

		comboEx : [{
					text : '����֪ͨ��״̬',
					key : 'state',
					data : [{
								text : 'δִ��',
								value : '0'
							}, {
								text : '��ִ��',
								value : '2'
							}]
				}],
		toAddConfig : {
			/**
			 * ���������õĺ�̨����
			 */
			action : 'toAddInGrid',

			/**
			 * ������Ĭ�Ͽ��
			 */
			formWidth : 900,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 500
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=purchase_delivered_delivered&action=init&perm=view&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 500 + "&width=" + 900);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'edit',
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == 0&&row.ExaStatus == '���'||row.ExaStatus == 'δ�ύ') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=purchase_delivered_delivered&action=init&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 500 + "&width=" + 900);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'delete',
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == 0&&row.ExaStatus == '���'||row.ExaStatus == 'δ�ύ') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=purchase_delivered_delivered&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									// grid.reload();
									$("#myDeliveredGrid").yxsubgrid("reload");
									alert('ɾ���ɹ���');
								}
							}
						});
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		},
				{
			name : 'sumbit',
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == 'δ�ύ'&&row.state!=3 ||row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = 'controller/purchase/delivered/ewf_index_grid.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&examCode=oa_purchase_delivered&formName=�ɹ���������';
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			name : 'view',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���'|| row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purchase_delivered&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
				}
			}
		}],
		searchitems : [{
					display : '���ϵ���',
					name : 'returnCode'
				}, {
					display : '��Ӧ��',
					name : 'supplierName'
				}, {
					display : '��������',
					name : 'productName'
				}, {
					display : '���ϱ��',
					name : 'productNumb'
				}],
		// Ĭ������˳��
		sortorder : "DESC",
		sortname : "updateTime"
	});
});