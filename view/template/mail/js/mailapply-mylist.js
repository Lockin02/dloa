// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#myMailapplyGrid").yxgrid("reload");
};
var invoiceapplyFn = function(rowId) {
		showThickboxWin('?model=finance_invoiceapply_invoiceapply&action=init&id='
				+ rowId
				+ '&perm=view'
				+ '&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
	};

	var outapplyFn = function(rowId, docType) {
		showThickboxWin('?model=stock_outstock_outapply&action=toView&id='
				+ rowId
				+ '&docType='
				+ docType
				+ '&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
	};
$(function() {
	$("#myMailapplyGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailapply',
		action : 'myApplyListJson',
		showcheckbox : false,
		isDelAction : false,
		title : "�ҵ��ʼ�����",
		// /**
		// * Ĭ�ϸ߶�
		// */
		// height : 280,
		// /**
		// * �Ƿ���ʾ������
		// *
		// * @type Boolean
		// */
		// isToolBar : false,
		/**
		 * ��Ĭ�Ͽ��
		 */
		formWidth : 900,
		/**
		 * ��Ĭ�Ͽ��
		 */
		formHeight : 550,

		/**
		 * �Ƿ���ʾ��Ӱ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isAddAction : true,
		/**
		 * �Ƿ���ʾ�鿴��ť/�˵�
		 *
		 * @type Boolean
		 */
		isViewAction : false,
		/**
		 * �Ƿ���ʾ�޸İ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isEditAction : false,

		// ��������
		comboEx : [{
			text : '״̬',
			key : 'status',
			data : [{
				text : 'δ����',
				value : '1'
			}, {
				text : '�Ѵ���',
				value : '2'
			}]
		}],

		menusEx : [{
			name : 'edit',
			text : "�޸�",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' && row.applyId == "") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailapply&action=init&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		}, {
			name : 'read',
			text : "�鿴",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailapply&action=readInfo&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
			}
		}, {
			name : 'delete',
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' && row.applyId == "") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=mail_mailapply&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									// grid.reload();
									alert('ɾ���ɹ���');
									$("#myMailapplyGrid").yxgrid("reload");
								}
							}
						});
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'sumbit',
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ'&& row.applyId == "" || row.ExaStatus == '���'&& row.applyId == ""
						) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = 'controller/mail/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&examCode=oa_mail_apply&formName=�ʼ���������';
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���'
						&& row.applyId == "") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_mail_apply&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
				}
			}
		}, {
			name : 'readMailMessage',
			text : "�ʼļ�¼",
			icon : 'view',
			 showMenuFn : function(row) {
			 if (row.status == '2') {
			 return true;
			 }
			 return false;
			 },
			action : function(row, rows, grid) {
				location = "?model=mail_mailinfo&action=toMailInfo&mailApplyId="
						+ row.id;
			}
		}],

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�������',
			name : 'applyNo',
			sortable : true,
			process : function(v, row) {
				if (row.applyNo == "") {
					return row.applyNo = "�޹������";
				}
				if (row.applyType == "invoiceapply") {

					return "<a href=\"javascript:invoiceapplyFn(" + row.applyId
							+ ")\">" + v + "</a>";
				}
				if (row.applyType == "outapply") {

					return "<a href=\"javascript:outapplyFn(" + row.applyId
							+ "," + "'" + row.docType + "'" + ")\">" + v
							+ "</a>";
				} else {
					return row.applyNo;
				}
			}

		}, {
			display : '�ʼı��',
			name : 'zipCode',
			sortable : true,
			align : 'center'
		}, {
			display : '֪ͨ����',
			name : 'mailDate',
			sortable : true,
			width : '130',
			align : 'center'
		}, {
			display : '�ռ���λȫ��',
			name : 'customerName',
			sortable : true,
			width : '130',
			align : 'center'
		}, {
			display : '�ռ���',
			name : 'linkman',
			sortable : true,
			width : '80',
			align : 'center'
		}, {
			display : '�ռ��˵绰',
			name : 'tel',
			sortable : true,
			width : '100',
			align : 'center'
		}, {
			display : '�ʼķ�ʽ',
			name : 'mailType',
			sortable : true,
			datacode : 'YJFS',
			width : '60',
			align : 'center'
		}, {
			display : '�ʼ�״̬',
			name : 'status',
			sortable : true,
			process : function(v) {
				if (v == "1") {
					return "δ����";
				} else {
					return "�Ѵ���";
				}
			},
			width : '60',
			align : 'center'
		}, {
			display : '����״̬',
			name : 'ExaStatus',
			sortable : true,
			width : '60',
			align : 'center'
		}],
		// ��������
		searchitems : [{
			display : '�ʼķ�ʽ',
			name : 'mailType'
		}],
		// Ĭ������˳��
		sortorder : "desc"

	});
});