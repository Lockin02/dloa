// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#mailapplyAuditNoGrid").yxgrid("reload");
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
	$("#mailapplyAuditNoGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailapply',
		action : 'myAuditPj',
		isToolBar:false,
		showcheckbox:false,
		title : "���������ʼ�����",
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

		menusEx : [ {
			name : 'read',
			text : "�鿴",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailapply&action=readInfo&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
			}
		},{
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
					location = "controller/mail/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_mail_apply";
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
		},  {
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