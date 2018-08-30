var show_page = function(page) {
	$(".stockcheckinstockGrid").yxgrid("reload");
};
$(function() {
	$(".stockcheckinstockGrid").yxgrid({
		model : 'stock_checkinfo_stockcheckinstock',
		title : '�̵����',
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'stockId',
			display : '�ֿ�id',
			sortable : true,
			hide : true
		}, {
			name : 'stockName',
			display : '�ֿ�����',
			sortable : true
		}, {
			name : 'checkType',
			display : '�̵�����',
			sortable : true,
			datacode : 'PDLX'
		}, {
			name : 'dealUserId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'dealUserName',
			display : '������',
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : 'createId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '��������',
			width : '150',
			sortable : true
		}, {
			name : 'updateName',
			display : '�޸���',
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
		}, {
			name : 'auditUserId',
			display : '�����id',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}],
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		showcheckbox : false,
		toAddConfig : {
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 1000,
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
			formWidth : 900,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 400

		},
		toViewConfig : {
			action : 'toRead',

			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 900,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 400

		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			name : 'edit',
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '�����' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=stock_checkinfo_stockcheckinstock&action=toEdit&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=stock_checkinfo_stockcheckinstock&action=toRead&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 700 + "&width=" + 900);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'in',
			text : '�̵����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=stock_checkinfo_stockcheckinstock&action=intostock&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
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
					location = 'controller/stock/checkinfo/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&examCode=oa_stock_check_instock&formName=�̵����';
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_stock_check_instock&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=900");
				}
			}
		}]
	});
});