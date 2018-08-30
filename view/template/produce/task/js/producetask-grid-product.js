var show_page = function(page) {
	$("#producetaskGrid").yxgrid("reload");
};

$(function() {
	$("#producetaskGrid").yxgrid({
		model : 'produce_task_producetask',
		action : 'productPageJson',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		title : '���ϻ���',
		// ����Ϣ
		colModel : [{
			display : 'productId',
			name : 'productId',
			sortable : true,
			hide : true
		},{
			name : 'proType',
			display : '��������',
			sortable : true,
			width : 100
		},{
			name : 'productCode',
			display : '���ñ���',
			sortable : true,
			width : 100
		},{
			name : 'productName',
			display : '��������',
			sortable : true,
			width : 300
		},{
			name : 'pattern',
			display : '����ͺ�',
			sortable : true,
			width : 150
		},{
			name : 'unitName',
			display : '��λ',
			sortable : true,
			width : 60,
			align : 'center'
		},{
			name : 'taskNum',
			display : '��������',
			sortable : true,
			width : 80,
			align : 'center',
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_apply_produceapply&action=toStatisticsProduct&code=" + row.productCode + "&num=" + row.taskNum + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'planNum',
			display : '���ƶ��ƻ�����',
			sortable : true,
			width : 80,
			align : 'center'
		},{
			name : 'inventory',
			display : '�������',
			width : 80,
			sortable : true,
			align : 'center'
		},{
			name : 'detail',
			display : '��ϸ',
			sortable : true,
			align : 'center',
			width : 60,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_task_producetask&action=toViewProduct&productId=" + row.productId + "\",1)'>��ϸ</a>";
			}
		}],

		toViewConfig : {
			toViewFn : function(p ,g) {
				if (g) {
					var row = g.getSelectedRow().data('data');
					showModalWin("?model=produce_task_producetask&action=toViewProduct&productId=" + row.productId ,'1');
				}
			}
		},

		//��չ�Ҽ��˵�
		menusEx : [],

		searchitems : [{
			display : "��������",
			name : 'proTypeTask'
		},{
			display : "���ñ���",
			name : 'productCode'
		},{
			display : "��������",
			name : 'productName'
		}]
	});
});