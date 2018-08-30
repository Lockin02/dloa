var show_page = function(page) {
	$("#tstaskGrid").yxgrid("reload");
};
$(function() {
	$("#tstaskGrid").yxgrid({
		model : 'techsupport_tstask_tstask',
		param : {
			"statusIn" : 'XMZT-02,XMZT-03'
		},
		title : '��ǰ֧��',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'formNo',
					display : '���ݱ��',
					sortable : true,
					width : 120
				}, {
					name : 'objName',
					display : '������Ŀ����',
					sortable : true
				}, {
					name : 'salesman',
					display : '���۸�����',
					sortable : true
				}, {
					name : 'trainDate',
					display : '����ʱ��',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 150
				}, {
					name : 'cusLinkman',
					display : '�ͻ���ϵ��',
					sortable : true
				}, {
					name : 'cusLinkPhone',
					display : '�ͻ���ϵ�绰',
					sortable : true
				}, {
					name : 'technicians',
					display : '������Ա',
					sortable : true
				}, {
					name : 'status',
					display : '��ǰ״̬',
					sortable : true,
					datacode : 'XMZT'
				}, {
					name : 'createTime',
					display : '����ʱ��',
					sortable : true,
					width : 120
				}],
		toAddConfig : {
			formWidth : 900,
			formHeight : 500,
			action : 'toSelect'
		},
		toEditConfig : {
			formWidth : 900,
			formHeight : 500
		},
		toViewConfig : {
			formWidth : 900,
			formHeight : 500
		},
		menusEx : [{
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 'XMZT-01') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=techsupport_tstask_tstask&action=init&id='
							+ row.id
							+ '&skey='
							+ row.skey_
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
		}, {
			text : '��д�����¼',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == 'XMZT-03') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=techsupport_tstask_tstask&action=handup&id='
							+ row.id
							+ '&skey='
							+ row.skey_
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == 'XMZT-01') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=techsupport_tstask_tstask&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									show_page(1);
								} else {
									alert('ɾ��ʧ��');
									show_page(1);
								}
							}
						});
					}
				}
			}
		}],
		searchitems : [{
					display : '���ݱ��',
					name : 'formNoSearch'
				}],
		// ��������
		comboEx : [{
			text : '״̬',
			key : 'status',
			data : [{
					text : '���ύ',
					value : 'XMZT-03'
				}, {
					text : '���',
					value : 'XMZT-02'
				}]
			}
		]
	});
});