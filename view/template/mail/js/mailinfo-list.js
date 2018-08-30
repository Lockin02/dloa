// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#mailinfoGrid").yxgrid("reload");
};
$(function() {
	$("#mailinfoGrid").yxgrid({
		model : 'mail_mailinfo',
		title : "�ʼ���Ϣ",
		isToolBar:false,
		showcheckbox:false,
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
			display : '�ռ���',
			name : 'receiver',
			sortable : true,
			width : 80
		}, {
			display : '�ռ��˵绰',
			name : 'tel',
			sortable : true
		}, {
			display : 'ҵ��Ա',
			name : 'salesman',
			sortable : true
		}, {
			display : '�ʼ���',
			name : 'mailMan',
			sortable : true
		}, {
			display : '�ʼ�����',
			name : 'mailTime',
			sortable : true,
			width : 80
		}, {
			display : '�ʼķ�ʽ',
			name : 'mailType',
			sortable : true,
			datacode : 'YJFS',
			width : 80,
			hide : true
		}, {
			display : '�ʼ�����',
			name : 'docType',
			sortable : true,
			width : 80,
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
			display : '����ҵ����',
			name : 'docCode',
			sortable : true,
			hide : true
		}, {
			display : '�ͻ�����',
			name : 'customerName',
			sortable : true,
			width : 130,
			hide : true
		}, {
			display : '������˾',
			name : 'logisticsName',
			sortable : true
		}, {
			display : '�ʼķ���',
			name : 'mailMoney',
			sortable : true,
			width : 60,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '״̬',
			name : 'mailStatus',
			sortable : true,
			process : function(v) {
				if (v == 1 ) {
					return "��ȷ��";
				} else {
					return "δȷ��";
				}
			},
			width : 80
		}, {
			display : 'ǩ��״̬',
			name : 'status',
			sortable : true,
			process : function(v) {
				if (v == 1 ) {
					return "��ǩ��";
				} else {
					return "δǩ��";
				}
			},
			width : 80
		}, {
			display : 'ǩ������',
			name : 'signDate',
			sortable : true,
			width : 80
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
				if(row.docType == 'YJSQDLX-FPYJ'){
					showThickboxWin("?model=mail_mailinfo&action=invoiceInit&perm=view&id="
						+ row.id
						+ '&docType=' + row.docType
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}else{
					showThickboxWin("?model=mail_mailinfo&action=shipInit&perm=view&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
					}
				}
			}, {
			name : 'view',
			text : "ǩ�ռ�¼",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status == 1 && row.docType == 'YJSQDLX-FPYJ') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailsign&action=read&id="
					+ "&docId=" + row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}, {
			name : 'edit',
			text : "�޸�",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.mailStatus == 0 && row.status == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if(row.docType == 'YJSQDLX-FPYJ'){
					showThickboxWin("?model=mail_mailinfo&action=invoiceInit&id="
						+ row.id
						+ '&docType=' + row.docType
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}else{
					showThickboxWin("?model=mail_mailinfo&action=shipInit&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
					}
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
					if(confirm('ȷ�ϵ�����ȷô?')){
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
								}else{
									alert('ȷ��ʧ��!');
								}
							}
						});
					}
				}
			}, {
			name : 'sign',
			text : "ǩ��",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status != 1 && row.docType == 'YJSQDLX-FPYJ') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=mail_mailsign&action=toAdd&id="
					+ row.id + "&signMan=" + row.receiver
					+ "&docId=" + row.docId
					+ "&docCode=" + row.docCode
					+ "&docType=" + row.docType
					+ "&mailNo=" + row.mailNo
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}
		],
		// ��������
		searchitems : [{
			display : '�ʼĵ���',
			name : 'mailNo'
		}, {
			display : '�ʼķ�Ʊ����',
			name : 'docCodeSearch'
		}, {
			display : '�ռ���',
			name : 'receiver'
		}, {
			display : '�ʼ���',
			name : 'mailMan'
		}],// ��������
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
		}, {
			text : 'ǩ��״̬',
			key : 'status',
			value : '0',
			data : [{
				text : 'δǩ��',
				value : '0'
			}, {
				text : '��ǩ��',
				value : '1'
			}]
		}],
		// Ĭ������˳��
		sortorder : "DESC"

	});
});