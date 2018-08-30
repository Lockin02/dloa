var show_page = function(page) {
	$("#cusMoneyGrid").yxgrid("reload");
};
$(function() {
	$("#cusMoneyGrid").yxgrid({
		model : 'projectmanagent_borrow_money',
//		action : 'MyBorrowPageJson',
		param : {
			'borrowType' : '�ͻ�',
			'controlType' : 'area'
		},
		title : '�ͻ������ý�����',
		//��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		//isDelAction : false,
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "��ʼ��",
			icon : 'add',

			action : function(row) {
				showOpenWin('?model=projectmanagent_borrow_money&action=toAdd');
			}
		}],
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'areaName',
			display : '��������',
			sortable : true,
			width : 150
		}, {
			name : 'maxMoney',
			display : '�����ý��',
			sortable : true,
			process : function(v) {
						return moneyFormat2(v);
					},
		    width : 150
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '����',
			name : 'areaName'
		}],
		// ��չ�Ҽ��˵�
		menusEx : [ {
			text : '�޸Ľ��',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=projectmanagent_borrow_money&action=editMoney&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600")
				} else {
					alert("��ѡ��һ������");
				}
			}
		}]
	});

});