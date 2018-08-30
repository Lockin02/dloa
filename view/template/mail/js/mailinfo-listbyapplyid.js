// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".mailinfoGrid").yxgrid("reload");
};
$(function() {
	$(".mailinfoGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailinfo',
		action : 'mylogpageJson&mailApplyId=' + $('#mailApplyId').val(),
		title : "�ʼ���Ϣ",

		/**
		 * �Ƿ���ʾ�����鿴��ť/�˵�
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

		//��չ��ť
		buttonsEx : [{
			name : 'return',
			text : '����',
			icon : 'back',
			action : function(row, rows, grid) {
				location = "?model=mail_mailapply";

			}
		}],

		menusEx : [{
			name : 'edit',
			text : "�޸�",
			icon : 'edit',
			showMenuFn : function(row) {
				 if (row.mailStatus == '1') {
				 return true;
				 }
				 return false;
			 },
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailinfo&action=init&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		}, {
			name : 'readMailMessage',
			text : "�ͻ�ǩ��",
			icon : 'view',
			showMenuFn : function(row) {
				 if (row.mailStatus == '1') {
				 return true;
				 }
				 return false;
			 },
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailsign&action=toAdd&mailInfoId="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
			}
		}],

		toAddConfig : {
			text : '����',
			/**
			 * Ĭ�ϵ��������ť�����¼�
			 */
			toAddFn : function(p) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model=mail_mailinfo&action=toAdd&mailApplyId="
						+ $('#mailApplyId').val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
			}
		},

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�ʼĵ���',
			name : 'mailNo',
			sortable : true
		}, {
			display : '֪ͨ����',
			name : 'mailTime',
			sortable : true,
			width : '130',
			align : 'center'
		}, {
			display : '�ռ���',
			name : 'receiver',
			sortable : true,
			width : '80'
		}, {
			display : '�ļ���',
			name : 'mailMan',
			sortable : true,
			width : '80'
		}, {
			display : '�ļ���ַ',
			name : 'address',
			hide : true,
			width : '200'
		}, {
			display : '�ռ��˵绰',
			name : 'tel',
			sortable : true,
			width : '100'
		}, {
			display : '�ʼķ�ʽ',
			name : 'mailType',
			sortable : true,
			datacode : 'YJFS',
			width : '60'
		}, {
			display : '״̬',
			name : 'mailStatus',
			sortable : true,
			process : function(v) {
				if (v == "1") {
					return "δǩ��";
				} else {
					return "��ǩ��";
				}
			},
			width : '60'
		}, {
			display : '�ͻ�ǩ����',
			name : 'signMan',
			sortable : true
		}, {
			display : 'ǩ��ʱ��',
			name : 'signDate',
			sortable : true,
			width : '100'
		}],
		// ��������
		searchitems : [{
			display : '�ռ���',
			name : 'receiver'
		}, {
			display : '�ļ���',
			name : 'mailMan'
		}, {
			display : '�ʼķ�ʽ',
			name : 'mailType'
		}],
		// Ĭ������˳��
		sortorder : "DESC"

	});
});