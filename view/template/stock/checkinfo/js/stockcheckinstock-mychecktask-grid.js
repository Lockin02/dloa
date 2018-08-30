var show_page = function(page) {
	$(".mychecktaskGrid").yxgrid("reload");
};
$(function() {
	$(".mychecktaskGrid").yxgrid({
		model : 'stock_checkinfo_stockcheckinstock',
		action : 'mytaskPJ',
		title : '�ҵ��̵���������',
		isToolBar : false,
		showcheckbos : false,
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
			name : 'stockCode',
			display : '�ֿ���',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
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
			name : 'auditUserName',
			display : '�����',
			sortable : true
		}, {
			name : 'auditUserId',
			display : '�����id',
			sortable : true,
			hide : true
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
			display : 'taskId',
			name : 'taskId',
			sortable : true,
			hide : true
		}, {
			display : 'spid',
			name : 'spid',
			sortable : true,
			hide : true
		}],
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		isDelAction : false,
		toAddConfig : {
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 900,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 400
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
			name : 'audit',
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
					location = "controller/stock/checkinfo/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_stock_check_instock";
			}
		}],
		// ��չ��������ť
		buttonsEx : [{
			name : 'audit',
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = "controller/stock/checkinfo/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_stock_check_instock";
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
		}]
	});
});