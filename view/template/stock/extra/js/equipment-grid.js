var show_page = function(page) {
	$("#equipmentGrid").yxgrid("reload");
};
$(function() {
	$("#equipmentGrid").yxgrid({
		model : 'stock_extra_equipment',
		title : '�����豸������Ϣ',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'equipName',
			display : '�豸����',
			sortable : true,
			width : 200
		}, {
			name : 'isProduce',
			display : '�Ƿ�ͣ��',
			sortable : true,
			process : function(v, row) {
				if (v == "0") {
					return "��";
				} else {
					return "��";
				}
			}
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		} ],
		// // ���ӱ������
		// subGridOptions : {
		// url : '?model=stock_extra_equipmentpro&action=pageItemJson',
		// param : [ {
		// paramId : 'mainId',
		// colId : 'id'
		// } ],
		// colModel : [ {
		// name : 'XXX',
		// display : '�ӱ��ֶ�'
		// } ]
		// },

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "�豸����",
			name : 'equipName'
		} ]
	});
});