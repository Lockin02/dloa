var show_page = function(page) {
	$("#chanceGrid").yxgrid("reload");
};
$(function() {
	$("#chanceGrid").yxgrid({
		model : 'projectmanagent_chance_chance',
		action : 'approvaledJson',
		title : '�����̻�',

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'chanceCode',
			display : '�̻����',
			sortable : true
		}, {
			name : 'chanceName',
			display : '�̻�����',
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'trackman',
			display : '������',
			sortable : true
		}, {
			name : 'customerProvince',
			display : '�ͻ�����ʡ��',
			sortable : true
		}, {
			name : 'customerType',
			display : '�ͻ�����',
			datacode : 'KHLX',
			sortable : true
		}, {
			name : 'status',
			display : '�̻�״̬',
			process : function(v) {
				if (v == 0) {
					return "��ת�̻�";
				}else if(v == 3){
					return "�ر�";
				}else if(v == 4){
					return "��ת����";
				}else if(v == 5){
				    return "����"
				}
//				return "�ɽ���״̬";

			},
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}],
//		param : {
//			ExaStatus : '���'
//		},
		//��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=projectmanagent_chance_chance&action=init&perm=view&id=" + row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}

		}
//		,{
//			text : '���ɶ���',
//			icon : 'add',
//			action : function (row,rows,grid){
//				if(row){
//					parent.location = "?model=projectmanagent_order_order&action=toAdd&id="+ row.id;
//				}else{
//					akert("��ѡ��һ������");
//				}
//			}
//		}
		],
		//��������
		searchitems : [{
			display : '�̻�����',
			name : 'chanceName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}],
		//ҵ���������
		//Ĭ�������ֶ���
		sortname : "id",
		//Ĭ������˳��
		sortorder : "ASC",
		//��ʾ�鿴��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false
	});
});