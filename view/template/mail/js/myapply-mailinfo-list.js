// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".myMailinfoGrid").yxgrid("reload");
};
$(function() {
	$(".myMailinfoGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailinfo',
		action : 'mylogpageJson&mailApplyId=' + $('#mailApplyId').val(),
		title : "�ʼ���Ϣ",
		isToolBar:false,
		showcheckbox:false,
		isRightMenu : false,
		//��չ��ť
		buttonsEx : [{
			name : 'return',
			text : '����',
			icon : 'view',
			action : function(row, rows, grid) {
				location = "?model=mail_mailapply&action=toMyApplyList";

			}
		}],
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
			sortable : true
		}, {
			display : '�ļ���',
			name : 'mailMan',
			sortable : true
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