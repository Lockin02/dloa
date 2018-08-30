var show_page = function(page) {
	$("#supportGrid").yxgrid("reload");
};
$(function() {
	$("#supportGrid").yxgrid({
		model : 'projectmanagent_support_support',
		title : '��ǰ֧������',
		param : {'SingleId' : $("#chanceId").val()},
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'supportCode',
			display : '���ݱ��',
			sortable : true,
			width : 150
		}, {
			name : 'prinvipalName',
			display : '���۾���',
			sortable : true
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'signSubjectName',
			display : '֧�����',
			sortable : true
		}, {
			name : 'exchangeName',
			display : '���齻����Ա',
			sortable : true
		}, {
			name : 'linkman',
			display : '�ͻ���ϵ��',
			sortable : true
		}, {
			name : 'contact',
			display : '��ϵ��ʽ',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}],
        // ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_support_support&action=toView&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		}, {
			text : '�༭',
			icon : 'edit',
            showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_support_support&action=toEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				}
			}

		}, {
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row && row.ExaStatus == '��������') {
					return true;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_sale_chance_support&pid='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row && (row.ExaStatus == 'δ����' || row.ExaStatus == '���')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				 if (window.confirm(("ȷ���ύ������?"))) {
					showThickboxWin('controller/projectmanagent/support/ewf_support_index.php?actTo=ewfSelect&billId='
										+ row.id
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		}
		],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���ݱ��',
			name : 'supportCode'
		}, {
			display : '��Ŀ����',
			name : 'projectName'
		}, {
			display : '��Ŀ���',
			name : 'projectCode'
		}]
	});
});