// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".mailshipGrid").yxgrid("reload");
};
$(function() {
	$(".mailshipGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailinfo',
		action : 'shipJson',
		param : {
			docType : $('#docType').val()
		},
		title : "�ʼ���Ϣ",
		isToolBar : false,
		showcheckbox : false,
		// isAddAction : false,
		// isViewAction : false,
		// isEditAction : false,
		// isDelAction:false,

		// ��������
		comboEx : [{
			text : '״̬',
			key : 'mailStatus',
			data : [{
				text : 'δȷ��',
				value : '0'
			}, {
				text : '��ȷ��',
				value : '1'
			}]
		}],
//		 ��չ��ť
		 buttonsEx : [{
			 name : 'import',
			 text : '�ʼķ�����Ϣ����',
			 icon : 'excel',
			 action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailinfo&action=toFareImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=500");
			 }
		 }],

		menusEx : [{
			name : 'view',
			text : "�鿴",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailinfo&action=shipInit&perm=view&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		}, {
			name : 'edit',
			text : "�޸�",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.mailStatus == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailinfo&action=shipInit&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
			}
		}, {
			name : 'edit',
			text : "ȷ��",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.mailStatus == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (confirm('ȷ�ϵ�����ȷô?')) {
					$.ajax({
						type : "POST",
						url : "?model=mail_mailinfo&action=confirm",
						data : {
							"id" : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ȷ�ϳɹ���');
								show_page(1);
							} else {
								alert('ȷ��ʧ��!');
							}
						}
					});
				}
			}
				// }, {
				// name : 'readMailMessage',
				// text : "�ͻ�ǩ��",
				// icon : 'view',
				// showMenuFn : function(row) {
				// if (row.mailStatus == '1') {
				// return true;
				// }
				// return false;
				// },
				// action : function(row, rows, grid) {
				// showThickboxWin("?model=mail_mailsign&action=toAdd&mailInfoId="
				// + row.id
				// +
				// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				// }
				}],

		// toAddConfig : {
		// text : '����',
		// /**
		// * Ĭ�ϵ��������ť�����¼�
		// */
		// toAddFn : function(p) {
		// var c = p.toAddConfig;
		// var w = c.formWidth ? c.formWidth : p.formWidth;
		// var h = c.formHeight ? c.formHeight : p.formHeight;
		// showThickboxWin("?model=mail_mailinfo&action=toAdd&mailApplyId="
		// + $('#mailApplyId').val()
		// +
		// "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
		// }
		// },

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�ʼ�����',
			name : 'docType',
			sortable : true,
			process : function(v){
				if(v == 'YJSQDLX-FPYJ'){
					return '��Ʊ�ʼ�';
				}else if(v == 'YJSQDLX-FHYJ'){
					return '�����ʼ�';
				}else{
					return v;
				}
			}
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
			display : '������˾',
			name : 'logisticsName',
			sortable : true,
			width : '100'
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
				if (v == "0") {
					return "δȷ��";
				} else {
					return "��ȷ��";
				}
			},
			width : '60'
				// }, {
				// display : '�ͻ�ǩ����',
				// name : 'signMan',
				// sortable : true
				// }, {
				// display : 'ǩ��ʱ��',
				// name : 'signDate',
				// sortable : true,
				// width : '100'
				}],
		// ��������
		searchitems : [{
			display : '�ʼĵ���',
			name : 'mailNo'
		}, {
			display : '�ռ���',
			name : 'receiver'
		}, {
			display : '�ļ���',
			name : 'mailMan'
		}],
		// Ĭ������˳��
		sortorder : "DESC"

	});
});