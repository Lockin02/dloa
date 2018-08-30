var show_page = function(page) {
	$("#fillupapproval").yxgrid("reload");
};
$(function() {
	$("#fillupapproval").yxgrid({
		model : 'stock_fillup_fillup',
		action : 'myApprovalPJ',
		title : '����ƻ����',
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		isDelAction : false,
		isToolBar : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'fillupCode',
					display : '����ƻ�����',
					sortable : true
				}, {
					name : 'stockId',
					display : '�ֿ�id',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '�ֿ�����',
					sortable : true,
					width:200
				}, {
					name : 'stockCode',
					display : '�ֿ����',
					sortable : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true
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
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '¼����',
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
				}],
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
		toViewConfig : {
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
			formWidth : 1000,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 500
		},
		menusEx : [{
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
				location = "controller/stock/fillup/ewf_index.php?actTo=ewfExam&taskId="
						+ row.taskId
						+ "&spid="
						+ row.spid
						+ "&billId="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&examCode=oa_stock_fillup";
			}
		}, {
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_fillup_fillup&action=init&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		}]
	});
});