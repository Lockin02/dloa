var show_page = function(page) {
	$("#propersonalGrid").yxgrid("reload");
};
$(function() {
	$("#propersonalGrid").yxgrid({
		model : 'projectmanagent_borrow_money',
//		action : 'MyBorrowPageJson',
		param : {
			'borrowType' : 'Ա��',
			'controlType' : 'user'
		},
		title : 'Ա�������ˣ������ý�����',
		//��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		//isDelAction : false,
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "����",
			icon : 'add',

			action : function(row) {
				showThickboxWin('?model=projectmanagent_borrow_money&action=toProAdd&type=person'
				                   + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
			}
		}],
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : '��Ա����',
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
					showThickboxWin("?model=projectmanagent_borrow_money&action=proeditMoney&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600")
				} else {
					alert("��ѡ��һ������");
				}
			}
		}]
	});

});