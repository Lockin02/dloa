var show_page = function(page) {
	$("#mypresentGrid").yxgrid("reload");
};
$(function() {
	$("#mypresentGrid").yxgrid({
		model : 'projectmanagent_present_present',
		param : {
			'salesNameId' : $("#user").val()
		},
		title : '��������',
		// ��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "����",
			icon : 'add',
			action : function(row) {
				showModalWin('?model=projectmanagent_present_present&action=toAdd');
			}
		}],
		comboEx : [{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : 'δ����',
				value : 'δ����'
			}, {
				text : '��������',
				value : '��������'
			}, {
				text : '���',
				value : '���'
			},
				{
					text: '����ȷ��',
					value: '����ȷ��'
				},
				{
					text: '���������',
					value: '���������'
				}]
		}],
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'Code',
					display : '���',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true
				}, {
					name : 'salesName',
					display : '������',
					sortable : true
				}, {
					name : 'reason',
					display : '��������',
					sortable : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true
				}, {
					name : 'objCode',
					display : 'ҵ����',
					width : 120
				}, {
					name : 'orderCode',
					display : 'Դ�����',
					width : 120
				}, {
					name : 'orderName',
					display : 'Դ������',
					width : 120
				}],
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_present_present&action=viewTab&id="
							+ row.id + "&skey=" + row['skey_']);

				}
			}

		}, {
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if ((row.ExaStatus == 'δ����') || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_present_present&action=init&id="
							+ row.id + "&skey=" + row['skey_']);

				}
			}
		}, {
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == 'δ����') || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_present_present&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page(1);
							} else {
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
		}, {
		// 	text : '�ύ���',
		// 	icon : 'add',
		// 	showMenuFn : function(row) {
		// 		if (row.ExaStatus == 'δ����' || row.ExaStatus == '���') {
		// 			return true;
		// 		}
		// 		return false;
		// 	},
		// 	action : function(row, rows, grid) {
		// 		if (row) {
		// 			showThickboxWin('controller/projectmanagent/present/ewf_present.php?actTo=ewfSelect&billId='
		// 					+ row.id
		// 					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
		// 		}
		// 	}
        //
		// }, {

			text : '���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_present_present&action=toChange&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���',
			name : 'Code'
		},{
			display : '������',
			name : 'salesName'
		},{
			display : 'Դ�����',
			name : 'orderCode'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}]
	});
});