var show_page = function(page) {
	$("#supportGrid").yxgrid("reload");
};
$(function() {
	$("#supportGrid").yxgrid({
		model : 'projectmanagent_support_support',
		title : '��ǰ֧������',
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

		}
//		, {
//			text : '�༭',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == '���' || row.ExaStatus == '��������') {
//					return false;
//				}
//				return true;
//			},
//			action : function(row) {
//				if (row) {
//					showModalWin("?model=projectmanagent_support_support&action=toEdit&id="
//							+ row.id
//							+ "&skey="
//							+ row['skey_']
//							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
//				}
//			}
//
//		}
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