var show_page = function(page) {
	$("#replacedGrid").yxsubgrid("reload");
};
$(function() {
	$("#replacedGrid").yxsubgrid({
		model : 'engineering_resources_replaced',
		title : '�豸����-���滻�豸����',
//		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'deviceName',
			display : '�豸����',
			sortable : true,
			width : 180
		}, {
			name : 'deviceId',
			display : '�豸ID',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 180
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=engineering_resources_replacedinfo&action=pageJson',
			param : [{
				paramId : 'replacedId',
				colId : 'id'
			}],
			colModel : [{
				name : 'deviceName',
				display : '�豸����',
				width : 100
			},{
				name : 'remark',
				display : '��ע',
				width : 200
			}]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "�豸����",
			name : 'deviceName'
		}]
	});
});