var show_page = function(page) {
	$("#budgetGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [{
		name : 'add',
		text : "Ԥ���˵��ά��",
		icon : 'add',
		action : function(row) {
				showModalWin("?model=equipment_budget_budget&action=explain"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
	}],
	$("#budgetGrid").yxgrid({
		model : 'equipment_budget_budget',
		title : '�豸Ԥ���',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=equipment_budget_budget&action=toView&id="
						+ data.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'budgetGrid',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'budgetCode',
			display : '���',
			sortable : true
		}, {
			name : 'budgetTypeName',
			display : '����',
			sortable : true
		}, {
			name : 'equName',
			display : '�豸����',
			sortable : true,
			width : 200
		}, {
			name : 'equRemark',
			display : '�豸��ע',
			sortable : true,
			hide : true
		}, {
			name : 'allMoney',
			display : '�ܽ��',
			sortable : true,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'status',
			display : '״̬',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'useEndDate',
			display : '��Ч��ֹ����',
			sortable : true
		}],
        menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=equipment_budget_budget&action=toView&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
			}
		}
//		, {
//			text : 'ɾ��',
//			icon : 'delete',
//			action : function(row, rows, grid) {
//				if (window.confirm("ȷ��Ҫɾ��?")) {
//					$.ajax({
//						type : "POST",
//						url : "?model=equipment_budget_budgetbaseinfo&action=ajaxdeletes",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								show_page();
//								alert('ɾ���ɹ���');
//							} else {
//								alert('ɾ��ʧ��!');
//							}
//						}
//					});
//				}
//			}
//		}
		],
		buttonsEx : buttonsArr,
		// ��������
		searchitems : [{
			display : '���',
			name : 'budgetCode'
		}, {
			display : '����',
			name : 'budgetTypeName'
		}, {
			display : '�豸����',
			name : 'equName'
		}]
	});
});